<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: ''
})

function submit() {
  form.post('/login', {
    onSuccess: () => alert('로그인 성공')
  })
}
</script>

<template>
  <div class="max-w-md mx-auto mt-20">
    <h1 class="text-2xl font-bold mb-6">로그인</h1>
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label for="email">이메일</label>
        <input id="email" v-model="form.email" type="email" class="w-full border" />
        <div v-if="form.errors.email" class="text-red-500">{{ form.errors.email }}</div>
      </div>
      <div>
        <label for="password">비밀번호</label>
        <input id="password" v-model="form.password" type="password" class="w-full border" />
        <div v-if="form.errors.password" class="text-red-500">{{ form.errors.password }}</div>
      </div>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">로그인</button>
    </form>
  </div>
</template>
