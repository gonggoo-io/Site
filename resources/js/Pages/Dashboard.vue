<template>
    <div class="min-h-screen flex flex-col">
      <Header />
  
      <main class="flex-grow">
        <Container>
          <h1 class="text-3xl sm:text-5xl font-bold mt-32">siniseong님 근처에서</h1>
          <h1 class="text-3xl sm:text-5xl font-bold mt-2 whitespace-nowrap">새로운 공구가 시작되었어요📦</h1>
          <div class="mt-7 flex items-center justify-between">
            <div class="w-32 border-b-8 border-gray-800"></div>
            <div class="relative" ref="sortRef">
              <button 
                @click="toggleSort" 
                class="button-base"
              >
                <span>{{ selectedSort }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
              </button>
              <div v-if="showSort" class="dropdown-menu">
                <a 
                  v-for="option in sortOptions" 
                  :key="option.value"
                  href="#" 
                  @click.prevent="selectSort(option.label)"
                  class="dropdown-item"
                >
                  {{ option.label }}
                </a>
              </div>
            </div>
          </div>
          <div>
          </div>
          <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 gap-y-8">
            <div v-for="n in 10" :key="n">
              <div class="bg-gray-100 h-[180px] rounded-2xl overflow-hidden">
              </div>
              <div class="text-left text-xl font-semibold text-[#333333] mt-2">
                  <span>14,000원</span>
              </div>
              <div class="text-left text-base text-gray-500">
                  <span>석부장 최애 탕비실 사무실 간식 박스과자세트 16p</span>
              </div>
              <div class="text-left text-sm mt-1 text-gray-500 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 mr-1">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                  </svg>
                  1/12 · 3일 후 마감
              </div>
            </div>
          </div>

          <div class="text-center mt-10 mb-10 sm:hidden">
            <a href="#" @click.prevent="scrollToTop" class="text-gray-500 hover:underline">▲ 상단으로 이동 또는 맨 위로 (TOP)</a>
          </div>
        </Container>
      </main>
  
      <Footer class="mt-12" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  import Container from '../Pages/components/Container.vue'
  import Header from '../Pages/components/Header.vue'
  import Footer from '../Pages/components/Footer.vue'
  
  const showCategory = ref(false)
  const showSort = ref(false)
  const categoryRef = ref(null)
  const sortRef = ref(null)
  
  const selectedCategory = ref('전체')
  const selectedSort = ref('최신순')
  const searchQuery = ref('')
  
  const selectCategory = (category) => {
    selectedCategory.value = category
    showCategory.value = false
  }
  
  const sortOptions = ref([
    { label: '최신순', value: 'latest' },
    { label: '낮은 가격순', value: 'price_asc' },
    { label: '높은 가격순', value: 'price_desc' }
  ])
  
  const selectSort = (sort) => {
    selectedSort.value = sort
    showSort.value = false
  }
  
  const handleClickOutside = (event) => {
    if (sortRef.value && !sortRef.value.contains(event.target)) {
      showSort.value = false
    }
  }
  
  onMounted(() => {
    document.addEventListener('click', handleClickOutside)
  })
  
  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
  })
  
  const toggleCategory = () => {
    showCategory.value = !showCategory.value
    showSort.value = false
  }
  
  const toggleSort = () => {
    showSort.value = !showSort.value
  }

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
  </script>
  
  <style scoped>
  .input-base {
    @apply w-full h-12 pl-12 pr-4 border-2 border-gray-200 rounded-xl bg-gray-50/50 focus:outline-none focus:border-[#2F9266] focus:bg-white hover:border-[#2F9266] transition-all duration-200 [&::-webkit-search-cancel-button]:cursor-pointer shadow-sm;
  }
  
  .button-base {
    @apply flex items-center gap-2 px-5 h-10 border border-gray-200 rounded-lg bg-white transition-all duration-200 shadow-sm;
  }
  
  .sort-button {
    @apply relative px-4 h-10 rounded-lg text-gray-600 transition-all duration-200;
  }
  
  .active-sort {
    @apply text-white;
  }
  
  .active-sort::before {
    content: '';
    @apply absolute inset-0 bg-[#2F9266] rounded-lg shadow-sm;
    z-index: 0;
  }
  
  .dropdown-menu {
    @apply absolute z-10 w-56 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden;
  }
  
  .dropdown-item {
    @apply block px-5 py-3 text-sm text-gray-700 transition-colors duration-200;
  }
  </style>
  