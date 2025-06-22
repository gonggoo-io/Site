<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex justify-center pt-20 sm:pt-32 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6">공구 등록</h2>

        <form @submit.prevent="fetchMetadata" class="space-y-4 sm:space-y-6">
          <div>
            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">링크</label>
            <div class="flex gap-2">
              <input
                id="link"
                v-model="form.link"
                type="text"
                placeholder="https://"
                class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
                required
              />
              <button
                type="submit"
                :disabled="isLoading"
                class="px-4 py-2 bg-[#2F9266] text-white rounded-md hover:bg-[#247A4F] transition focus:outline-none focus:ring-2 focus:ring-[#2F9266] focus:ring-offset-2 disabled:opacity-50"
              >
                <div v-if="isLoading" class="flex items-center">
                  <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                </div>
                <span v-else>확인</span>
              </button>
            </div>
          </div>

          <div v-if="ogData.title || ogData.description || ogData.image" class="space-y-4">
            <div v-if="ogData.title">
              <label for="ogTitle" class="block text-sm font-medium text-gray-700">제목</label>
              <input id="ogTitle" v-model="ogData.title" type="text" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]" />
            </div>
            <div v-if="ogData.description">
              <label for="ogDescription" class="block text-sm font-medium text-gray-700">설명</label>
              <textarea id="ogDescription" v-model="ogData.description" rows="3" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"></textarea>
            </div>
            <div v-if="ogData.image">
              <label for="ogImage" class="block text-sm font-medium text-gray-700">이미지 URL</label>
              <input id="ogImage" v-model="ogData.image" type="text" class="mt-1 w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]" />
            </div>
          </div>

          <button
            v-if="ogData.title || ogData.description || ogData.image"
            type="button"
            @click="submit"
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
import { ref, computed } from 'vue'
import axios from 'axios'

const form = ref({
  link: ''
})

const fullLink = computed(() => {
  if (!form.value.link) return ''
  if (form.value.link.startsWith('http://') || form.value.link.startsWith('https://')) {
    return form.value.link
  }
  return `https://${form.value.link}`
})

const ogData = ref({
  title: '',
  description: '',
  image: ''
})

const isLoading = ref(false)

const fetchMetadata = async () => {
  if (!form.value.link) return;
  
  isLoading.value = true;
  try {
    const response = await axios.post('/api/meta', {
      url: fullLink.value
    });
    
    const metaData = response.data.data || response.data;
    
    ogData.value = {
      title: metaData.title || '',
      description: metaData.description || '',
      image: metaData.image || ''
    };
    
    console.log('OG Image URL:', metaData.image);
  } catch (error) {
    console.error('메타데이터 가져오기 실패:', error.response ? error.response.data : error.message);
    alert('링크 정보를 가져오는데 실패했습니다. 개발자 콘솔을 확인해주세요.');
  } finally {
    isLoading.value = false;
  }
}

const submit = async () => {
  try {
    const formData = {
      link: fullLink.value,
      title: ogData.value.title,
      description: ogData.value.description,
      image: ogData.value.image
    }
    await axios.post('/insert', formData)
    alert('등록이 완료되었습니다.')
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