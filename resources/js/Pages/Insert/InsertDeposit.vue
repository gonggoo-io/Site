<template>
    <div class="min-h-screen bg-white flex flex-col overflow-y-auto">
      <Header />
      <Container>
        <main class="flex-grow flex flex-col md:flex-row items-center justify-between pt-32 transition-colors duration-300">
          <div class="w-full md:w-[50%] flex items-center justify-center mb-12 md:mb-40">
            <transition name="slide-fade">
              <div class="flex flex-col items-center" key="question">
                <img src="/public/images/insert-deposit.png" alt="section choice" class="w-60 hidden md:block mb-7 mt-20" />
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 animate-fadein text-center">
                  물품 정보 > 세부 정보 ><span class="text-[#2F9266]">입금 정보</span>
                </h1>
                <div class="w-full text-center te   xt-gray-500 mt-1 mb-[-2] text-base md:text-lg">
                  공구가 완료되면 송금하기 위해 계좌 정보가 필요해요.
                </div>
              </div>
            </transition>
          </div>
          <div class="w-full md:w-[50%] flex flex-col items-start">
            <div class="w-full max-w-md mx-auto scrollable-area overflow-y-auto" style="max-height: calc(100vh - 200px);">
              <div class="sticky top-0 z-10 bg-white pt-4 pb-2">
                <p class="px-4 text-2xl text-black font-semibold">입금 계좌 정보를 입력해주세요.</p>
                <p class="px-4 text-base text-gray-500 mb-6">입력을 완료했으면 '다음' 버튼을 클릭해주세요.</p>
              </div>
              <form @submit.prevent="goToNext" class="px-4 space-y-6">
                <div class="form-group">
                  <label for="bank" class="block text-sm font-semibold text-gray-700 mb-2">
                    통장 은행 <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="bank"
                    v-model="form.bank"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                    required
                  >
                    <option value="" disabled>은행을 선택하세요</option>
                    <option value="토스뱅크">토스뱅크</option>
                    <option value="카카오뱅크">카카오뱅크</option>
                    <option value="국민은행">국민은행</option>
                    <option value="신한은행">신한은행</option>
                    <option value="우리은행">우리은행</option>
                    <option value="하나은행">하나은행</option>
                    <option value="농협은행">농협은행</option>
                    <option value="기업은행">기업은행</option>
                    <option value="SC제일은행">SC제일은행</option>
                    <option value="씨티은행">씨티은행</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="accountNumber" class="block text-sm font-semibold text-gray-700 mb-2">
                    계좌번호 <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="accountNumber"
                    v-model="form.accountNumber"
                    type="text"
                    placeholder="예: 12345678901234"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white"
                    required
                  />
                </div>
                <div class="pt-4 pb-6">
                  <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#2F9266] to-[#34A373] text-white py-3 px-6 rounded-xl font-semibold text-base shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    :disabled="!isFormValid"
                  >
                    <span class="flex items-center justify-center gap-2">
                      완료
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </main>
      </Container>
      <div class="mt-auto">
        <p class="text-center text-sm text-gray-500 py-4 mb-2">© 2025 gonggoo. All rights reserved.</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import Header from '../components/Header.vue';
  import Container from '../components/Container.vue';
  import { router } from '@inertiajs/vue3';
  
  const form = ref({
    bank: '',
    accountNumber: ''
  });
  
  const isFormValid = computed(() => {
    return form.value.bank && form.value.accountNumber.trim().length > 0;
  });
  
  const goToNext = () => {
    if (isFormValid.value) {
      router.visit('/dashboard');
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
  
  .scrollable-area::-webkit-scrollbar {
    display: none;
  }
  
  .form-group {
    animation: slideInUp 0.6s ease forwards;
  }
  
  .form-group:nth-child(1) { animation-delay: 0.1s; }
  .form-group:nth-child(2) { animation-delay: 0.2s; }
  
  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  
  </style>