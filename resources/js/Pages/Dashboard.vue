<script setup>
import { ref } from 'vue';

const props = defineProps({
    inserts: {
        type: Array,
        default: () => []
    }
});

console.log('Inserts data:', props.inserts);

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
                 class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <h2 class="text-xl font-semibold mb-4">{{ insert.item }}</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">링크:</span>
                        <a :href="insert.link" target="_blank" 
                           class="text-blue-600 hover:underline truncate max-w-[200px]">
                            {{ insert.link }}
                        </a>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">모집인원:</span>
                        <span>{{ insert.count }}명</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">총 금액:</span>
                        <span>{{ insert.price.toLocaleString() }}원</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">1인당 금액:</span>
                        <span>{{ calculatePricePerPerson(insert.price, insert.count) }}원</span>
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
    </div>
</template>
