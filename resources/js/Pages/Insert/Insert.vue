<template>
  <div class="min-h-screen bg-white flex flex-col">
    <Header />
    
    <main class="flex-grow flex items-center justify-center py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold text-center mb-4 sm:mb-6">공구 등록</h2>

        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">카테고리</label>
            <select
              id="category"
              v-model="form.category"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              required
            >
              <option value="">카테고리를 선택하세요</option>
              <option value="식품">식품</option>
              <option value="생활용품">생활용품</option>
              <option value="화장품">화장품</option>
              <option value="주방용품">주방용품</option>
              <option value="문구/사무용품">문구/사무용품</option>
              <option value="운동/피트니스용품">운동/피트니스용품</option>
              <option value="기타">기타</option>
            </select>
          </div>

          <div>
            <label for="item" class="block text-sm font-medium text-gray-700 mb-1">아이템명</label>
            <input
              id="item"
              v-model="form.item"
              type="text"
              placeholder="아이템명을 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              required
            />
          </div>

          <div>
            <label for="link" class="block text-sm font-medium text-gray-700 mb-1">링크</label>
            <input
              id="link"
              v-model="form.link"
              type="text"
              placeholder="https://"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266]"
              required
              @input="handleLinkInput"
            />
          </div>

          <div>
            <label for="count" class="block text-sm font-medium text-gray-700 mb-1">모집 인원</label>
            <input
              id="count"
              v-model="form.count"
              type="number"
              placeholder="모집 인원을 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
              required
            />
          </div>

          <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">총금액</label>
            <input
              id="price"
              v-model="form.price"
              type="number"
              placeholder="총금액을 입력하세요"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-md focus:outline-none focus:ring-2 focus:ring-[#2F9266] [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
              required
            />
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
  category: '',
  item: '',
  link: '',
  count: '',
  price: ''
})

const handleLinkInput = (event) => {
  let value = event.target.value;
  value = value.replace(/^https?:\/\//, '');
  form.value.link = value;
}

const submit = async () => {
  try {
    const formData = {
      ...form.value,
      link: `https://${form.value.link}`
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
  