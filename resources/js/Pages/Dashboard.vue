<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    inserts: {
        type: Array,
        default: () => []
    },
    userId: {
        type: Number,
        required: true
    }
});

console.log('Inserts data:', props.inserts);

const showModal = ref(false);
const selectedInsert = ref(null);
const joined = ref(false);
const loading = ref(false);

function toKST(dateStr) {
    const utc = new Date(dateStr);
    return new Date(utc.getTime() + 9 * 60 * 60 * 1000);
}

const formatDateKST = (dateStr) => {
    const d = toKST(dateStr);
    return d.toLocaleString('ko-KR', { timeZone: 'Asia/Seoul' });
};

function getParticipantCount(insert) {
    const hasWriter = insert.buys.some(buy => buy.user_id === insert.user_id);
    return insert.buys.length + (hasWriter ? 0 : 1);
}

const openModal = (insert) => {
    selectedInsert.value = insert;
    showModal.value = true;
    joined.value = insert.buys.some(buy => buy.user_id === props.userId);
};
const closeModal = () => {
    showModal.value = false;
    selectedInsert.value = null;
    joined.value = false;
};

const copyLink = () => {
    if (selectedInsert.value) {
        const url = `${window.location.origin}/dashboard?id=${selectedInsert.value.id}`;
        navigator.clipboard.writeText(url);
        alert('링크가 복사되었습니다!');
    }
};

const handleBuy = async () => {
    if (!selectedInsert.value) return;
    loading.value = true;
    try {
        if (!joined.value) {
            if (props.userId === selectedInsert.value.user_id) {
                alert('자신이 등록한 공동구매에는 참여할 수 없습니다.');
                return;
            }
            await axios.post('/buy', { 
                insert_id: selectedInsert.value.id,
                created_at: new Date().toISOString()
            });
            selectedInsert.value.buys.push({ 
                user_id: props.userId,
                created_at: new Date().toISOString()
            });
            joined.value = true;
        } else {
            const buyCount = selectedInsert.value.buys.length;
            if (buyCount >= selectedInsert.value.count - 1) {
                alert('최대 인원수 또는 최대-1에 도달하여 취소가 불가합니다.');
                return;
            }
            await axios.delete('/buy', { data: { insert_id: selectedInsert.value.id } });
            selectedInsert.value.buys = selectedInsert.value.buys.filter(buy => buy.user_id !== props.userId);
            joined.value = false;
        }
    } catch (e) {
        alert(e.response?.data?.message || '오류가 발생했습니다.');
    } finally {
        loading.value = false;
    }
};

const canCancel = () => {
    if (!selectedInsert.value) return false;
    const buyCount = selectedInsert.value.buys.length;
    return joined.value && buyCount !== selectedInsert.value.count && buyCount !== selectedInsert.value.count - 1;
};

const canBuy = () => {
    if (!selectedInsert.value) return false;
    const buyCount = selectedInsert.value.buys.length;
    return !joined.value && buyCount < selectedInsert.value.count;
};

const calculateTimeLeft = (createdAt) => {
    const created = new Date(createdAt);
    const now = new Date();
    const diff = now - created;
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    
    return `${days}일 ${hours}시간 ${minutes}분 전`;
};

const calculateTimeUntilDeadline = (deadline) => {
    const deadlineDate = new Date(deadline);
    const now = new Date();
    const diff = deadlineDate - now;

    if (diff <= 0) {
        return '마감되었습니다:('; 
    }

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

    return `${days}일 ${hours}시간 ${minutes}분 후 마감`;
};

const calculatePricePerPerson = (totalPrice, participantCount) => {
    return Math.floor(totalPrice / participantCount).toLocaleString();
};
</script>

<template>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">공동구매 목록</h1>
        
        <div v-if="!inserts || inserts.length === 0" class="text-center text-gray-500 py-8">
            등록된 공동구매가 없습니다.
        </div>
        
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="insert in inserts" :key="insert.id" 
                 class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow cursor-pointer"
                 @click="openModal(insert)">
                <h2 class="text-xl font-semibold mb-4">{{ insert.item }}</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">링크:</span>
                        <a :href="insert.link" target="_blank" 
                           class="text-blue-600 hover:underline truncate max-w-[200px]"
                           @click.stop>{{ insert.link }}</a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">모집인원:</span>
                        <span>{{ getParticipantCount(insert) }}/{{ insert.count }}명</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">총 금액:</span>
                        <span>{{ insert.price.toLocaleString() }}원</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">1인당 금액:</span>
                        <span>{{ calculatePricePerPerson(insert.price, getParticipantCount(insert)) }}원</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">작성자:</span>
                        <span>{{ insert.user ? insert.user.name : '알 수 없음' }}</span>
                    </div>
                    <div class="flex justify-between" v-if="calculateTimeUntilDeadline(insert.deadline)">
                        <span class="text-gray-600">모집:</span>
                        <span>{{ calculateTimeUntilDeadline(insert.deadline) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
                <button @click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                <h2 class="text-2xl font-bold mb-4">{{ selectedInsert.item }}</h2>
                <div class="mb-2 text-gray-700">{{ selectedInsert.user ? selectedInsert.user.name : '알 수 없음' }} | {{ calculateTimeUntilDeadline(selectedInsert.deadline) }}</div>
                <div class="mb-2"><span class="font-semibold">링크:</span> <a :href="selectedInsert.link" target="_blank" class="text-blue-600 hover:underline">{{ selectedInsert.link }}</a></div>
                <div class="mb-2"><span class="font-semibold">모집인원:</span> {{ getParticipantCount(selectedInsert) }}/{{ selectedInsert.count }}명</div>
                <div class="mb-2"><span class="font-semibold">총 금액:</span> {{ selectedInsert.price.toLocaleString() }}원</div>
                <div class="mb-2"><span class="font-semibold">1인당 금액:</span> {{ calculatePricePerPerson(selectedInsert.price, getParticipantCount(selectedInsert)) }}원</div>
                <div class="mb-4"><span class="font-semibold">마감일:</span> {{ formatDateKST(selectedInsert.deadline) }}</div>
                <div class="flex gap-2 mt-4">
                    <button @click="copyLink" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">공유</button>
                    <template v-if="props.userId !== selectedInsert.user_id">
                        <button v-if="!joined" :disabled="!canBuy() || loading" @click="handleBuy" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:opacity-50">살래요</button>
                        <button v-else-if="joined" :disabled="!canCancel() || loading" @click="handleBuy" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 disabled:opacity-50">취소할래요</button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
