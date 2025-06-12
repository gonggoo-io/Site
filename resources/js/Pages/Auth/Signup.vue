<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex items-center justify-center py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6">회원가입</h2>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">이름</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              placeholder="이름을 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              :class="{ 'border-red-500': form.errors.name }"
            />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
            <div class="flex space-x-2">
              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="이메일을 입력하세요"
                :disabled="isEmailVerified"
                class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266] disabled:bg-gray-100"
                :class="{ 'border-red-500': form.errors.email }"
              />
              <button
                type="button"
                @click="sendVerificationCode"
                :disabled="isCodeSending || isEmailVerified || !form.email"
                class="px-3 sm:px-4 py-2 bg-[#2F9266] text-white text-sm sm:text-base rounded-md hover:bg-[#247A4F] transition disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
              >
                <span v-if="isCodeSending">전송중...</span>
                <span v-else-if="isEmailVerified">인증완료</span>
                <span v-else>인증번호</span>
              </button>
            </div>
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
          </div>

          <div v-if="isCodeSent && !isEmailVerified">
            <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-1">
              인증번호
              <span v-if="countdown > 0" class="text-red-500 text-xs ml-2">
                ({{ formatTime(countdown) }} 남음)
              </span>
            </label>
            <div class="flex space-x-2">
              <input
                id="verification_code"
                v-model="verificationCode"
                type="text"
                maxlength="6"
                placeholder="6자리 인증번호"
                class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              />
              <button
                type="button"
                @click="verifyCode"
                :disabled="isCodeVerifying || !verificationCode"
                class="px-3 sm:px-4 py-2 bg-green-600 text-white text-sm sm:text-base rounded-md hover:bg-green-700 transition disabled:opacity-50 whitespace-nowrap"
              >
                <span v-if="isCodeVerifying">확인중...</span>
                <span v-else>확인</span>
              </button>
            </div>
          </div>

          <div v-if="isEmailVerified" class="rounded-md bg-green-50 p-3">
            <div class="text-sm text-green-700">
              ✓ 이메일 인증이 완료되었습니다!
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">비밀번호</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="비밀번호를 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              :class="{ 'border-red-500': form.errors.password }"
            />
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">비밀번호 확인</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              placeholder="비밀번호를 다시 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              :class="{ 'border-red-500': form.errors.password_confirmation }"
            />
            <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
          </div>

          <div>
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
            class="w-full bg-[#2F9266] text-white py-2 px-4 text-sm sm:text-base rounded-md hover:bg-[#247A4F] transition focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:ring-offset-2 disabled:opacity-50"
            :disabled="form.processing || !isEmailVerified"
          >
            <span v-if="form.processing">처리중...</span>
            <span v-else>회원가입</span>
          </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
          <Link href="/signin" class="text-[#2F9266] hover:underline ml-2">이미 계정이 있으신가요?</Link>
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
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  user: {
    type: Object,
    default: () => null
  }
});

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const verificationCode = ref('');
const isCodeSent = ref(false);
const isEmailVerified = ref(false);
const isCodeSending = ref(false);
const isCodeVerifying = ref(false);
const countdown = ref(0);
const countdownInterval = ref(null);
const privacyAgreement = ref(false);
const privacyError = ref(false);

async function sendVerificationCode() {
  if (!form.email) {
    alert('이메일을 입력해주세요.');
    return;
  }
  
  isCodeSending.value = true;
  
  try {
    await axios.post('/verification/send', {
      email: form.email
    });
    
    isCodeSent.value = true;
    startCountdown();
    alert('인증번호가 이메일로 전송되었습니다.');
  } catch (error) {
    alert(error.response?.data?.message || '인증번호 전송에 실패했습니다.');
  } finally {
    isCodeSending.value = false;
  }
}

async function verifyCode() {
  if (!verificationCode.value) {
    alert('인증번호를 입력해주세요.');
    return;
  }
  
  isCodeVerifying.value = true;
  
  try {
    await axios.post('/verification/code', {
      email: form.email,
      code: verificationCode.value
    });
    
    isEmailVerified.value = true;
    clearInterval(countdownInterval.value);
    alert('이메일 인증이 완료되었습니다!');
  } catch (error) {
    alert(error.response?.data?.message || '인증번호가 일치하지 않습니다.');
  } finally {
    isCodeVerifying.value = false;
  }
}

function startCountdown() {
  countdown.value = 300;
  countdownInterval.value = setInterval(() => {
    countdown.value--;
    if (countdown.value <= 0) {
      clearInterval(countdownInterval.value);
      isCodeSent.value = false;
    }
  }, 1000);
}

function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
}

function submit() {
  privacyError.value = !privacyAgreement.value;
  
  if (!isEmailVerified.value) {
    alert('이메일 인증을 완료해주세요.');
    return;
  }
  
  if (!privacyAgreement.value) {
    return;
  }
  
  form.post('/signup', {
    onSuccess: () => {
      alert('회원가입이 완료되었습니다!');
    }
  });
}
</script>

<style scoped>
@media (max-width: 640px) {
  .min-h-screen {
    min-height: 100vh;
  }
}
</style>