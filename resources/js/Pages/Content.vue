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
                    1인 <span class="text-[#2F9266] font-bold">{{ formatPrice(contentPrice) }}원</span>
                  </p>
                  <span class="text-gray-400">·</span>
                  <p class="text-2xl font-semibold text-gray-900">합계 {{ formatPrice(contentTotal) }}원</p>
                </div>
              </div>

              <div class="flex items-center text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="size-5 mr-2">
                  <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                </svg>
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
                <button class="w-1/2 bg-[#2F9266] text-white py-4 rounded-xl font-semibold hover:bg-[#267a54] transition-colors">
                  공구 참여하기
                </button>
                <button 
                  ref="copyButtonRef"
                  class="w-1/2 border-2 border-[#2F9266] bg-white text-[#2F9266] py-4 rounded-xl font-semibold hover:bg-[#f0f0f0] transition-colors"
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
import { usePage } from '@inertiajs/vue3'
import Container from './components/Container.vue'
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import OpenGraph from './components/OpenGraph.vue'
import ImageLenz from './components/ImageLenz.vue'

const page = usePage()
const insert = page.props.insert || {}

const contentTitle = computed(() => insert.title || '제목 없음')
const contentImage = computed(() => insert.image || 'https://via.placeholder.com/400x300.png?text=OG+Image')
const contentPrice = computed(() => insert.price || 0)
const contentTotal = computed(() => (insert.price || 0) * (insert.people_count || 1))
const contentDescription = computed(() => insert.description || '')
const contentLink = computed(() => insert.link || '')
const contentAddress = computed(() => insert.address || '')
const contentPeopleCount = computed(() => insert.people_count || 0)
const contentDeadline = computed(() => insert.deadline || '')
const contentBuys = computed(() => insert.buys ? insert.buys.length : 0)

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

let map = null
let marker = null
const copyButtonRef = ref(null)

const initKakaoMap = () => {
  if (typeof kakao === 'undefined') {
    return
  }
  const container = document.getElementById('kakao-map')
  if (!container) return
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
    script.src = `//dapi.kakao.com/v2/maps/sdk.js?appkey=${import.meta.env.VITE_KAKAO_MAP_KEY}&autoload=false`
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
  } catch (error) {
  }
})

onUnmounted(() => {
  if (map) map = null
})
</script>