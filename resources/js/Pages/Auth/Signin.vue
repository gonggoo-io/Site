<template>
  <div class="min-h-screen bg-white">
    <Header />
    
    <div class="min-h-[calc(100vh-128px)] flex items-center justify-center">

      <div class="w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-2">로그인</h2>
        <p class="text-center text-gray-600 mb-6">
          공구를 시작해보아요.
        </p>

        <form @submit.prevent="handleLogin">
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
            <input
              id="email"
              v-model="email"
              type="email"
              placeholder="이메일을 입력하세요"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
            />
            <p v-if="emailError" class="text-red-500 text-sm mt-1">이메일을 입력해주세요.</p>
          </div>

          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
            <input
              id="password"
              v-model="password"
              type="password"
              placeholder="비밀번호를 입력하세요"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
            />
            <p v-if="passwordError" class="text-red-500 text-sm mt-1">비밀번호를 입력해주세요.</p>
          </div>

          <button
            type="submit"
            class="w-full bg-[#2F9266] text-white py-2 rounded-md hover:bg-[#247A4F] transition"
          >
            로그인
          </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          <a href="#" class="text-[#2F9266] hover:underline ml-2">아직 회원가입 하지 않았다면?</a>
        </p>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script>
import Header from '../components/Header.vue';
import Footer from '../components/Footer.vue';

export default {
  name: 'LoginPage',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      email: '',
      password: '',
      emailError: false,
      passwordError: false
    };
  },
  methods: {
    handleLogin() {
      this.emailError = !this.email.trim();
      this.passwordError = !this.password.trim();

      if (!this.emailError && !this.passwordError) {
        console.log('로그인 시도:', this.email, this.password);
      }
    }
  }
};
</script>
