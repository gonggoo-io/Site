<template>
  <div class="min-h-screen flex flex-col">
    <Header />
    <main class="flex-grow">
      <Container>
        <div class="mt-32">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <ImageLenz 
              :image="insert.image"
              :alt="contentTitle"
            >
              <OpenGraph :image="contentImage" />
            </ImageLenz>

            <div class="space-y-6">
              <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ contentTitle }}</h1>
                <div class="flex items-center gap-2 mt-4">
                  <p class="text-2xl font-semibold text-gray-900">
                    1인 <span class="text-black">{{ contentPerPersonCount }}개</span> <span class="text-[#2F9266] font-bold">{{ formatPrice(contentPrice) }}원</span>
                  </p>
                  <span class="text-gray-400">·</span>
                  <p class="text-2xl font-semibold text-gray-900">합계 {{ formatPrice(contentTotal) }}원</p>
                </div>
              </div>

              <div class="flex items-center text-gray-600">
                <img :src="contentUsersIcon" alt="users" class="size-5 mr-2" />
                <span>{{ contentBuys }}/{{ contentPeopleCount }} · {{ formatDeadline(contentDeadline) }}</span>
              </div>

              <div class="border-t border-gray-200 pt-6">
                <h2 class="text-2xl font-semibold mb-4">물품 설명</h2>
                <p class="text-gray-600">{{ contentDescription }}</p>
              </div>

              <div class="border-t border-gray-200 pt-6">
                <h2 class="text-2xl font-semibold mb-4">구매 링크</h2>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                  <a :href="contentLink" target="_blank" class="text-gray-600 hover:underline break-all">
                    {{ contentLink }}
                  </a>
                </div>
              </div>

              <div class="border-t border-gray-200 pt-6">
                <h2 class="text-2xl font-semibold mb-1">물품 배송 위치</h2>
                <span class="block text-base font-medium mb-4 text-gray-500">마커를 클릭하면 카카오맵으로 이동해요.</span>
                <div class="space-y-4">
                  <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                    <div 
                      id="kakao-map"  
                      class="w-full h-64 bg-white"
                      style="min-height: 256px;"
                    ></div>
                    <div class="p-4">
                      <div class="text-gray-700">
                        <span class="font-medium text-lg">{{ contentAddress }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex gap-4">
                <button 
                  v-if="!isCreator && !isJoined && !isBanned"
                  class="w-1/2 bg-[#2F9266] text-white py-3 rounded-xl font-semibold hover:bg-[#267a54] transition-colors"
                  @click="joinGroupBuy"
                  :disabled="isLoading"
                >
                  {{ isLoading ? '잠시만 기다려주세요' : '공구 참여하기' }}
                </button>
                <button 
                  v-if="!isCreator && isJoined"
                  class="w-1/2 bg-red-500 text-white py-3 rounded-xl font-semibold hover:bg-red-600 transition-colors"
                  @click="cancelGroupBuy"
                  :disabled="isLoading || isCancelDisabled"
                >
                  {{ isLoading ? '잠시만 기다려주세요' : '공구 참여 취소' }}
                </button>
                <button
                  v-if="!isCreator && isBanned"
                  class="w-1/2 bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold cursor-not-allowed"
                  disabled
                >
                  참여 불가
                </button>
                <button 
                  v-if="isCreator"
                  class="w-1/2 bg-gray-200 text-gray-500 py-3  rounded-xl font-semibold cursor-not-allowed"
                  disabled
                >
                  내가 등록한 공구입니다
                </button>
                <button 
                  ref="copyButtonRef"
                  class="w-1/2 border-2 border-[#2F9266] bg-white text-[#2F9266] py-3 rounded-xl font-semibold hover:bg-[#f0f0f0] transition-colors"
                  @click="shareLink"
                >
                  친구에게 공구 공유하기
                </button>
              </div>
            </div>
          </div>
        </div>
      </Container>
    </main>

    <Footer class="mt-12"/>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import Container from './components/Container.vue'
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import OpenGraph from './components/OpenGraph.vue'
import ImageLenz from './components/ImageLenz.vue'
import contentUsersIcon from '/public/images/dashboard-users.svg';

const page = usePage()
const insert = page.props.insert || {}

const contentTitle = computed(() => insert.title || '제목 없음')
const contentImage = computed(() => insert.image || 'https://via.placeholder.com/400x300.png?text=OG+Image')
const contentPrice = computed(() => insert.price || 0)
const contentTotal = computed(() => (insert.price || 0) * (insert.people_count || 1) * (insert.per_person_count || 1))
const contentDescription = computed(() => insert.description || '')
const contentLink = computed(() => insert.link || '')
const contentAddress = computed(() => insert.address || '')
const contentPeopleCount = computed(() => insert.people_count || 0)
const contentDeadline = computed(() => insert.deadline || '')
const contentBuys = computed(() => {
  if (!insert.buys) return 0
  return insert.buys.filter(buy => buy.cancelled_at === null).length
})
const contentPerPersonCount = computed(() => insert.per_person_count || 0)
const isCreator = computed(() => insert.user_id === page.props.auth?.user?.id)

const isJoined = ref(false)
const isLoading = ref(false)
const isBanned = ref(false)
const isCancelDisabled = computed(() => {
  return contentBuys.value >= contentPeopleCount.value || contentBuys.value >= contentPeopleCount.value - 1
})

const formatPrice = (price) => new Intl.NumberFormat('ko-KR').format(price)
const formatDeadline = (deadline) => {
  if (!deadline) return '마감일 없음'
  const now = new Date()
  const deadlineDate = new Date(deadline)
  const diffTime = deadlineDate - now
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  if (diffDays < 0) return '마감됨'
  if (diffDays === 0) return '오늘 마감'
  if (diffDays === 1) return '내일 마감'
  return `${diffDays}일 후 마감`
}

// 공구 참여하기
const joinGroupBuy = async () => {
  if (isLoading.value) return
  
  isLoading.value = true
  try {
    const response = await fetch('/buy', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        insert_id: insert.id
      })
    })

    const data = await response.json()
    
    if (response.ok) {
      isJoined.value = true
      window.location.reload()
    } else {
      alert(data.message || '공구 참여에 실패했습니다.')
    }
  } catch (error) {
    console.error('Error joining group buy:', error)
    alert('공구 참여 중 오류가 발생했습니다.')
  } finally {
    isLoading.value = false
  }
}

