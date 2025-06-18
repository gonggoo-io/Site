<template>
    <div class="min-h-screen flex flex-col">
      <Header />
      <main class="flex-grow">
        <Container>
          <div class="mt-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="relative">
                <div class="bg-gray-100 h-[540px] rounded-2xl overflow-hidden">
                  <img 
                    v-if="ogImage" 
                    :src="ogImage" 
                    :alt="productTitle"
                    class="w-full h-full object-cover"
                    @error="handleImageError"
                  />
                  <div v-else-if="isLoadingImage" class="flex items-center justify-center h-full">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2F9266]"></div>
                  </div>
                  <div v-else class="flex items-center justify-center h-full text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                  </div>
                </div>
                <p class="text-base text-gray-500 mt-4 text-center">※ 구매 링크 미리보기 이미지입니다.</p>
              </div>
              <div class="space-y-6">
                <div>
                  <h1 class="text-3xl font-bold text-gray-900">석부장 최애 탕비실 사무실 간식 박스과자세트 16p</h1>
                  <div class="flex items-center gap-2 mt-4">
                    <p class="text-2xl font-semibold text-gray-900">1인 <span class="text-[#2F9266] font-bold">14,000원</span></p>
                    <span class="text-gray-400">·</span>
                    <p class="text-2xl font-semibold text-gray-900">합계 74,000원</p>
                  </div>
                </div>
  
                <div class="flex items-center text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 mr-2">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                  </svg>
                  <span>1/12 · 3일 후 마감</span>
                </div>
                <div class="border-t border-gray-200 pt-6">
                  <h2 class="text-2xl font-semibold mb-4">물품 설명</h2>
                  <p class="text-gray-600">
                    사무실에서 즐기는 다양한 과자 세트입니다. 16가지의 다양한 과자가 포함되어 있어 
                    모두가 좋아하는 맛을 찾을 수 있습니다. 탕비실이나 사무실에서 공유하기 좋은 
                    구성으로 준비되어 있습니다.
                  </p>
                </div>
  
                <div class="border-t border-gray-200 pt-6">
                  <h2 class="text-2xl font-semibold mb-4">구매 링크</h2>
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <a href="https://item.gmarket.co.kr/Item?goodsCode=3026275566" target="_blank" class="text-gray-600 hover:underline break-all">
                        https://item.gmarket.co.kr/Item?goodsCode=3026275566
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
                          <span class="font-medium text-lg">부산광역시 부산진구 중앙대로 623</span>
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
  import { onMounted, onUnmounted, ref } from 'vue'
  import Container from './components/Container.vue'
  import Header from './components/Header.vue'
  import Footer from './components/Footer.vue'
  
  let map = null
  let marker = null
  
  const copyButtonRef = ref(null);
  const ogImage = ref(null);
  const isLoadingImage = ref(false);
  
  const productTitle = '석부장 최애 탕비실 사무실 간식 박스과자세트 16p';
  const purchaseUrl = 'https://item.gmarket.co.kr/Item?goodsCode=3026275566';
  
  const fetchOGImage = async () => {
    isLoadingImage.value = true;
    try {
      let htmlContent = null;
      
      try {
        const proxyUrl = `https://api.allorigins.win/raw?url=${encodeURIComponent(purchaseUrl)}`;
        const response = await fetch(proxyUrl);
        if (response.ok) {
          htmlContent = await response.text();
        }
      } catch (error) {
        console.log('첫 번째 프록시 실패, 두 번째 시도...');
      }
      
      if (!htmlContent) {
        try {
          const proxyUrl2 = `https://cors-anywhere.herokuapp.com/${purchaseUrl}`;
          const response2 = await fetch(proxyUrl2);
          if (response2.ok) {
            htmlContent = await response2.text();
          }
        } catch (error) {
          console.log('두 번째 프록시도 실패');
        }
      }
      
      if (htmlContent) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlContent, 'text/html');
        let imageUrl = null;
        
        const ogImageMeta = doc.querySelector('meta[property="og:image"]');
        if (ogImageMeta && ogImageMeta.getAttribute('content')) {
          imageUrl = ogImageMeta.getAttribute('content');
          console.log('Found og:image:', imageUrl);
        }
        
        if (!imageUrl) {
          const twitterImageMeta = doc.querySelector('meta[property="twitter:image"]');
          if (twitterImageMeta && twitterImageMeta.getAttribute('content')) {
            imageUrl = twitterImageMeta.getAttribute('content');
            console.log('Found twitter:image:', imageUrl);
          }
        }
        
        if (!imageUrl) {
          const images = doc.querySelectorAll('img');
          for (let img of images) {
            const src = img.src || img.getAttribute('data-src');
            if (src && (src.includes('product') || src.includes('item') || src.includes('goods') || src.includes('image'))) {
              imageUrl = src;
              console.log('Found product image:', imageUrl);
              break;
            }
          }
        }
        
        if (!imageUrl) {
          const firstImg = doc.querySelector('img');
          if (firstImg) {
            imageUrl = firstImg.src || firstImg.getAttribute('data-src');
            console.log('Found first image:', imageUrl);
          }
        }
        
        if (imageUrl) {
          if (imageUrl.startsWith('//')) {
            imageUrl = `https:${imageUrl}`;
          } else if (imageUrl.startsWith('/')) {
            imageUrl = `https://item.gmarket.co.kr${imageUrl}`;
          } else if (!imageUrl.startsWith('http')) {
            imageUrl = `https://item.gmarket.co.kr/${imageUrl}`;
          }
          
          console.log('Final image URL:', imageUrl);
          ogImage.value = imageUrl;
        } else {
          console.log('No image found in HTML');
          ogImage.value = 'https://via.placeholder.com/400x400/2F9266/FFFFFF?text=상품+이미지';
        }
      } else {
        console.log('HTML content could not be fetched');
        ogImage.value = 'https://via.placeholder.com/400x400/2F9266/FFFFFF?text=상품+이미지';
      }
    } catch (error) {
      console.error('OG 이미지 가져오기 실패:', error);
      ogImage.value = 'https://via.placeholder.com/400x400/2F9266/FFFFFF?text=상품+이미지';
    } finally {
      isLoadingImage.value = false;
    }
  };
  
  const handleImageError = () => {
    ogImage.value = null;
  };
  
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
    const markerImage = new kakao.maps.MarkerImage(
      '/images/delivery-driver.png',
      new kakao.maps.Size(50, 54)
    );

    marker = new kakao.maps.Marker({
      position: position,
      map: map,
      image: markerImage
    });
    
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
  
  const shareLink = async () => {
    try {
      await navigator.share({
        title: '친구가 공구를 요청했어요.',
        text: `${productTitle} 공구를 친구가 요청했어요.`,
        url: window.location.href,
      });
    } catch (err) {
      console.error('공유 실패:', err);
    }
  };
  
  onMounted(async () => {
    try {
      await loadKakaoMapScript()
      initKakaoMap()
      fetchOGImage()
    } catch (error) {
      console.error(error)
    }
  })
  
  onUnmounted(() => {
    if (map) {
      map = null
    }
  })
  </script>