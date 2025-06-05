<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const verificationCode = ref('')
const isCodeSent = ref(false)
const isEmailVerified = ref(false)
const isCodeSending = ref(false)
const isCodeVerifying = ref(false)
const countdown = ref(0)
const countdownInterval = ref(null)

async function sendVerificationCode() {
  if (!form.email) {
    alert('이메일을 입력해주세요.')
    return
  }
  
  isCodeSending.value = true
  
  try {
    await axios.post('/send-verification-code', {
      email: form.email
    })
    
    isCodeSent.value = true
    startCountdown()
    alert('인증번호가 이메일로 전송되었습니다.')
  } catch (error) {
    alert(error.response?.data?.message || '인증번호 전송에 실패했습니다.')
  } finally {
    isCodeSending.value = false
  }
}

async function verifyCode() {
  if (!verificationCode.value) {
    alert('인증번호를 입력해주세요.')
    return
  }
  
  isCodeVerifying.value = true
  
  try {
    await axios.post('/verify-code', {
      email: form.email,
      code: verificationCode.value
    })
    
    isEmailVerified.value = true
    clearInterval(countdownInterval.value)
    alert('이메일 인증이 완료되었습니다!')
  } catch (error) {
    alert(error.response?.data?.message || '인증번호가 일치하지 않습니다.')
  } finally {
    isCodeVerifying.value = false
  }
}

function startCountdown() {
  countdown.value = 300
  countdownInterval.value = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) {
      clearInterval(countdownInterval.value)
      isCodeSent.value = false
    }
  }, 1000)
}

function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
}

function submit() {
  if (!isEmailVerified.value) {
    alert('이메일 인증을 완료해주세요.')
    return
  }
  
  form.post('/signup', {
    onSuccess: () => {
      alert('회원가입이 완료되었습니다!')
    }
  })
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          회원가입
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <div class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">이름</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="이름을 입력하세요"
            />
          </div>
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">이메일</label>
            <div class="mt-1 flex space-x-2">
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                :disabled="isEmailVerified"
                class="flex-1 appearance-none relative block px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm disabled:bg-gray-100"
                placeholder="이메일을 입력하세요"
              />
              <button
                type="button"
                @click="sendVerificationCode"
                :disabled="isCodeSending || isEmailVerified || !form.email"
                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="isCodeSending">전송중...</span>
                <span v-else-if="isEmailVerified">인증완료</span>
                <span v-else>인증번호</span>
              </button>
            </div>
          </div>

          <div v-if="isCodeSent && !isEmailVerified" class="space-y-2">
            <label for="verification_code" class="block text-sm font-medium text-gray-700">
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
                class="flex-1 appearance-none relative block px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              />
              <button
                type="button"
                @click="verifyCode"
                :disabled="isCodeVerifying || !verificationCode"
                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
              >
                <span v-if="isCodeVerifying">확인중...</span>
                <span v-else>확인</span>
              </button>
            </div>
          </div>

          <div v-if="isEmailVerified" class="rounded-md bg-green-50 p-4">
            <div class="text-sm text-green-700">
              ✓ 이메일 인증이 완료되었습니다!
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">비밀번호</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="비밀번호를 입력하세요"
            />
          </div>
          
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">비밀번호 확인</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              required
              class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="비밀번호를 다시 입력하세요"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            :disabled="form.processing || !isEmailVerified"
          >
            회원가입
          </button>
        </div>
      </form>
    </div>
  </div>
</template>