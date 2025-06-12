<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex items-center justify-center py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6">로그인</h2>

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

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input
                type="checkbox"
                v-model="form.remember"
                class="w-4 h-4 text-[#2F9266] border-gray-300 rounded focus:ring-[#2F9266]"
              />
              <span class="ml-2 text-sm text-gray-600">로그인 상태 유지</span>
            </label>
            <Link href="/forgot-password" class="text-sm text-[#2F9266] hover:underline">
              비밀번호를 잊으셨나요?
            </Link>
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
      </div>
    </main>

    <div class="mt-auto">
      <Footer />
    </div>
  </div>
</template>

<script setup>
import Header from '../components/Header.vue';
import Footer from '../components/Footer.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false
});

const submit = () => {
  form.post('/signin', {
    onSuccess: () => {
      alert('로그인되었습니다!');
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