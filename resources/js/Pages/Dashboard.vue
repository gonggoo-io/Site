<template>
    <div>
      <Header />
  
      <Container>
        <h1 class="text-5xl font-bold mt-32">근처에서 새로운 공구가</h1>
        <h1 class="text-5xl font-bold mt-2">시작되었어요📦</h1>
        <div class="mt-8">
          <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
              <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                  <img src="/public/images/search.svg" alt="search" class="w-6 h-6" />
                </span>
                <input type="search" class="input-base" placeholder="원하는 아이템명을 검색해보세요." />
              </div>
            </div>
  
            <div class="relative" ref="categoryRef">
              <button @click="toggleCategory" class="button-base">
                <img src="/public/images/category.svg" alt="category" class="w-5 h-5" />
                <span>{{ selectedCategory || '카테고리' }}</span>
                <img src="/public/images/chevron-down.svg" alt="chevron down" class="w-4 h-4" />
              </button>
              <div v-if="showCategory" class="dropdown-menu">
                <div class="py-1">
                  <a href="#" @click.prevent="selectCategory('전자제품')" class="dropdown-item">전자제품</a>
                  <a href="#" @click.prevent="selectCategory('의류')" class="dropdown-item">의류</a>
                  <a href="#" @click.prevent="selectCategory('식품')" class="dropdown-item">식품</a>
                  <a href="#" @click.prevent="selectCategory('가구')" class="dropdown-item">가구</a>
                  <a href="#" @click.prevent="selectCategory('생활용품')" class="dropdown-item">생활용품</a>
                </div>
              </div>
            </div>
  
            <div class="relative" ref="participantsRef">
              <button @click="toggleParticipants" class="button-base">
                <img src="/public/images/participants.svg" alt="participants" class="w-5 h-5" />
                <span>{{ selectedParticipants || '최대 인원' }}</span>
                <img src="/public/images/chevron-down.svg" alt="chevron down" class="w-4 h-4" />
              </button>
              <div v-if="showParticipants" class="dropdown-menu">
                <div class="py-1">
                  <a href="#" @click.prevent="selectParticipants('2명')" class="dropdown-item">2명</a>
                  <a href="#" @click.prevent="selectParticipants('4명')" class="dropdown-item">4명</a>
                  <a href="#" @click.prevent="selectParticipants('6명')" class="dropdown-item">6명</a>
                  <a href="#" @click.prevent="selectParticipants('8명')" class="dropdown-item">8명</a>
                  <a href="#" @click.prevent="selectParticipants('10명 이상')" class="dropdown-item">10명 이상</a>
                </div>
              </div>
            </div>
  
            <div class="relative" ref="sortRef">
              <button @click="toggleSort" class="button-base">
                <img src="/public/images/sort.svg" alt="sort" class="w-5 h-5" />
                <span>{{ selectedSort || '정렬' }}</span>
                <img src="/public/images/chevron-down.svg" alt="chevron down" class="w-4 h-4" />
              </button>
              <div v-if="showSort" class="dropdown-menu">
                <div class="py-1">
                  <a href="#" @click.prevent="selectSort('최신순')" class="dropdown-item">최신순</a>
                  <a href="#" @click.prevent="selectSort('모집률순')" class="dropdown-item">모집률순</a>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <div class="mt-12 grid grid-cols-5 gap-4">
          <div class="bg-gray-100 h-[180px] rounded-lg overflow-hidden" v-for="n in 5" :key="n">
          </div>
        </div>
      </Container>
  
      <Footer />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  import Container from '../Pages/components/Container.vue'
  import Header from '../Pages/components/Header.vue'
  import Footer from '../Pages/components/Footer.vue'
  
  const showCategory = ref(false)
  const showParticipants = ref(false)
  const showSort = ref(false)
  const categoryRef = ref(null)
  const participantsRef = ref(null)
  const sortRef = ref(null)
  
  const selectedCategory = ref('')
  const selectedParticipants = ref('')
  const selectedSort = ref('')
  
  const selectCategory = (category) => {
    selectedCategory.value = category
    showCategory.value = false
  }
  
  const selectParticipants = (participants) => {
    selectedParticipants.value = participants
    showParticipants.value = false
  }
  
  const selectSort = (sort) => {
    selectedSort.value = sort
    showSort.value = false
  }
  
  const handleClickOutside = (event) => {
    if (categoryRef.value && !categoryRef.value.contains(event.target)) {
      showCategory.value = false
    }
    if (participantsRef.value && !participantsRef.value.contains(event.target)) {
      showParticipants.value = false
    }
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
    showParticipants.value = false
    showSort.value = false
  }
  
  const toggleParticipants = () => {
    showParticipants.value = !showParticipants.value
    showCategory.value = false
    showSort.value = false
  }
  
  const toggleSort = () => {
    showSort.value = !showSort.value
    showCategory.value = false
    showParticipants.value = false
  }
  </script>
  
  <style scoped>
  .input-base {
    @apply w-full h-10 pl-10 pr-4 border border-gray-200 rounded-lg focus:outline-none focus:border-[#2F9266] hover:border-[#2F9266] transition-colors duration-200 [&::-webkit-search-cancel-button]:cursor-pointer;
  }
  
  .button-base {
    @apply flex items-center gap-2 px-4 h-10 border border-gray-200 rounded-lg hover:border-[#2F9266] transition-colors duration-200;
  }
  
  .dropdown-menu {
    @apply absolute z-10 w-48 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg;
  }
  
  .dropdown-item {
    @apply block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md mx-1;
  }
  </style>
  