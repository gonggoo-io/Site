<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)
const status = computed(() => page.props.flash?.status)

const form = useForm({})

function resendEmail() {
  form.post('/email/verification-notification', {
    onSuccess: () => {
      alert('인증 이메일이 재전송되었습니다.')
    }
  })
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          이메일 인증
        </h2>
        <div class="mt-4 text-sm text-gray-600">
          <p>
            회원가입을 완료하려면 이메일 인증이 필요합니다.
          </p>
          <p class="mt-2">
            <strong>{{ user?.email }}</strong>로 인증 링크를 보냈습니다.
          </p>
        </div>
      </div>

      <div v-if="status === 'verification-link-sent'" class="rounded-md bg-green-50 p-4">
        <div class="text-sm text-green-700">
          새로운 인증 링크가 이메일로 전송되었습니다.
        </div>
      </div>

      <div class="space-y-4">
        <div class="text-sm text-gray-600">
          <p>이메일을 받지 못하셨나요?</p>
          <p>스팸 폴더도 확인해보세요.</p>
        </div>

        <button
          @click="resendEmail"
          :disabled="form.processing"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
        >
          <span v-if="form.processing">전송 중...</span>
          <span v-else>인증 이메일 재전송</span>
        </button>

        <div class="text-center">
          <a
            href="/login"
            class="text-sm text-indigo-600 hover:text-indigo-500"
          >
            다른 계정으로 로그인
          </a>
        </div>
      </div>
    </div>
  </div>
</template>