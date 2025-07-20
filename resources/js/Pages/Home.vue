<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import { onMounted, ref, onUnmounted } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { router } from '@inertiajs/vue3';
import mainMapImage from '/public/images/main-map.png';
import mainBellImage from '/public/images/main-bell.png';
import mainDiscountImage from '/public/images/main-discount.png';

const featuresSection = ref(null);
const currentLocation = ref(0);
const locations = ['🏢직장', '🏫학교', '🏠아파트'];
let intervalId = null;
const animatedDiscount = ref(0);
const animatedPurchases = ref(0);
const typingText = ref('');
const isTyping = ref(false);
const statsSection = ref(null);
const hasAnimated = ref(false);
const chatSection = ref(null);
const hasTyped = ref(false);
const shouldAnimate = ref(false);

const scrollToFeatures = () => {
    featuresSection.value?.scrollIntoView({ behavior: 'smooth' });
};

const goToSignup = () => {
    router.visit('/signup');
};

const startCountingAnimation = () => {
    const targetValue = 28;
    const duration = 2000;
    let startTime = null;

    const easeOutExpo = (x) => {
        return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
    };

    const animate = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / duration, 1);
        const easedProgress = easeOutExpo(progress);
        const current = Math.floor(easedProgress * targetValue);
        animatedDiscount.value = current;

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            animatedDiscount.value = targetValue;
        }
    };
    requestAnimationFrame(animate);
};

const startPurchasesAnimation = () => {
    const targetValue = 3;
    const duration = 2000;
    let startTime = null;

    const easeOutExpo = (x) => {
        return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
    };

    const animate = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = Math.min((timestamp - startTime) / duration, 1);
        const easedProgress = easeOutExpo(progress);
        const current = Math.floor(easedProgress * targetValue);
        animatedPurchases.value = current;

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            animatedPurchases.value = targetValue;
        }
    };
    requestAnimationFrame(animate);
};

