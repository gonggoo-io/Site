<template>
  <Header />
  <Container>
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <Sidebar active="applied" />
        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
          <div class="text-black font-semibold text-3xl mb-1">
            📝 총 {{ totalCount }}건
            <span class="text-lg text-gray-700 font-medium"> · {{ dateRange }}</span>
          </div>
          <div class="text-md text-gray-500 font-medium">※ 등록한 공구와 참여한 공구의 진행 상황을 확인하세요.</div>
          
          <div v-if="isLoadingInserts || isLoadingBuys" class="mt-12 flex justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          </div>

          <div v-else-if="allItems.length === 0" class="mt-12 text-center text-gray-500">
            <p class="text-lg">등록하거나 참여한 공구가 없습니다.</p>
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
                  <div class="font-semibold mb-1 text-xl text-gray-800 flex items-center">
                    {{ insert.title || '제목 없음' }}
                    <span v-if="insert.owner" class="ml-2 px-2 py-1 text-primary text-sm font-semibold">
                      Owner
                    </span>
                    <span v-if="insert.type === 'buy'" class="ml-2 px-2 py-1 text-blue-500 text-sm font-semibold">
                      User
                    </span>
                  </div>
                  <div v-if="insert.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ insert.description }} · <img src="/public/images/dashboard-users.svg" alt="users" class="w-3 h-3 inline mr-1" />{{ getActiveBuysCount(insert) }}/{{ insert.max_participants || 10 }}
                  </div>
                  <div class="flex space-x-3 mt-4">
                    <button 
                      v-if="!insert.owner"
                      @click.stop="goToContent(insert.id)"
                      class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm"
                    >
                      상세보기
                    </button>
                    <button 
                      v-if="insert.owner && getActiveBuysCount(insert) < (insert.max_participants || 10)"
                      disabled
                      class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm cursor-not-allowed"
                    >
                      주문하기
                    </button>
                    <button 
                      v-if="insert.owner && getActiveBuysCount(insert) >= (insert.max_participants || 10)"
                      @click.stop="handlePurchaseInput(insert.id)"
                      class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm"
                    >
                      구매 입력
                    </button>
                    <button 
                      @click.stop="cancelInsert(insert.id)"
                      class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200"
                      :class="insert.owner ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    >
                      {{ insert.owner ? '삭제하기' : '취소하기' }}
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
const buys = ref([])
const isLoadingInserts = ref(true)
const isLoadingBuys = ref(true)
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
    
    if (response.ok) {
      const data = await response.json()
      console.log('Buys data:', data)
      buys.value = data.buys || []
    } else {
      console.error('Failed to fetch buys:', response.status, response.statusText)
    }
  } catch (error) {
    console.error('Error fetching buys:', error)
  } finally {
    isLoadingBuys.value = false
  }
}

const goToContent = (insertId) => {
  router.visit(`/content/${insertId}`)
}

const cancelInsert = async (insertId) => {
  if (!insertId) return
  
  const insert = inserts.value.find(i => i.id === insertId) || buys.value.find(b => b.insert.id === insertId)?.insert
  const isOwner = insert?.owner
  const isBuy = buys.value.find(b => b.insert.id === insertId)
  
  if (!confirm(isOwner ? '정말로 이 공구를 삭제하시겠습니까?' : '정말로 이 공구를 취소하시겠습니까?')) return
  
  try {
    let response
    if (isBuy) {
      response = await fetch(`/buy`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ insert_id: insertId })
      })
    } else {
      response = await fetch(`/insert/${insertId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
    }

    if (response.ok) {
      window.location.reload()
    } else {
      const data = await response.json()
      alert(data.message || (isOwner ? '공구 삭제에 실패했습니다.' : '공구 취소에 실패했습니다.'))
    }
  } catch (error) {
    console.error('Error cancelling insert:', error)
    alert(isOwner ? '공구 삭제 중 오류가 발생했습니다.' : '공구 취소 중 오류가 발생했습니다.')
  }
}

const handlePurchaseInput = (insertId) => {
  alert('구매 입력 기능은 준비 중입니다.')
}

const groupedInserts = computed(() => {
  const groups = {}
  
  inserts.value.forEach(insert => {
    const date = new Date(insert.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push({
      ...insert,
      type: 'insert'
    })
  })
  
  buys.value.forEach(buy => {
    const date = new Date(buy.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push({
      ...buy.insert,
      type: 'buy',
      buy_id: buy.id
    })
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

const allItems = computed(() => [...inserts.value, ...buys.value])

const totalCount = computed(() => allItems.value.length)

const dateRange = computed(() => {
  if (allItems.value.length === 0) return ''
  
  const dates = allItems.value.map(item => new Date(item.created_at))
  const minDate = new Date(Math.min(...dates))
  const maxDate = new Date(Math.max(...dates))
  
  return `${minDate.getFullYear()}. ${String(minDate.getMonth() + 1).padStart(2, '0')}.${String(minDate.getDate()).padStart(2, '0')} ~ ${maxDate.getFullYear()}. ${String(maxDate.getMonth() + 1).padStart(2, '0')}.${String(maxDate.getDate()).padStart(2, '0')}`
})

onMounted(() => {
  fetchInserts()
  fetchBuys()
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