const cancelGroupBuy = async () => {
  if (isLoading.value || isCancelDisabled.value) return
  
  if (!confirm('정말로 공구 참여를 취소하시겠습니까? 취소 후에는 다시 참여할 수 없습니다.')) {
    return
  }
  
  isLoading.value = true
  try {
    const response = await fetch('/buy', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        insert_id: insert.id
      })
    })

    const data = await response.json()
    
    if (response.ok) {
      isJoined.value = false
      window.location.reload()
    } else {
      alert(data.message || '공구 참여 취소에 실패했습니다.')
    }
  } catch (error) {
    console.error('Error canceling group buy:', error)
    alert('공구 참여 취소 중 오류가 발생했습니다.')
  } finally {
    isLoading.value = false
  }
}

const checkJoinStatus = async () => {
  try {
    const response = await fetch(`/buy/${insert.id}`)
    const data = await response.json()
    isJoined.value = data.joined
    if (!data.joined) {
      const banCheck = await fetch(`/buy/${insert.id}/bancheck`)
      const banData = await banCheck.json()
      isBanned.value = banData.banned
    }
  } catch (error) {
    console.error('Error checking join status:', error)
  }
}

let map = null
let marker = null
let geocoder = null
const copyButtonRef = ref(null)

const initKakaoMap = () => {
  if (typeof kakao === 'undefined') {
    return
  }
  const container = document.getElementById('kakao-map')
  if (!container) return

  if (contentAddress.value) {
    geocoder = new kakao.maps.services.Geocoder()
    
    geocoder.addressSearch(contentAddress.value, function(result, status) {
      if (status === kakao.maps.services.Status.OK) {
        const coords = new kakao.maps.LatLng(result[0].y, result[0].x)
        
        const options = {
          center: coords,
          level: 2
        }
        
        map = new kakao.maps.Map(container, options)
        marker = new kakao.maps.Marker({
          position: coords,
          map: map
        })

        kakao.maps.event.addListener(marker, 'click', () => {
          const kakaoMapUrl = `https://map.kakao.com/link/to/${contentAddress.value},${coords.getLat()},${coords.getLng()}`
          window.open(kakaoMapUrl, '_blank')
        })
      } else {
        const position = new kakao.maps.LatLng(35.1595454, 129.0477508)
        const options = {
          center: position,
          level: 2
        }
        map = new kakao.maps.Map(container, options)
        marker = new kakao.maps.Marker({
          position: position,
          map: map
        })
        kakao.maps.event.addListener(marker, 'click', () => {
          const kakaoMapUrl = `https://map.kakao.com/link/to/${contentAddress.value},${position.getLat()},${position.getLng()}`
          window.open(kakaoMapUrl, '_blank')
        })
      }
    })
  } else {
    const position = new kakao.maps.LatLng(35.1595454, 129.0477508)
    const options = {
      center: position,
      level: 2
    }
    map = new kakao.maps.Map(container, options)
    marker = new kakao.maps.Marker({
      position: position,
      map: map
    })
    kakao.maps.event.addListener(marker, 'click', () => {
      const kakaoMapUrl = `https://map.kakao.com/link/to/${contentAddress.value},${position.getLat()},${position.getLng()}`
      window.open(kakaoMapUrl, '_blank')
    })
  }

  setTimeout(() => {
    if (map) map.relayout()
  }, 100)
}

const loadKakaoMapScript = () => {
  return new Promise((resolve, reject) => {
    if (typeof kakao !== 'undefined') {
      resolve()
      return
    }

    const script = document.createElement('script')
    script.src = `//dapi.kakao.com/v2/maps/sdk.js?appkey=${import.meta.env.VITE_KAKAO_MAP_KEY}&libraries=services&autoload=false`
    script.onload = () => {
      kakao.maps.load(() => {
        resolve()
      })
    }
    script.onerror = reject
    document.head.appendChild(script)
  })
}

const shareLink = async () => {
  try {
    await navigator.share({
      title: '공구를 요청했어요.',
      text: `${contentTitle.value} 공구를 친구가 요청했어요.`,
      url: window.location.href,
    });
  } catch (err) {
  }
}

onMounted(async () => {
  try {
    await loadKakaoMapScript()
    initKakaoMap()
    await checkJoinStatus()
  } catch (error) {
  }
})

onUnmounted(() => {
  if (map) map = null
})
</script>