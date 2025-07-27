<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex items-center justify-center py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-8 nexon-gothic flex justify-center items-center mt-12">
          gonggoo.
        </h2>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6 mb-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="이메일을 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
              required
            />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="비밀번호를 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
              required
            />
          </div>

          <div class="flex items-center">
            <label class="flex items-center">
              <input
                type="checkbox"
                v-model="form.remember"
                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
              />
              <span class="ml-2 text-sm text-gray-600">로그인 상태 유지</span>
            </label>
          </div>

          <button
            type="submit"
            class="w-full bg-primary text-white py-3 px-4 text-sm sm:text-base rounded-md hover:bg-[#247A4F] transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
            :disabled="form.processing"
          >
            <span v-if="form.processing">로그인</span>
            <span v-else>로그인</span>
          </button>
        </form>

        <div class="relative flex items-center justify-center mb-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative bg-white px-4 text-sm text-gray-500">또는</div>
        </div>

        <div class="mb-6">
          <button 
            @click="redirectToKakao"
            type="button"
            class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-medium py-3 px-4 rounded-md transition flex items-center justify-center space-x-2"
            :disabled="isKakaoLoading"
          >
            <svg v-if="!isKakaoLoading" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 3c5.799 0 10.5 3.664 10.5 8.185 0 4.52-4.701 8.184-10.5 8.184a13.5 13.5 0 0 1-1.727-.11L7.5 21l.955-2.83C6.464 17.04 1.5 14.174 1.5 11.185 1.5 6.665 6.201 3 12 3z"/>
            </svg>
            <div v-if="isKakaoLoading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-black"></div>
            <span>{{ isKakaoLoading ? '카카오로 시작하기' : '카카오로 시작하기' }}</span>
          </button>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6">
          <Link href="/signup" class="inline-block px-3 py-2 rounded-md hover:bg-gray-100 transition-colors">
            계정이 없으신가요? <span class="text-primary ml-2">회원가입</span>
          </Link>
        </p>

        <div v-if="message" :class="['mt-4', 'p-3', 'rounded-md', messageType === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700']">
          {{ message }}
        </div>

        <div v-if="errors.kakao" class="mt-4 p-3 rounded-md bg-red-50 text-red-700">
          {{ errors.kakao }}
        </div>
        
        <div v-if="Object.keys(errors).length > 0 && !errors.kakao" class="mt-4 p-3 rounded-md bg-red-50 text-red-700">
          <div v-for="(error, key) in errors" :key="key">
            {{ Array.isArray(error) ? error[0] : error }}
          </div>
        </div>
      </div>
    </main>

    <div class="mt-auto">
      <p class="text-center text-sm text-gray-500 py-4 mb-2">© 2025 gonggoo. All rights reserved.</p>
    </div>
  </div>
</template>

<script setup>
import Header from '../components/Header.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import logoImage from '/public/images/logo.svg';

const props = defineProps({
  errors: {
    type: Object,
    default: () => ({})
  }
});

const page = usePage();
const errors = computed(() => page.props.errors || {});

const form = useForm({
  email: '',
  password: '',
  remember: false
});

const message = ref("");
const messageType = ref("");
const isKakaoLoading = ref(false);

function showMessage(msg, type = "success") {
  message.value = msg;
  messageType.value = type;
  setTimeout(() => {
    message.value = "";
    messageType.value = "";
  }, 4000);
}

const submit = () => {
  form.post('/signin', {
    onSuccess: () => {
      showMessage('로그인되었습니다!', 'success');
    },
    onError: () => {
      showMessage('로그인에 실패했습니다. 이메일 또는 비밀번호를 확인하세요.', 'error');
    }
  });
};

const redirectToKakao = () => {
  try {
    isKakaoLoading.value = true;
    console.log('카카오 로그인 시도 중...');
    
    window.location.replace('/auth/kakao');
  } catch (error) {
    console.error('카카오 로그인 오류:', error);
    isKakaoLoading.value = false;
    showMessage('카카오 로그인 중 오류가 발생했습니다.', 'error');
  }
};
</script>

<style scoped>
@media (max-width: 640px) {
  .min-h-screen {
    min-height: 100vh;
  }
}
</style>