const startTypingAnimation = () => {
    const text = "🤔같이 살래";
    const questionMark = "?";
    let currentIndex = 0;
    isTyping.value = true;
    typingText.value = '';

    const type = () => {
        if (currentIndex < text.length) {
            typingText.value += text[currentIndex];
            currentIndex++;
            setTimeout(type, 150);
        } else {
            setTimeout(() => {
                typingText.value += `<span class="text-3xl sm:text-4xl xl:text-5xl">${questionMark}</span>`;
                isTyping.value = false;
                setTimeout(() => {
                    shouldAnimate.value = true;
                }, 500);
            }, 150);
        }
    };

    type();
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

    startCountingAnimation();

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                startPurchasesAnimation();
            }
        });
    }, {
        threshold: 0.5
    });

    const chatObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                shouldAnimate.value = false;
                startTypingAnimation();
            } else {
                shouldAnimate.value = false;
            }
        });
    }, {
        threshold: 0.5
    });

    if (statsSection.value) {
        statsObserver.observe(statsSection.value);
    }

    if (chatSection.value) {
        chatObserver.observe(chatSection.value);
    }
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<template>
    <Head title="주변의 공동구매를 쉽게 연결해드려요" />
    <Header />
    <main class="pt-[72px]">
        <section class="min-h-[95vh] bg-gradient-to-b from-white via-[#2F9266]/7 to-[#2F9266]/15 text-gray-800 flex items-center relative">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium" style="font-family: 'Gyeonggi_Title_Medium', sans-serif;">
                        <span class="inline-block min-w-[120px] h-[1.2em] overflow-hidden">
                            <transition name="slide" mode="out-in">
                                <span :key="currentLocation" class="block" style="font-family: 'Gyeonggi_Title_Medium', sans-serif;">
                                    {{ locations[currentLocation] }}
                                </span>
                            </transition>
                        </span>
                    </h1>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-medium mb-8" style="font-family: 'Gyeonggi_Title_Medium', sans-serif;">
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
                        class="bg-[#2F9266] text-white px-8 sm:px-12 py-3 sm:py-4 rounded-full font-semibold text-lg sm:text-xl transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-[0_8px_20px_rgba(47,146,102,0.3)] active:scale-[0.98] shadow-[0_4px_12px_rgba(47,146,102,0.2)]"
                    >
                        지금 시작하기
                    </button>
                </div>
            </div>
        </section>

        <section class="py-12 sm:py-16 md:py-20 bg-white">
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-10 xl:gap-14">
                    <div class="flex items-center justify-center xl:justify-start flex-shrink-0 hover-scale" data-aos="fade-right">
                        <div class="w-[130px] h-[130px] sm:w-[220px] sm:h-[220px] relative flex items-center justify-center">
                            <img :src="mainMapImage" alt="Email Icon" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="text-center xl:text-left flex flex-col justify-center max-w-2xl" data-aos="fade-left">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-semibold mb-6 text-gray-800 leading-tight">
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
                    <div class="text-center xl:text-left order-2 xl:order-1 flex flex-col justify-center max-w-2xl" data-aos="fade-right">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-semibold mb-6 text-gray-800 leading-tight">
                            배송부터 완료까지<br/>
                            놓지지 말고 확인하세요.
                        </h2>
                        <p class="text-base sm:text-lg xl:text-xl text-gray-600 leading-relaxed">
                            매일 공구 참여 후 배송이 언제 오는지 궁금해서 계속 확인하는 번거로움을 덜어드려요. 공구 등록자가 배송 상태를 업데이트할 때마다 참여자들에게 실시간으로 알림을 보내드립니다. 배송 준비부터 배송 중, 배송 완료까지 각 단계별 상태를 푸시 알림으로 전달해드립니다.
                        </p>
                    </div>

                    <div class="flex items-center justify-center xl:justify-end order-1 xl:order-2 flex-shrink-0" data-aos="fade-left">
                        <div class="w-[110px] h-[110px] sm:w-[210px] sm:h-[210px] relative flex items-center justify-center">
                            <img :src="mainBellImage" alt="Pencil Icon" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 sm:py-16 md:py-20 bg-white">
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-10 xl:gap-14">
                    <div class="flex items-center justify-center xl:justify-start flex-shrink-0" data-aos="fade-right">
                        <div class="w-[130px] h-[130px] sm:w-[200px] sm:h-[200px] relative flex items-center justify-center">
                            <img :src="mainDiscountImage" alt="Email Icon" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="text-center xl:text-left flex flex-col justify-center max-w-2xl" data-aos="fade-left">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-semibold mb-6 text-gray-800 leading-tight">
                            공동구매로 <span class="text-primary">{{ animatedDiscount }}%</span><span class="text-base text-gray-400"> 5인 기준</span><br/>
                            <span>할인 받으며 구매해요.</span>
                        </h2>
                        <p class="text-base sm:text-lg xl:text-xl text-gray-600 leading-relaxed">
                            혼자 구매할 때는 정가로 사야 했던 상품들이 이제 주변 사람들과 함께하면 대폭 할인된 가격으로 구매할 수 있어요. 5명이 함께 구매하면 평균 28%의 할인 혜택을 받을 수 있습니다. 가족, 친구, 동료, 이웃과 함께 원하는 상품의 공구를 만들어 같은 상품을 더 저렴하게 구매하는 스마트한 쇼핑을 경험해보세요.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 sm:py-16 md:py-20 bg-gray-50" ref="chatSection">
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-12 xl:gap-20">
                    <div class="text-center xl:text-center order-2 xl:order-1 flex flex-col justify-center max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-semibold mb-6 text-gray-800 leading-tight">
                            더 이상 채팅방에서<br/>
                            <span class="text-primary" v-html="typingText"></span><span v-if="isTyping" class="animate-pulse">|</span> 묻지 말고, <span class="underline-animation" :class="{ 'animate-underline': shouldAnimate }">🔗링크 공유만</span>
                        </h2>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 sm:py-20 md:py-28 bg-primary" ref="statsSection">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-medium mb-4 sm:mb-6 md:mb-8 text-white" data-aos="fade-right" data-aos-delay="200">
                        공구를 이용해<br class="block sm:hidden" /> 함께 더 저렴하게 물건을 구입해요✌
                    </h2>
                    <button 
                        @click="goToSignup"
                        class="bg-white text-primary px-6 sm:px-8 md:px-12 py-2.5 sm:py-3 md:py-4 rounded-full font-semibold text-base sm:text-lg md:text-xl transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-[0_8px_20px_rgba(255,255,255,0.3)] active:scale-[0.98] shadow-[0_4px_12px_rgba(255,255,255,0.2)]"
                        data-aos="fade-left"
                        data-aos-delay="600"
                    >
                        지금 시작하기
                    </button>
                </div>
            </div>
        </section>
    </main>
    <Footer />
</template>

<style>
@font-face {
    font-family: 'Gyeonggi_Title_Medium';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/2410-3@1.0/Title_Medium.woff') format('woff');
    font-weight: 500;
    font-style: normal;
}

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

.pin-enter-active,
.pin-leave-active {
    transition: all 0.5s ease;
}

.pin-enter-from,
.pin-leave-to {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.5);
}

.underline-animation {
    position: relative;
    display: inline-block;
}

.underline-animation::after {
    content: '';
    position: absolute;
    width: 0;
    height: 4px;
    bottom: -4px;
    left: 0;
    background-color: #2F9266;
    border-radius: 2px;
}

.animate-underline::after {
    animation: underline 1s ease-in-out forwards;
}

@keyframes underline {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}

[data-aos="fade-right"] {
    transform: translateY(20px);
    opacity: 0;
    transition-property: transform, opacity;
    transition-duration: 0.8s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

[data-aos="fade-right"].aos-animate {
    transform: translateY(0);
    opacity: 1;
}

[data-aos="fade-left"] {
    transform: translateY(20px);
    opacity: 0;
    transition-property: transform, opacity;
    transition-duration: 0.8s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

[data-aos="fade-left"].aos-animate {
    transform: translateY(0);
    opacity: 1;
}

.hover-scale {
    transition: transform 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.02);
}
</style>