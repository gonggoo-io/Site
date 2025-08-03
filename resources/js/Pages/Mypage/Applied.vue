<template>
  <Header />
  <div class="flex flex-col min-h-screen">
    <main class="flex-1">
      <Container>
        <div class="flex flex-col lg:flex-row flex-1 gap-10">
          <Sidebar active="applied" />
          <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
            <div class="mt-0">
              <div class="text-black font-semibold text-3xl pb-3">
                내가 등록한 공구 현황이에요.
              </div>
              <div v-if="!isLoadingInserts" class="text-gray-600 text-base mb-8 -mt-2">
                내가 등록한 공구는 {{ ownerItems.length }}건이에요
              </div>
              
              <div v-if="isLoadingInserts">
                <div v-for="n in 2" :key="`sk-owner-${n}`" class="mt-8 animate-pulse">
                  <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
                  <div class="bg-white rounded-xl p-6 mb-5 border border-gray-200/60 shadow-sm w-full">
                    <div class="h-6 bg-gray-200 rounded w-3/4 mb-3"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    <div class="flex space-x-3 mt-4">
                      <div class="h-10 w-28 bg-gray-200 rounded-lg"></div>
                    </div>
                  </div>
                </div>
              </div>
              <template v-else>
                <div v-if="ownerItems.length > 0">
                  <div v-for="(group, date) in groupedOwnerItems" :key="`owner-${date}`" class="mt-8">
                    <div class="text-black font-semibold text-xl mb-2 pb-2 border-b border-gray-200">{{ formatDate(date) }}</div>
                    <section 
                      v-for="insert in group" 
                      :key="`owner-${insert.id}`" 
                      @click.self="goToContent(insert.id)"
                      class="bg-white rounded-xl p-6 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200/60 shadow-sm transition-all duration-200 w-full cursor-pointer"
                    >
                      <div class="w-full">
                        <div class="font-semibold mb-1 text-xl text-gray-800 flex items-center">
                          {{ insert.title || '제목 없음' }}
                        </div>
                        <div v-if="insert.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
                          {{ insert.description }} · <img src="/public/images/dashboard-users.svg" alt="users" class="w-3 h-3 inline mr-1" />{{ getActiveBuysCount(insert) }}/{{ insert.people_count || 10 }}
                        </div>

                        <div v-if="showTrackingInput === insert.id" class="mb-4 p-4 bg-gray-50 rounded-lg">
                          <div class="text-sm font-medium text-gray-700 mb-2">운송장번호 입력</div>
                          <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <select v-model="selectedCourier" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full sm:w-auto" :disabled="isSubmittingTracking">
                              <option value="">택배사 선택</option>
                              <option value="CJ대한통운">CJ대한통운</option>
                              <option value="한진택배">한진택배</option>
                              <option value="우체국택배">우체국택배</option>
                              <option value="롯데택배">롯데택배</option>
                              <option value="로젠택배">로젠택배</option>
                              <option value="경동택배">경동택배</option>
                              <option value="일양로지스">일양로지스</option>
                              <option value="CU편의점택배">CU편의점택배</option>
                              <option value="GSPostbox">GSPostbox</option>
                              <option value="기타">기타</option>
                            </select>
                            <input 
                              v-model="trackingNumber"
                              type="text" 
                              placeholder="운송장번호를 입력하세요"
                              class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                              :disabled="isSubmittingTracking"
                            />
                            <button 
                              @click="submitTrackingNumber(insert.id)"
                              :disabled="!trackingNumber.trim() || !selectedCourier || isSubmittingTracking"
                              class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                              {{ isSubmittingTracking ? '처리중...' : '입력완료' }}
                            </button>
                            <button 
                              @click="cancelTrackingInput"
                              :disabled="isSubmittingTracking"
                              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all duration-200 font-medium text-sm"
                            >
                              취소
                            </button>
                          </div>
                        </div>
                        
                        <div class="flex space-x-3 mt-4">
                          <button 
                            v-if="getActiveBuysCount(insert) >= (insert.people_count || 10)"
                            @click.stop="handlePurchaseInput(insert.id)"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-[#247A4F] transition-all duration-200 font-medium text-sm"
                          >
                            운송장 입력
                          </button>
                          <button 
                            v-if="!(getActiveBuysCount(insert) >= (insert.people_count || 10))"
                            @click.stop="cancelInsert(insert.id)"
                            class="px-4 py-2 bg-red-500 text-white hover:bg-red-600 rounded-lg font-medium text-sm transition-all duration-200"
                          >
                            삭제하기
                          </button>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
              </template>
            </div>

            <div class="mt-16">
              <div class="text-black font-semibold text-3xl pb-3">
                내가 참여한 공구 현황이에요.
              </div>
              <div v-if="!isLoadingBuys" class="text-gray-600 text-base mb-8 -mt-2">
                대시보드에 있는 공동구매에 {{ userItems.length }}번 참여했어요
              </div>

              <div v-if="isLoadingBuys">
                <div v-for="n in 2" :key="`sk-user-${n}`" class="mt-8 animate-pulse">
                  <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
                  <div class="bg-white rounded-xl p-6 mb-5 border border-gray-200/60 shadow-sm w-full">
                    <div class="h-6 bg-gray-200 rounded w-3/4 mb-3"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    <div class="flex space-x-3 mt-4">
                      <div class="h-10 w-28 bg-gray-200 rounded-lg"></div>
                      <div class="h-10 w-28 bg-gray-200 rounded-lg"></div>
                    </div>
                  </div>
                </div>
              </div>
              <template v-else>
                <div v-if="userItems.length > 0">
                  <div v-for="(group, date) in groupedUserItems" :key="`user-${date}`" class="mt-8">
                    <div class="text-black font-semibold text-xl mb-2 pb-2 border-b border-gray-200">{{ formatDate(date) }}</div>
                    <section 
                      v-for="insert in group" 
                      :key="`user-${insert.id}`" 
                      @click.self="goToContent(insert.id)"
                      class="bg-white rounded-xl p-6 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200/60 shadow-sm transition-all duration-200 w-full cursor-pointer"
                    >
                      <div class="w-full">
                        <div class="font-semibold mb-1 text-xl text-gray-800 flex items-center">
                          {{ insert.title || '제목 없음' }}
                        </div>
                        <div v-if="insert.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
                          {{ insert.description }} · <img src="/public/images/dashboard-users.svg" alt="users" class="w-3 h-3 inline mr-1" />{{ getActiveBuysCount(insert) }}/{{ insert.people_count || 10 }}
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
                            class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-sm transition-all duration-200"
                          >
                            취소하기
                          </button>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
              </template>
            </div>
            
            <div v-if="!isLoadingInserts && !isLoadingBuys && allItems.length === 0" class="mt-12 text-center text-gray-500">
              <p class="text-lg">등록하거나 참여한 공구가 없습니다.</p>
            </div>
            
            <div class="pb-20 lg:pb-32"></div>
          </main>
        </div>
      </Container>
    </main>
    <Footer />
  </div>
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
const showTrackingInput = ref(null)
const trackingNumber = ref('')
const isSubmittingTracking = ref(false)
const selectedCourier = ref("")

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
      buys.value = data.all_buys || []
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
  const isBuy = !isOwner && buys.value.find(b => b.insert.id === insertId)
  
  if (!confirm(isOwner ? '정말로 이 공구를 삭제하시겠습니까?' : '정말로 이 공구를 취소하시겠습니까?')) return
  
  try {
    let response
    if (isOwner) {
      response = await fetch(`/insert/${insertId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
    } else if (isBuy) {
      response = await fetch(`/buy`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ insert_id: insertId })
      })
    } else {
      alert('삭제할 수 없는 공구입니다.')
      return
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
  showTrackingInput.value = insertId
  trackingNumber.value = ''
  selectedCourier.value = ''
}

const cancelTrackingInput = () => {
  showTrackingInput.value = null
  trackingNumber.value = ''
  selectedCourier.value = ''
}

const submitTrackingNumber = async (insertId) => {
  if (!trackingNumber.value.trim() || !selectedCourier.value) return
  
  try {
    isSubmittingTracking.value = true
    const response = await fetch(`/insert/${insertId}/tracking`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        tracking_number: trackingNumber.value.trim(),
        courier: selectedCourier.value
      })
    })
    
    if (response.ok) {
      alert('운송장번호가 성공적으로 입력되었습니다. 참여자들에게 알림이 전송되었습니다.')
      cancelTrackingInput()
      await fetchInserts()
      await fetchBuys()
    } else {
      const errorData = await response.json()
      alert(errorData.message || '운송장번호 입력에 실패했습니다.')
    }
  } catch (error) {
    alert('운송장번호 입력 중 오류가 발생했습니다.')
  } finally {
    isSubmittingTracking.value = false
  }
}

const groupedInserts = computed(() => {
  const groups = {}
  
  allItems.value.forEach(item => {
    if (!item || !item.created_at) return
    const date = new Date(item.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push(item)
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
  return insert.buys.filter(buy => buy && buy.cancelled_at === null).length
}

const allItems = computed(() => {
  const ownedInsertIds = new Set(inserts.value.filter(insert => insert && insert.id).map(insert => insert.id))
  const filteredBuys = buys.value.filter(buy => buy && buy.insert && buy.insert.id && !ownedInsertIds.has(buy.insert.id))
  
  const allInserts = inserts.value
    .filter(insert => insert && insert.id && !isShipping(insert))  // 배송중인 항목 제외
    .map(insert => ({
      ...insert,
      type: 'insert'
    }))
  
  const allBuys = filteredBuys
    .filter(buy => !isShipping(buy.insert))  // 배송중인 항목 제외
    .map(buy => ({
      ...buy.insert,
      type: 'buy',
      buy_id: buy.id
    }))
  
  return [...allInserts, ...allBuys]
})

const totalCount = computed(() => allItems.value.length)

const dateRange = computed(() => {
  if (allItems.value.length === 0) return ''
  
  const dates = allItems.value
    .filter(item => item && item.created_at)
    .map(item => new Date(item.created_at))
    
  if (dates.length === 0) return ''
  
  const minDate = new Date(Math.min(...dates))
  const maxDate = new Date(Math.max(...dates))
  
  return `${minDate.getFullYear()}. ${String(minDate.getMonth() + 1).padStart(2, '0')}.${String(minDate.getDate()).padStart(2, '0')} ~ ${maxDate.getFullYear()}. ${String(maxDate.getMonth() + 1).padStart(2, '0')}.${String(maxDate.getDate()).padStart(2, '0')}`
})

const isShipping = (insert) => {
  if (!insert || !insert.tracking_number || !insert.courier) return false
  return true
}

const ownerItems = computed(() => {
  return allItems.value.filter(item => item.owner)
})

const userItems = computed(() => {
  return allItems.value.filter(item => !item.owner)
})

const groupedOwnerItems = computed(() => {
  const groups = {}
  ownerItems.value.forEach(item => {
    if (!item || !item.created_at) return
    const date = new Date(item.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push(item)
  })
  return groups
})

const groupedUserItems = computed(() => {
  const groups = {}
  userItems.value.forEach(item => {
    if (!item || !item.created_at) return
    const date = new Date(item.created_at).toISOString().split('T')[0]
    if (!groups[date]) {
      groups[date] = []
    }
    groups[date].push(item)
  })
  return groups
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

