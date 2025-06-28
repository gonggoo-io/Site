<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex items-center justify-center py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6 nexon-gothic flex justify-center items-center mt-12">
          <img :src="logoImage" alt="gonggoo logo" class="h-6 w-6 mr-4 mt-1" />
          gonggoo
        </h2>
        <p class="text-center text-sm sm:text-base text-gray-700 -mt-2 sm:-mt-4 mb-6">공구에 방문하신걸 환영해요👋</p>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="이메일을 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
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
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              required
            />
          </div>

          <div class="flex items-center">
            <label class="flex items-center">
              <input
                type="checkbox"
                v-model="form.remember"
                class="w-4 h-4 text-[#2F9266] border-gray-300 rounded focus:ring-[#2F9266]"
              />
              <span class="ml-2 text-sm text-gray-600">로그인 상태 유지</span>
            </label>
          </div>

          <button
            type="submit"
            class="w-full bg-[#2F9266] text-white py-2 px-4 text-sm sm:text-base rounded-md hover:bg-[#247A4F] transition focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:ring-offset-2"
            :disabled="form.processing"
          >
            <span v-if="form.processing">로그인 중...</span>
            <span v-else>로그인</span>
          </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          계정이 없으신가요?
          <Link href="/signup" class="text-[#2F9266] hover:underline ml-2">회원가입</Link>
        </p>

        <div v-if="message" :class="['mt-4', 'p-3', 'rounded-md', messageType === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700']">
          {{ message }}
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
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import logoImage from '/public/images/logo.svg';

const form = useForm({
  email: '',
  password: '',
  remember: false
});

const message = ref("");
const messageType = ref("");

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
</script>

<style scoped>
@media (max-width: 640px) {
  .min-h-screen {
    min-height: 100vh;
  }
}
</style>