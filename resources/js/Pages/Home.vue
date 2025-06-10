<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import Container from './components/Container.vue';
import { onMounted, ref, onUnmounted } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { router } from '@inertiajs/vue3';

const featuresSection = ref(null);
const currentLocation = ref(0);
const locations = ['🏢직장', '🏫학교', '🏠아파트'];
let intervalId = null;

const scrollToFeatures = () => {
    featuresSection.value?.scrollIntoView({ behavior: 'smooth' });
};

const goToSignup = () => {
    router.visit('/signup');
};

onMounted(() => {
    AOS.init({
        duration: 1000,
        once: false,
        mirror: true
    });

    intervalId = setInterval(() => {
        currentLocation.value = (currentLocation.value + 1) % locations.length;
    }, 2000);
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<template>
    <Head title="공구 - 함께하면 더 저렴해요." />
    <Header />
    <main class="pt-[72px]">
        <section class="min-h-[95vh] bg-gradient-to-b from-white via-[#2F9266]/7 to-[#2F9266]/15 text-gray-800 flex items-center relative">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium">
                        <span class="inline-block min-w-[120px] h-[1.2em] overflow-hidden">
                            <transition name="slide" mode="out-in">
                                <span :key="currentLocation" class="block">
                                    {{ locations[currentLocation] }}
                                </span>
                            </transition>
                        </span>
                    </h1>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium mb-8">
                        주변의 공동구매를 쉽게 연결해드려요!
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl text-gray-600">
                        원하는 물건 찾아보고, 함께 구매해요.
                    </p>
                    <p class="text-lg sm:text-xl md:text-2xl mb-12 text-gray-600">
                        <span class="text-base sm:text-lg md:text-xl">더</span> <span class="text-lg sm:text-xl md:text-2xl">더</span> <span class="text-xl sm:text-2xl md:text-3xl">더</span> 많은 사람이 모일수록 더 저렴해져요.
                    </p>
                    <button 
                        @click="goToSignup"
                        class="bg-[#2F9266] text-white px-8 sm:px-12 py-3 sm:py-4 rounded-full font-bold text-lg sm:text-xl transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-[0_8px_20px_rgba(47,146,102,0.3)] active:scale-[0.98] shadow-[0_4px_12px_rgba(47,146,102,0.2)]"
                    >
                        지금 시작하기
                    </button>
                </div>
            </div>
        </section>

        <section class="py-12 sm:py-16 md:py-20 bg-white">
            
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-10 xl:gap-14">
                    <div class="flex items-center justify-center xl:justify-start flex-shrink-0">
                        <div class="w-[130px] h-[130px] sm:w-[220px] sm:h-[220px] relative flex items-center justify-center">
                            <img src="/public/images/map.png" alt="Email Icon" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="text-center xl:text-left flex flex-col justify-center max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-bold mb-6 text-gray-800 leading-tight">
                            복잡한 주소 입력은 이제 그만!<br/>
                            <span class="text-[#2F9266]">지도</span>에서 간편하게
                        </h2>
                        <p class="text-base sm:text-lg xl:text-xl text-gray-600 leading-relaxed">
                            공구를 등록할 때마다 배송지를 일일이 주소로 입력하는 번거로움을 덜어드려요. 바쁜 여러분을 대신해서 지도에서 클릭 한 번으로 정확한 배송 위치를 설정할 수 있도록 직관적인 지도 기능을 제공합니다.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
            
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-12 xl:gap-20">
                    <div class="text-center xl:text-left order-2 xl:order-1 flex flex-col justify-center max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-bold mb-6 text-gray-800 leading-tight">
                            배송부터 완료까지<br/>
                            놓지지 말고 확인하세요.
                        </h2>
                        <p class="text-base sm:text-lg xl:text-xl text-gray-600 leading-relaxed">
                            매일 공구 참여 후 배송이 언제 오는지 궁금해서 계속 확인하는 번거로움을 덜어드려요. 공구 등록자가 배송 상태를 업데이트할 때마다 참여자들에게 실시간으로 알림을 보내드립니다. 배송 준비부터 배송 중, 배송 완료까지 각 단계별 상태를 푸시 알림으로 전달해드립니다.
                        </p>
                    </div>

                    <div class="flex items-center justify-center xl:justify-end order-1 xl:order-2 flex-shrink-0">
                        <div class="w-[110px] h-[110px] sm:w-[210px] sm:h-[210px] relative flex items-center justify-center">
                            <img src="/public/images/bell.png" alt="Pencil Icon" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-12 sm:py-16 md:py-20 bg-white">
            
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12 flex items-center justify-center min-h-[50vh]">
                    <div class="text-center xl:text-center flex flex-col justify-center max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-bold mb-6 text-gray-800 leading-tight">
                            주변 사람들과 공동구매로 <span class="text-[#2F9266]">28%</span><span class="text-base text-gray-400 font-medium ml-1">5명 기준</span><br/>
                            <span class="text-[#2F9266]">할인 받으며 구매해요.</span>
                        </h2>
                        <p class="text-base sm:text-lg xl:text-xl text-gray-600 leading-relaxed">
                            5명이 함께 구매하면 평균 28%의 할인 혜택을 받으며<br>
                            개인 구매 대비 상당한 비용 절약이 가능합니다.
                        </p>
                    </div>
                </div>
        </section>

        <section class="py-16 sm:py-24 md:py-32 bg-white">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6 sm:mb-8">지금 바로 시작하세요</h2>
                    <p class="text-lg sm:text-xl md:text-2xl text-gray-600 mb-8 sm:mb-12">원하는 상품을 찾아보고, 함께 구매하세요.</p>
                    <button class="bg-blue-600 text-white px-8 sm:px-12 py-3 sm:py-4 rounded-full font-bold text-lg sm:text-xl hover:bg-blue-700 transition-colors transform hover:scale-105">
                        무료로 시작하기
                    </button>
                </div>
            </div>
        </section>
        
    </main>
    <Footer />
</template>

<style>
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.8s ease;
}

.slide-enter-from {
    transform: translateY(100%);
    opacity: 0;
}

.slide-leave-to {
    transform: translateY(-100%);
    opacity: 0;
}
</style>