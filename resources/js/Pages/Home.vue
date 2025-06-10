<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import Container from './components/Container.vue';
import { onMounted, ref } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';

const featuresSection = ref(null);

const scrollToFeatures = () => {
    featuresSection.value?.scrollIntoView({ behavior: 'smooth' });
};

onMounted(() => {
    AOS.init({
        duration: 1000,
        once: false,
        mirror: true
    });
});
</script>

<template>
    <Head title="공구 - 함께하면 더 저렴해요." />
    <Header />
    <main class="pt-[72px]">
        <section class="min-h-[95vh] bg-gradient-to-b from-white via-[#2F9266]/7 to-[#2F9266]/15 text-gray-800 flex items-center relative">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <img src="/public/images/box.png" alt="box" class="w-32 sm:w-40 md:w-52 h-32 sm:h-40 md:h-52 mx-auto mb-6">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium= mb-4">
                        회사, 학교, 아파트…
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
                </div>
            </div>
        </section>

        <section ref="featuresSection" class="py-16 sm:py-24 md:py-32 bg-white">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-12 md:gap-20">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-blue-100 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8 transform hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">함께 모여 더 저렴하게</h3>
                        <p class="text-base sm:text-lg text-gray-600">많은 사람이 참여할수록 더 큰 할인을 받을 수 있습니다.</p>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-blue-100 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8 transform hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">안전한 거래</h3>
                        <p class="text-base sm:text-lg text-gray-600">검증된 판매자와 안전한 결제 시스템을 제공합니다.</p>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="bg-blue-100 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center mx-auto mb-6 sm:mb-8 transform hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">빠른 배송</h3>
                        <p class="text-base sm:text-lg text-gray-600">목표 인원 달성 시 즉시 주문 처리 및 배송이 시작됩니다.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 sm:py-24 md:py-32 bg-gray-50">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-center mb-8 sm:mb-16" data-aos="fade-up">이미 많은 분들이 이용하고 있습니다</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 md:gap-12">
                    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg transform hover:scale-105 transition-transform" data-aos="fade-up" data-aos-delay="100">
                        <p class="text-lg sm:text-xl text-gray-600 mb-4 sm:mb-6">"같은 상품을 30% 더 저렴하게 구매할 수 있었어요. 정말 만족스러운 경험이었습니다."</p>
                        <div class="font-bold text-base sm:text-lg">김○○ 님</div>
                    </div>
                    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg transform hover:scale-105 transition-transform" data-aos="fade-up" data-aos-delay="200">
                        <p class="text-lg sm:text-xl text-gray-600 mb-4 sm:mb-6">"안전하게 거래할 수 있어서 좋았어요. 다음에도 꼭 이용할 예정입니다."</p>
                        <div class="font-bold text-base sm:text-lg">이○○ 님</div>
                    </div>
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
</style>