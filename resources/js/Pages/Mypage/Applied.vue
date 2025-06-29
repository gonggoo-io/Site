<template>
  <Header />
  <Container>
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <Sidebar active="applied" />
        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
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
                @click="goToContent(insert.id)"
                class="bg-white rounded-xl p-6 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200/60 shadow-sm transition-all duration-200 w-full cursor-pointer"
              >
                <div class="w-full">
                  <div class="font-semibold mb-1 text-xl text-gray-800">{{ insert.title || '제목 없음' }}</div>
                  <div v-if="insert.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ insert.description }} · <img src="/public/images/dashboard-users.svg" alt="users" class="w-3 h-3 inline mr-1" />{{ getActiveBuysCount(insert) }}/{{ insert.max_participants || 10 }}
                  </div>
                  <div class="flex space-x-3 mt-4">
                    <button 
                      @click.stop="goToContent(insert.id)"
                      class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm"
                    >
                      상세보기
                    </button>
                    <button 
                      @click.stop="cancelInsert(insert.id)"
                      class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-sm"
                    >
                      취소하기
                    </button>
                  </div>
                </div>
              </section>
            </div>
          </template>
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

const activeTab = ref('inserts')
const inserts = ref([])
const isLoadingInserts = ref(true)
const isCancelling = ref(false)

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

const goToContent = (insertId) => {
  router.visit(`/content/${insertId}`)
}

const cancelInsert = async (insertId) => {
  if (!insertId) return
  
  if (!confirm('정말로 이 공구를 취소하시겠습니까?')) return
  
  try {
    const response = await fetch(`/insert/${insertId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })

    if (response.ok) {
      window.location.reload()
    } else {
      const data = await response.json()
      alert(data.message || '공구 취소에 실패했습니다.')
    }
  } catch (error) {
    console.error('Error cancelling insert:', error)
    alert('공구 취소 중 오류가 발생했습니다.')
  }
}

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

const totalInsertsCount = computed(() => inserts.value.length)

const insertsDateRange = computed(() => {
  if (inserts.value.length === 0) return ''
  
  const dates = inserts.value.map(insert => new Date(insert.created_at))
  const minDate = new Date(Math.min(...dates))
  const maxDate = new Date(Math.max(...dates))
  
  return `${minDate.getFullYear()}. ${String(minDate.getMonth() + 1).padStart(2, '0')}.${String(minDate.getDate()).padStart(2, '0')} ~ ${maxDate.getFullYear()}. ${String(maxDate.getMonth() + 1).padStart(2, '0')}.${String(maxDate.getDate()).padStart(2, '0')}`
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return `${date.getFullYear()}.${String(date.getMonth() + 1).padStart(2, '0')}.${String(date.getDate()).padStart(2, '0')}`}

const formatPrice = (price) => {
  return new Intl.NumberFormat('ko-KR').format(price)
}

const getActiveBuysCount = (insert) => {
  if (!insert || !insert.buys) return 0
  return insert.buys.filter(buy => buy.cancelled_at === null).length
}

onMounted(() => {
  fetchInserts()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 

