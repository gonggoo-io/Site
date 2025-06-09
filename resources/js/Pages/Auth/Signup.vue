<template>
  <div class="min-h-screen bg-white">
    <Header />
    
    <div class="min-h-[calc(100vh-128px)] flex items-center justify-center">

      <div class="w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-2">회원가입</h2>
        <p class="text-center text-gray-600 mb-6">
          공구로 회사 동료, 친구들과 더 저렴하게 구입해요.
        </p>

        <form @submit.prevent="handleSignup">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">이름</label>
            <input
              id="name"
              v-model="name"
              type="text"
              placeholder="이름을 입력하세요"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
            />
            <p v-if="nameError" class="text-red-500 text-sm mt-1">이름을 입력해주세요.</p>
          </div>

          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
            <div class="flex gap-2">
              <input
                id="email"
                v-model="email"
                type="email"
                placeholder="이메일을 입력하세요"
                class="flex-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              />
              <button
                type="button"
                class="px-4 py-2 bg-[#2F9266] text-white rounded-md hover:bg-[#247A4F] transition whitespace-nowrap"
              >
                인증
              </button>
            </div>
            <p v-if="emailError" class="text-red-500 text-sm mt-1">이메일을 입력해주세요.</p>
          </div>

          <div class="mb-4">
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

          <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">비밀번호 확인</label>
            <input
              id="password_confirmation"
              v-model="password_confirmation"
              type="password"
              placeholder="비밀번호를 다시 입력하세요"
              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
            />
            <p v-if="passwordConfirmationError" class="text-red-500 text-sm mt-1">비밀번호가 일치하지 않습니다.</p>
          </div>

          <div class="mb-6">
            <label class="flex items-center">
              <input
                type="checkbox"
                v-model="privacyAgreement"
                class="w-4 h-4 text-[#2F9266] border-gray-300 rounded focus:ring-[#2F9266]"
              />
              <span class="ml-2 text-sm text-gray-600">
                개인정보 수집 및 이용에 동의합니다.
                <Link href="/privacy" class="text-[#2F9266] hover:underline">자세히 보기</Link>
              </span>
            </label>
            <p v-if="privacyError" class="text-red-500 text-sm mt-1">개인정보 수집 및 이용에 동의해주세요.</p>
          </div>

          <button
            type="submit"
            class="w-full bg-[#2F9266] text-white py-2 rounded-md hover:bg-[#247A4F] transition"
          >
            회원가입
          </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          <Link href="/signin" class="text-[#2F9266] hover:underline ml-2">이미 계정이 있으신가요?</Link>
        </p>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script>
import Header from '../components/Header.vue';
import Footer from '../components/Footer.vue';
import { Link } from '@inertiajs/vue3';

export default {
  name: 'SignupPage',
  components: {
    Header,
    Footer,
    Link
  },
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      privacyAgreement: false,
      nameError: false,
      emailError: false,
      passwordError: false,
      passwordConfirmationError: false,
      privacyError: false
    };
  },
  methods: {
    handleSignup() {
      this.nameError = false;
      this.emailError = false;
      this.passwordError = false;
      this.passwordConfirmationError = false;
      this.privacyError = false;
      this.nameError = !this.name.trim();
      this.emailError = !this.email.trim();
      this.passwordError = !this.password.trim();
      this.passwordConfirmationError = this.password !== this.password_confirmation;
      this.privacyError = !this.privacyAgreement;

      if (!this.nameError && !this.emailError && !this.passwordError && 
          !this.passwordConfirmationError && !this.privacyError) {
        console.log('회원가입 시도:', {
          name: this.name,
          email: this.email,
          password: this.password
        });
      }
    }
  }
};
</script>
