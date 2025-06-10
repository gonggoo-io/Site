<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex items-center justify-center py-8">
      <div class="w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-2">로그인</h2>
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="이메일을 입력하세요"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              :class="{ 'border-red-500': form.errors.email }"
            />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
          </div>

          <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="비밀번호를 입력하세요"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              :class="{ 'border-red-500': form.errors.password }"
            />
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
          </div>

          <div class="mb-6">
            <label class="flex items-center">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="w-4 h-4 text-[#2F9266] border-gray-300 rounded focus:ring-[#2F9266] mr-2"
              />
              <span class="text-sm text-gray-600">로그인 유지</span>
            </label>
          </div>

          <button
            type="submit"
            class="w-full bg-[#2F9266] text-white py-2 rounded-md hover:bg-[#247A4F] transition disabled:opacity-50"
            :disabled="form.processing"
          >
            <span v-if="form.processing">로그인 중...</span>
            <span v-else>로그인</span>
          </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          <Link href="/signup" class="text-[#2F9266] hover:underline ml-2">아직 회원가입 하지 않았다면?</Link>
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

const props = defineProps({
  user: {
    type: Object,
    default: () => null
  }
});

const form = useForm({
  email: '',
  password: '',
  remember: true
});

function submit() {
  form.post('/signin', {
    onSuccess: () => {
    }
  });
}
</script>