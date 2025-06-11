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
const animatedDiscount = ref(0);
const animatedPurchases = ref(0);
const mapSection = ref(null);
const activePin = ref(null);
const pins = [
    { id: 1, location: '서울', x: 50, y: 30 },
    { id: 2, location: '부산', x: 60, y: 80 },
    { id: 3, location: '인천', x: 45, y: 35 },
    { id: 4, location: '대구', x: 55, y: 65 },
    { id: 5, location: '광주', x: 40, y: 70 },
    { id: 6, location: '대전', x: 48, y: 55 },
    { id: 7, location: '울산', x: 65, y: 75 },
    { id: 8, location: '세종', x: 45, y: 50 },
    { id: 9, location: '경기', x: 48, y: 35 },
    { id: 10, location: '강원', x: 55, y: 25 }
];
let pinInterval = null;
const statsSection = ref(null);
const hasAnimated = ref(false);
const typingText = ref('');
const isTyping = ref(false);

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
    const text = "같이 살래?";
    let currentIndex = 0;
    isTyping.value = true;
    typingText.value = '';

    const type = () => {
        if (currentIndex < text.length) {
            typingText.value += text[currentIndex];
            currentIndex++;
            setTimeout(type, 150);
        } else {
            isTyping.value = false;
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

    let currentPinIndex = 0;
    pinInterval = setInterval(() => {
        activePin.value = pins[currentPinIndex];
        currentPinIndex = (currentPinIndex + 1) % pins.length;
    }, 1000);

    // Setup Intersection Observer for stats section
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated.value) {
                startPurchasesAnimation();
                hasAnimated.value = true;
            }
        });
    }, {
        threshold: 0.5
    });

    if (statsSection.value) {
        observer.observe(statsSection.value);
    }

    startTypingAnimation();
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
    if (pinInterval) {
        clearInterval(pinInterval);
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
                    <div class="text-center xl:text-left order-2 xl:order-1 flex flex-col   justify-center max-w-2xl">
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
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-10 xl:gap-14">
                    <div class="flex items-center justify-center xl:justify-start flex-shrink-0">
                        <div class="w-[130px] h-[130px] sm:w-[200px] sm:h-[200px] relative flex items-center justify-center">
                            <img src="/public/images/discount.png" alt="Email Icon" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="text-center xl:text-left flex flex-col justify-center max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-bold mb-6 text-gray-800 leading-tight">
                                공동구매로 <span class="text-[#2F9266]">{{ animatedDiscount }}%</span><span class="text-base text-gray-400"> 5인 기준</span><br/>
                            <span>할인 받으며 구매해요.</span>
                        </h2>
                        <p class="text-base sm:text-lg xl:text-xl text-gray-600 leading-relaxed">
                            혼자 구매할 때는 정가로 사야 했던 상품들이 이제 주변 사람들과 함께하면 대폭 할인된 가격으로 구매할 수 있어요. 5명이 함께 구매하면 평균 28%의 할인 혜택을 받을 수 있습니다. 가족, 친구, 동료, 이웃과 함께 원하는 상품의 공구를 만들어 같은 상품을 더 저렴하게 구매하는 스마트한 쇼핑을 경험해보세요.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
            
            <div class="w-full max-w-[1400px] mx-auto px-6 sm:px-8 lg:px-12">
                <div class="flex flex-col xl:flex-row items-center justify-center gap-12 xl:gap-20">
                    <div class="text-center xl:text-center order-2 xl:order-1 flex flex-col justify-center max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl xl:text-4xl font-semibold mb-6 text-gray-800 leading-tight">
                            더 이상 채팅방에서<br/>
                            <span class="text-[#2F9266]">{{ typingText }}</span><span v-if="isTyping" class="animate-pulse">|</span> 묻지 말고, <span class="underline-animation">링크 공유만</span>
                        </h2>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-16 sm:py-24 md:py-32 bg-[#2F9266]" ref="statsSection">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl sm:text-3xl font-medium mb-2 text-white">지금까지 {{ animatedPurchases }}+개의 공구가 이루어졌고, 사용자의 만족도는 92.7% 입니다.</h2>
                    <h2 class="text-3xl sm:text-3xl font-medium mb-6 sm:mb-8 text-white">공구를 이용해 함께 더 저렴하게 물건을 구입해요.</h2>
                    <button 
                        @click="goToSignup"
                        class="bg-white text-[#2F9266] px-8 sm:px-12 py-3 sm:py-4 rounded-full font-bold text-lg sm:text-xl transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-[0_8px_20px_rgba(255,255,255,0.3)] active:scale-[0.98] shadow-[0_4px_12px_rgba(255,255,255,0.2)]"
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
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: #2F9266;
    animation: underline 1s ease-in-out forwards;
    animation-delay: 1.5s; /* 타이핑 애니메이션이 끝난 후 시작 */
}

@keyframes underline {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}
</style>