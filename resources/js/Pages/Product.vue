<template>
    <div class="min-h-screen flex flex-col">
      <Header />
      <main class="flex-grow">
        <Container>
          <div class="mt-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="bg-gray-100 h-[400px] rounded-2xl overflow-hidden">
              </div>
              <div class="space-y-6">
                <div>
                  <h1 class="text-3xl font-bold text-gray-900">석부장 최애 탕비실 사무실 간식 박스과자세트 16p</h1>
                  <div class="flex items-center gap-2 mt-4">
                    <p class="text-2xl font-semibold text-gray-900">1인 14,000원</p>
                    <span class="text-gray-400">·</span>
                    <p class="text-2xl font-semibold text-gray-900">총 74,000원</p>
                  </div>
                </div>
  
                <div class="flex items-center text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mr-2">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                  </svg>
                  <span>1/12 · 3일 후 마감</span>
                </div>
                <div class="border-t border-gray-200 pt-6">
                  <h2 class="text-xl font-semibold mb-4">상품 설명</h2>
                  <p class="text-gray-600">
                    사무실에서 즐기는 다양한 과자 세트입니다. 16가지의 다양한 과자가 포함되어 있어 
                    모두가 좋아하는 맛을 찾을 수 있습니다. 탕비실이나 사무실에서 공유하기 좋은 
                    구성으로 준비되어 있습니다.
                  </p>
                </div>
  
                <div class="border-t border-gray-200 pt-6">
                  <h2 class="text-xl font-semibold mb-4">상품 링크</h2>
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <a href="https://www.coupang.com/vp/products/123456789" target="_blank" class="text-gray-600 hover:underline break-all">
                      https://www.coupang.com/vp/products/123456789
                    </a>
                  </div>
                </div>
  
                <div class="border-t border-gray-200 pt-6">
                  <h2 class="text-xl font-semibold mb-4">공구 물품 배송 위치</h2>
                  <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                      <div 
                        id="kakao-map" 
                        class="w-full h-64 bg-white"
                        style="min-height: 256px;"
                      ></div>
                      <div class="p-4">
                        <div class="text-gray-700">
                          <span class="font-medium text-lg">부산광역시 부산진구 중앙대로 623</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
  
                <button class="w-full bg-[#2F9266] text-white py-4 rounded-xl font-semibold hover:bg-[#267a54] transition-colors">
                  참여하기
                </button>
              </div>
            </div>
          </div>
        </Container>
      </main>
  
      <Footer class="mt-12" />
    </div>
  </template>
  
  <script setup>
  import { onMounted, onUnmounted } from 'vue'
  import Container from './components/Container.vue'
  import Header from './components/Header.vue'
  import Footer from './components/Footer.vue'
  
  let map = null
  let marker = null
  
  const initKakaoMap = () => {
    if (typeof kakao === 'undefined') {
      console.error('카카오 맵 API가 로드 실패..')
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
  
    const infowindow = new kakao.maps.InfoWindow({
      content: `
        <div class="p-2 text-center">
          <div class="font-semibold text-gray-800">부산광역시 부산진구 중앙대로 623</div>
          <div class="text-sm text-gray-600">배송 받을 위치</div>
        </div>
      `
    })
  
    infowindow.open(map, marker)
    
    setTimeout(() => {
      if (map) {
        map.relayout()
      }
    }, 100)
    
    kakao.maps.event.addListener(marker, 'click', () => {
      const kakaoMapUrl = `https://map.kakao.com/link/to/부산진구 중앙대로 623,${position.getLat()},${position.getLng()}`
      window.open(kakaoMapUrl, '_blank')
    })
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
  
  onMounted(async () => {
    try {
      await loadKakaoMapScript()
      initKakaoMap()
    } catch (error) {
      console.error(error)
    }
  })
  
  onUnmounted(() => {
    if (map) {
      map = null
    }
    if (marker) {
      marker = null
    }
  })
  </script>