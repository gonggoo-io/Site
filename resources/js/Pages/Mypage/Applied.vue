<template>
  <Header />
  <Container>
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <Sidebar active="applied" />
        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
          <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg">
            <button 
              @click="activeTab = 'buys'"
              :class="[
                'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200',
                activeTab === 'buys' 
                  ? 'bg-white text-primary shadow-sm' 
                  : 'text-gray-600 hover:text-gray-800'
              ]"
            >
              참여한 공구
            </button>
            <button 
              @click="activeTab = 'inserts'"
              :class="[
                'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-200',
                activeTab === 'inserts' 
                  ? 'bg-white text-primary shadow-sm' 
                  : 'text-gray-600 hover:text-gray-800'
              ]"
            >
              등록한 공구
            </button>
          </div>

          <div v-if="activeTab === 'buys'">
            <div class="text-black font-semibold text-3xl mb-1">
              📫 총 {{ totalBuysCount }}건
              <span class="text-lg text-gray-700 font-medium"> · {{ buysDateRange }}</span>
            </div>
            <div class="text-md text-gray-500 font-medium">※ 참여를 취소하면 취소한 공구에 다시 참여할 수 없어요.</div>

            <div v-if="isLoadingBuys" class="mt-12 flex justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>

            <div v-else-if="groupedBuys.length === 0" class="mt-12 text-center text-gray-500">
              <p class="text-lg">참여한 공구가 없습니다.</p>
            </div>

            <template v-else>
              <div v-for="(group, date) in groupedBuys" :key="date" class="mt-12">
                <div class="text-black font-semibold text-2xl mb-2 pb-2 border-b border-gray-200">{{ formatDate(date) }}</div>
                <section 
                  v-for="buy in group" 
                  :key="buy.id" 
                  class="bg-white rounded-xl p-6 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200/60 shadow-sm transition-all duration-200 w-full"
                >
                  <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-[3px] mr-4 flex-shrink-0 flex items-center justify-center">
                    <div class="w-8 h-8 bg-blue-400 rounded-[2px]"></div>
                  </div>
                  <div class="w-full">
                    <div class="font-semibold mb-1 text-xl text-gray-800">{{ buy.insert?.title || '제목 없음' }}</div>
                    <div class="font-normal text-xs sm:text-sm text-gray-500 flex items-center whitespace-normal">
                      {{ formatPrice(buy.insert?.price || 0) }}원
                      <span class="mx-2 text-gray-300 hidden sm:inline">·</span>
                      <img src="/public/images/dashboard-users.svg" alt="users" class="size-4 mr-1" />
                      <span class="text-primary font-semibold text-sm sm:text-base">
                        {{ getActiveBuysCount(buy.insert) }}/{{ buy.insert?.people_count || 0 }}
                      </span>
                    </div>
                    <button 
                      @click="cancelBuy(buy.insert?.id)"
                      :disabled="isCancelling || isCancelDisabled(buy.insert)"
                      class="block sm:hidden w-full mt-4 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium text-sm flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      {{ isCancelling ? '취소 중...' : '공구 취소' }}
                    </button>
                  </div>
                  <button 
                    @click="cancelBuy(buy.insert?.id)"
                    :disabled="isCancelling || isCancelDisabled(buy.insert)"
                    class="hidden sm:block ml-4 p-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium text-sm whitespace-nowrap flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </section>
              </div>
            </template>
          </div>

          <div v-if="activeTab === 'inserts'">
            <div class="text-black font-semibold text-3xl mb-1">
              📝 총 {{ totalInsertsCount }}건
              <span class="text-lg text-gray-700 font-medium"> · {{ insertsDateRange }}</span>
            </div>
            <div class="text-md text-gray-500 font-medium">※ 등록한 공구의 진행 상황을 확인하세요.</div>
            
            <div v-if="isLoadingInserts" class="mt-12 flex justify-center">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>

            <div v-else-if="groupedInserts.length === 0" class="mt-12 text-center text-gray-500">
              <p class="text-lg">등록한 공구가 없습니다.</p>
            </div>

            <template v-else>
              <div v-for="(group, date) in groupedInserts" :key="date" class="mt-12">
                <div class="text-black font-semibold text-2xl mb-2 pb-2 border-b border-gray-200">{{ formatDate(date) }}</div>
                <section 
                  v-for="insert in group" 
                  :key="insert.id" 
                  class="bg-white rounded-xl p-6 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200/60 shadow-sm transition-all duration-200 w-full"
                >
                  <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-[3px] mr-4 flex-shrink-0 flex items-center justify-center">
                    <div class="w-8 h-8 bg-green-400 rounded-[2px]"></div>
                  </div>
                  <div class="w-full">
                    <div class="font-semibold mb-1 text-xl text-gray-800">{{ insert.title || '제목 없음' }}</div>
                    <div class="font-normal text-xs sm:text-sm text-gray-500 flex items-center whitespace-normal">
                      {{ formatPrice(insert.price || 0) }}원
                      <span class="mx-2 text-gray-300 hidden sm:inline">·</span>
                      <img src="/public/images/dashboard-users.svg" alt="users" class="size-4 mr-1" />
                      <span class="text-primary font-semibold text-sm sm:text-base">
                        {{ getActiveBuysCount(insert) }}/{{ insert.people_count || 0 }}
                      </span>
                    </div>
                    <button 
                      @click="goToContent(insert.id)"
                      class="block sm:hidden w-full mt-4 px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm flex items-center justify-center"
                    >
                      상세보기
                    </button>
                  </div>
                  <button 
                    @click="goToContent(insert.id)"
                    class="hidden sm:block ml-4 p-2.5 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm whitespace-nowrap flex-shrink-0"
                  >
                    상세보기
                  </button>
                </section>
              </div>
            </template>
          </div>
        </main>
      </div>
      <Footer />
    </div>
  </Container>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import Container from '../components/Container.vue'
