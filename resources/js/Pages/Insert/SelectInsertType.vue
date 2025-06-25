<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    <Container>
      <main class="flex-grow flex flex-col md:flex-row items-center justify-between pt-32 transition-colors duration-300">
        <div class="w-full md:w-[50%] flex items-center justify-center mb-12 md:mb-40">
          <transition name="slide-fade">
            <div class="flex flex-col items-center" key="question">
              <img src="/public/images/section-choice.svg" alt="section choice" class="w-80 mb-6" />
              <h1 class="text-2xl md:text-3xl font-bold text-gray-800 animate-fadein text-center">
                어떤 플랫폼에서<br class="md:hidden"/> 구매를 하시나요?
              </h1>
              <div class="w-full text-center text-gray-500 mt-1 mb-[-2] text-base md:text-lg">
              오직 링크만으로 내용을 작성해줘요.
              </div>
              <p class="px-4 text-sm text-gray-500">(쿠팡, G마켓, 옥션 제외)</p>
            </div>
          </transition>
        </div>
        <div class="w-full md:w-[50%] flex flex-col items-start">
          <div class="w-full max-w-md mx-auto overflow-y-auto" style="max-height: calc(100vh - 200px);">
            <p class="px-4 text-2xl text-black font-semibold pt-4">아래 항목 중 1개를 선택해주세요.</p>
            <p class="px-4 text-base text-gray-500">선택을 완료했으면 '다음' 버튼을 클릭해주세요.</p>
            <div class="flex flex-col gap-4 px-2 py-4">
              <div
                v-for="(platform, idx) in platforms"
                :key="platform.value"
                @click="selectPlatform(platform.value)"
                :class="[
                  'flex items-start gap-3 p-5 rounded-xl bg-white cursor-pointer border border-gray-100',
                  selectedPlatform === platform.value ? 'ring-2 ring-[#2F9266]' : '',
                ]"
              >
                <input
                  type="checkbox"
                  class="w-5 h-5 mt-1 accent-[#2F9266] cursor-pointer"
                  :checked="selectedPlatform === platform.value"
                  @change="selectPlatform(platform.value)"
                  @click.stop
                />
                <div class="flex flex-col">
                  <span class="text-base font-semibold text-gray-800">{{ platform.label }}</span>
                </div>
              </div>
            </div>
            <div class="px-2 pb-4">
              <button
                class="w-full mt-6 py-3 rounded-lg text-base font-semibold transition"
                :class="[
                  selectedPlatform
                    ? 'bg-[#2F9266] text-white cursor-pointer'
                    : 'bg-gray-200 text-gray-400 cursor-not-allowed',
                ]"
                :disabled="!selectedPlatform"
                @click="goToNext"
              >
                다음
              </button>
            </div>
          </div>
        </div>
      </main>
    </Container>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Header from '../components/Header.vue';
import Container from '../components/Container.vue';
import { router } from '@inertiajs/vue3';

const selectedPlatform = ref(null);

const selectPlatform = (value) => {
  selectedPlatform.value = value;
};

const platforms = [
  { value: 'coupang', label: '쿠팡' },
  { value: 'gmarket', label: 'G마켓' },
  { value: 'auction', label: '옥션' },
  { value: 'elevenst', label: '11번가' },
  { value: 'ali', label: '알리익스프레스' },
  { value: 'ssg', label: 'SSG닷컴' },
  { value: 'musinsa', label: '무신사' },
  { value: 'etc', label: '기타' },
];

const goToNext = () => {
  if (selectedPlatform.value) {
    router.visit('/insert');
  }
};
</script>

<style scoped>
.card-animate {
  animation: fadeInUp 0.4s ease forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

::-webkit-scrollbar {
  display: none;
}
</style>
