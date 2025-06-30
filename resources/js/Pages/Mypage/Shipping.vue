<template>
  <Header />
  <Container>
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <Sidebar active="shipping" />
        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
          <div class="text-black font-semibold text-3xl mb-1">
            🚚 배송 중인 거래
            <span class="text-lg text-gray-700 font-medium"> · {{ dateRange }}</span>
          </div>
          <div class="text-md text-gray-500 font-medium">※ 배송이 완료되면 거래가 완료로 이동합니다.</div>
          
          <div v-if="isLoadingInserts || isLoadingBuys" class="mt-12 flex justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
          </div>

          <div v-else-if="shippingItems.length === 0" class="mt-12 text-center text-gray-500">
            <p class="text-lg">배송 중인 거래가 없습니다.</p>
          </div>

          <template v-else>
            <div v-for="(group, date) in groupedShippingItems" :key="date" class="mt-12">
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
                    {{ insert.description }} · <img src="/public/images/dashboard-users.svg" alt="users" class="w-3 h-3 inline mr-1" />{{ getActiveBuysCount(insert) }}/{{ insert.people_count || 10 }}
                  </div>
                  
                  <!-- 배송 정보 표시 -->
                  <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center text-blue-700">
                      <span class="text-sm font-medium">🚚 배송중</span>
                      <span class="ml-2 text-xs text-blue-600">
                        {{ insert.courier }} - {{ insert.tracking_number }}
                      </span>
                    </div>
                    <div v-if="insert.bank && insert.account_number" class="mt-2 text-xs text-gray-600">
                      <div><strong>입금 정보:</strong> {{ insert.bank }} {{ insert.account_number }}</div>
                    </div>
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
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import Container from '../components/Container.vue'
import Sidebar from '../components/Sidebar.vue'

const inserts = ref([])
const buys = ref([])
const isLoadingInserts = ref(true)
const isLoadingBuys = ref(true)

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
    
    if (response.ok) {
      const data = await response.json()
      inserts.value = data.inserts || []
    } else {
      console.error('Failed to fetch inserts:', response.status, response.statusText)
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
    
    if (response.ok) {
      const data = await response.json()
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

const getActiveBuysCount = (insert) => {
  if (!insert || !insert.buys) return 0
  return insert.buys.filter(buy => buy.cancelled_at === null).length
}

const isShipping = (insert) => {
  return insert.tracking_number && insert.courier
}

const shippingItems = computed(() => {
  const ownedInsertIds = new Set(inserts.value.map(insert => insert.id))
  const filteredBuys = buys.value.filter(buy => !ownedInsertIds.has(buy.insert.id))
  const allItems = [...inserts.value, ...filteredBuys]
  
  // 운송장번호가 입력된 항목만 필터링
  return allItems.filter(item => isShipping(item))
})

const groupedShippingItems = computed(() => {
  const groups = {}
  const ownedInsertIds = new Set(inserts.value.map(insert => insert.id))
  
  inserts.value.forEach(insert => {
    if (isShipping(insert)) {
      const date = new Date(insert.purchased_at || insert.created_at).toISOString().split('T')[0]
      if (!groups[date]) {
        groups[date] = []
      }
      groups[date].push({
        ...insert,
        type: 'insert'
      })
    }
  })
  
  buys.value.forEach(buy => {
    if (!ownedInsertIds.has(buy.insert.id) && isShipping(buy.insert)) {
      const date = new Date(buy.insert.purchased_at || buy.created_at).toISOString().split('T')[0]
      if (!groups[date]) {
        groups[date] = []
      }
      groups[date].push({
        ...buy.insert,
        type: 'buy',
        buy_id: buy.id
      })
    }
  })
  
  return groups
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return `${date.getFullYear()}.${String(date.getMonth() + 1).padStart(2, '0')}.${String(date.getDate()).padStart(2, '0')}`
}

const dateRange = computed(() => {
  if (shippingItems.value.length === 0) return ''
  
  const dates = shippingItems.value.map(item => new Date(item.purchased_at || item.created_at))
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