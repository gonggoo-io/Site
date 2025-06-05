<template>
    <div>
      <h1>글 등록</h1>
      <form @submit.prevent="submit">
        <input v-model="form.item_name" placeholder="아이템명" required />
        <input v-model="form.link" placeholder="링크" type="url" required />
        <input v-model="form.participant_count" placeholder="모집 인원" type="number" required />
        <input v-model="form.total_price" placeholder="총금액" type="number" required />
        <button type="submit">등록</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  const form = ref({
    item_name: '',
    link: '',
    participant_count: '',
    total_price: ''
  })
  
  const submit = async () => {
    try {
      await axios.post('/insert', form.value)
      alert('등록 완료!')
    } catch (e) {
      alert('등록 실패')
    }
  }
  </script>
  