<template>
    <div class="min-h-screen flex flex-col">
      <Header />
  
      <main class="flex-grow">
        <Container>
          <h1 class="text-2xl sm:text-3xl md:text-[2.5rem] font-semibold mt-32 sm:mt-44 tracking-tight">{{ userName }}님</h1>
          <h1 class="text-2xl sm:text-3xl md:text-[2.5rem] font-semibold mt-2 sm:mt-4 tracking-tight">근처에서 새로운<br class="sm:hidden" /> 공구가 시작되었어요👏</h1>
          <div class="text-sm sm:text-base mt-4 text-gray-500">지금 6+개의 공구가 등록되어있고, 런칭 이후 10+개의 공구가 성사되었어요. {{ userName }}님도 공구를 성사시켜봐요!</div>
          <div class="mt-7 flex items-center justify-between">
            <div class="text-xl sm:text-xl md:text-2xl font-bold mt-8 mb-1">
              <template v-if="locationLoading">내 위치</template>
              <template v-else>
                {{ locationName }}
                <br class="sm:hidden" />  
                근처에서 진행되고 있는 공구
              </template>
            </div>
          </div>
          <div>
          </div>
          <div class="mt-4 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 gap-y-8">
            <div v-for="n in 10" :key="n" class="cursor-pointer" @click="goToContent(n)">
              <div class="bg-gray-100 h-[150px] sm:h-[180px] rounded-2xl overflow-hidden">
              </div>
              <div class="text-left text-lg sm:text-xl font-semibold text-[#333333] mt-2">
                  <span>14,000원</span>
              </div>
              <div class="text-left text-sm sm:text-base text-gray-500">
                  <span>석부장 최애 탕비실 사무실 간식 박스과자세트 16p</span>
              </div>
              <div class="text-left text-xs sm:text-sm mt-1 text-gray-500 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 mr-1">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                  </svg>
                  1/12 · 3일 후 마감
              </div>
            </div>
          </div>
        </Container>
      </main>
  
      <Footer class="mt-12" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  import { router, usePage } from '@inertiajs/vue3'
  import Container from '../Pages/components/Container.vue'
  import Header from '../Pages/components/Header.vue'
  import Footer from '../Pages/components/Footer.vue'
  
  const page = usePage();
  const userName = page.props.auth?.user?.name || '';
  
  const locationName = ref('');
  const locationLoading = ref(true);  
  
  const KAKAO_API_KEY = import.meta.env.VITE_SITE_KAKAO_API_KEY;
  
  const fetchLocationName = async (lat, lng) => {
    try {
      const keywordRes = await fetch(
        `https://dapi.kakao.com/v2/local/search/keyword.json?y=${lat}&x=${lng}&radius=200`,
        {
          headers: { Authorization: `KakaoAK ${KAKAO_API_KEY}` },
        }
      );
      const keywordData = await keywordRes.json();

      if (keywordData.documents && keywordData.documents.length > 0) {
        locationName.value = keywordData.documents[0].place_name;
      } else {
        const addrRes = await fetch(
          `https://dapi.kakao.com/v2/local/geo/coord2address.json?x=${lng}&y=${lat}`,
          {
            headers: { Authorization: `KakaoAK ${KAKAO_API_KEY}` },
          }
        );
        const addrData = await addrRes.json();
        if (addrData.documents && addrData.documents.length > 0) {
          const result = addrData.documents[0];
          locationName.value = result.road_address?.building_name || result.road_address?.address_name || result.address?.address_name || '내 위치';
        } else {
          locationName.value = '내 위치';
        }
      }
    } catch (e) {
      console.error('Failed to fetch location name:', e);
      locationName.value = '내 위치';
    } finally {
      locationLoading.value = false;
    }
  };
  
  const showCategory = ref(false)
  const showSort = ref(false)
  const categoryRef = ref(null)
  const sortRef = ref(null)
  
  const selectCategory = (category) => {
    selectedCategory.value = category
    showCategory.value = false
  }
  
  const selectSort = (sort) => {
    selectedSort.value = sort
    showSort.value = false
  }
  
  const handleClickOutside = (event) => {
    if (sortRef.value && !sortRef.value.contains(event.target)) {
      showSort.value = false
    }
  }
  
  const showLines = ref([false, false, false]);
  onMounted(() => {
    setTimeout(() => { showLines.value[0] = true }, 300);
    setTimeout(() => { showLines.value[1] = true }, 1200);
    setTimeout(() => { showLines.value[2] = true }, 2100);
    document.addEventListener('click', handleClickOutside)
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          fetchLocationName(pos.coords.latitude, pos.coords.longitude);
        },
        (error) => {
          console.error(`Geolocation error (code ${error.code}): ${error.message}`);
          locationName.value = '내 위치';
          locationLoading.value = false;
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0,
        }
      );
    } else {
      console.error('Geolocation is not supported by this browser.');
      locationName.value = '내 위치';
      locationLoading.value = false;
    }
  })
  
  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
  })
  
  const toggleCategory = () => {
    showCategory.value = !showCategory.value
    showSort.value = false
  }
  
  const toggleSort = () => {
    showSort.value = !showSort.value
  }

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  const goToContent = (contentId) => {
    router.visit(`/content/${contentId}`)
  }

  const goToInsert = () => {
    router.visit('/insert')
  }
  </script>
  