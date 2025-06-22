<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex justify-center pt-20 sm:pt-32 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6">공구 수동 등록</h2>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          <div class="space-y-4">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">제목</label>
              <input id="title" v-model="form.title" type="text" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]" required />
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">설명</label>
              <textarea id="description" v-model="form.description" rows="3" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"></textarea>
            </div>
            <div>
              <label for="image" class="block text-sm font-medium text-gray-700">이미지 URL</label>
              <input id="image" v-model="form.image" type="text" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]" />
            </div>
            <div>
              <label for="link" class="block text-sm font-medium text-gray-700">링크</label>
              <input id="link" v-model="form.link" type="text" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]" required />
            </div>
          </div>

          <button
            type="submit"
            class="w-full bg-[#2F9266] text-white py-2 px-4 text-sm sm:text-base rounded-md hover:bg-[#247A4F] transition focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:ring-offset-2"
          >
            등록하기
          </button>
        </form>
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
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
  title: '',
  description: '',
  image: '',
  link: ''
})

const submit = async () => {
  try {
    await axios.post('/insert', form.value)
    alert('등록이 완료되었습니다.')
    form.value = { title: '', description: '', image: '', link: '' };
  } catch (e) {
    alert('헉... 등록에 실패했어요.')
  }
}
</script>

<style scoped>
@media (max-width: 640px) {
  .min-h-screen {
    min-height: 100vh;
  }
}
</style>