import Sidebar from '../components/Sidebar.vue'

const activeTab = ref('buys')
const buys = ref([])
const inserts = ref([])
const isLoadingBuys = ref(true)
const isLoadingInserts = ref(true)
const isCancelling = ref(false)
const fetchBuys = async () => {
  try {
    isLoadingBuys.value = true
    const response = await fetch('/debug/my-buys', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      credentials: 'same-origin'
    })
    
    console.log('Buys response status:', response.status)
    console.log('Buys response headers:', response.headers)
    
    if (response.ok) {
      const data = await response.json()
      console.log('Buys data:', data)
      buys.value = data.buys || []
    } else {
      console.error('Failed to fetch buys:', response.status, response.statusText)
      const errorText = await response.text()
      console.error('Error response:', errorText)
    }
  } catch (error) {
    console.error('Error fetching buys:', error)
  } finally {
    isLoadingBuys.value = false
  }
}

const fetchInserts = async () => {
  try {
    isLoadingInserts.value = true
    const response = await fetch('/debug/my-inserts', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      credentials: 'same-origin'
    })
    
    console.log('Inserts response status:', response.status)
    console.log('Inserts response headers:', response.headers)
    
    if (response.ok) {
      const data = await response.json()
      console.log('Inserts data:', data)
      inserts.value = data.inserts || []
    } else {
      console.error('Failed to fetch inserts:', response.status, response.statusText)
      const errorText = await response.text()
      console.error('Error response:', errorText)
    }
  } catch (error) {
    console.error('Error fetching inserts:', error)
  } finally {
    isLoadingInserts.value = false
  }
}

const cancelBuy = async (insertId) => {
  if (!insertId || isCancelling.value) return
  
  if (!confirm('정말로 이 공구 참여를 취소하시겠습니까?')) return
  
  try {
    isCancelling.value = true
    const response = await fetch('/buy', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        insert_id: insertId
      })
    })

    const data = await response.json()
    
    if (response.ok) {
      window.location.reload()
    } else {
      alert(data.message || '공구 취소에 실패했습니다.')
    }
  } catch (error) {
    console.error('Error cancelling buy:', error)
    alert('공구 취소 중 오류가 발생했습니다.')
  } finally {
    isCancelling.value = false
  }
}
const goToContent = (insertId) => {
  router.visit(`/content/${insertId}`)
}
const groupedBuys = computed(() => {
  const groups = {}
  buys.value.forEach(buy => {
    const date = new Date(buy.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push(buy)
  })
  return groups
})

const groupedInserts = computed(() => {
  const groups = {}
  inserts.value.forEach(insert => {
    const date = new Date(insert.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push(insert)
  })
  return groups
})

const totalBuysCount = computed(() => buys.value.length)
const totalInsertsCount = computed(() => inserts.value.length)
const buysDateRange = computed(() => {
  if (buys.value.length === 0) return ''
  
  const dates = buys.value.map(buy => new Date(buy.created_at))
  const minDate = new Date(Math.min(...dates))
  const maxDate = new Date(Math.max(...dates))
  
  return `${minDate.getFullYear()}. ${String(minDate.getMonth() + 1).padStart(2, '0')}.${String(minDate.getDate()).padStart(2, '0')} ~ ${maxDate.getFullYear()}. ${String(maxDate.getMonth() + 1).padStart(2, '0')}.${String(maxDate.getDate()).padStart(2, '0')}`
})

const insertsDateRange = computed(() => {
  if (inserts.value.length === 0) return ''
  
  const dates = inserts.value.map(insert => new Date(insert.created_at))
  const minDate = new Date(Math.min(...dates))
  const maxDate = new Date(Math.max(...dates))
  
  return `${minDate.getFullYear()}. ${String(minDate.getMonth() + 1).padStart(2, '0')}.${String(minDate.getDate()).padStart(2, '0')} ~ ${maxDate.getFullYear()}. ${String(maxDate.getMonth() + 1).padStart(2, '0')}.${String(maxDate.getDate()).padStart(2, '0')}`
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return `${date.getFullYear()}.${String(date.getMonth() + 1).padStart(2, '0')}.${String(date.getDate()).padStart(2, '0')}`
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('ko-KR').format(price)
}

const getActiveBuysCount = (insert) => {
  if (!insert || !insert.buys) return 0
  return insert.buys.filter(buy => buy.cancelled_at === null).length
}

const isCancelDisabled = (insert) => {
  if (!insert) return true
  const activeCount = getActiveBuysCount(insert)
  return activeCount >= insert.people_count || activeCount >= insert.people_count - 1
}

watch(activeTab, (newTab) => {
  if (newTab === 'buys' && buys.value.length === 0) {
    fetchBuys()
  } else if (newTab === 'inserts' && inserts.value.length === 0) {
    fetchInserts()
  }
})

onMounted(() => {
  fetchBuys()
})
</script> 