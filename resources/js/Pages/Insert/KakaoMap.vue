<template>
  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">배송 위치</label>
    <div class="mb-2">
      <input
        v-model="searchQuery"
        @keyup.enter="searchAddress"
        type="text"
        :class="inputClass"
        placeholder="주소 또는 장소 검색"
        :disabled="mapLoading || mapError"
      />
      <ul v-if="searchResults.length" class="bg-white border rounded shadow max-h-40 overflow-y-auto mb-2">
        <li
          v-for="(result, idx) in searchResults"
          :key="idx"
          @click="selectSearchResult(result)"
          class="px-3 py-2 cursor-pointer hover:bg-gray-100"
        >
          <span v-if="result.place_name"><b>{{ result.place_name }}</b> <span class="text-xs text-gray-500">({{ result.address_name }})</span></span>
          <span v-else>{{ result.address_name }}</span>
        </li>
      </ul>
    </div>
    <div id="kakao-map" class="w-full h-64 rounded mb-2 relative">
      <div v-if="mapLoading" class="absolute inset-0 flex items-center justify-center bg-gray-100 rounded">
        <div class="text-center">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#2F9266] mx-auto mb-2"></div>
          <p class="text-sm text-gray-600">지도를 불러오는 중...</p>
        </div>
      </div>
      <div v-if="mapError" class="absolute inset-0 flex items-center justify-center bg-gray-100 rounded">
        <div class="text-center">
          <p class="text-sm text-red-600 mb-2">지도를 불러올 수 없습니다.</p>
          <button 
            @click="retryLoadMap" 
            class="px-3 py-1 bg-[#2F9266] text-white rounded text-sm hover:bg-[#267a54] transition-colors"
          >
            다시 시도
          </button>
        </div>
      </div>
    </div>
    <div class="text-base text-gray-700 mt-4">
      <span v-if="address" class="font-medium text-lg bg-gray-100 px-2 py-1 rounded">🪧 {{ address }}</span>
      <span v-else class="text-base">주소를 선택하세요.</span>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, defineEmits } from 'vue';

const emit = defineEmits(['update:address']);
const props = defineProps({
  address: String,
  inputClass: {
    type: String,
    default: ''
  }
});

const address = ref(props.address || '');
const searchQuery = ref('');
const searchResults = ref([]);
const mapLoading = ref(true);
const mapError = ref(false);
let map = null;
let marker = null;
let geocoder = null;
let scriptLoading = false;

const KAKAO_MAP_KEY = import.meta.env.VITE_KAKAO_MAP_KEY;

function loadKakaoMapScript() {
  return new Promise((resolve) => {
    if (window.kakao && window.kakao.maps) {
      resolve();
      return;
    }
    const script = document.createElement('script');
    script.src = `//dapi.kakao.com/v2/maps/sdk.js?appkey=${KAKAO_MAP_KEY}&libraries=services&autoload=false`;
    
    script.onload = () => {
      window.kakao.maps.load(() => {
        scriptLoading = false;
        resolve();
      });
    };
    document.head.appendChild(script);
  });
}

function setMapCenter(lat, lng) {
  const center = new window.kakao.maps.LatLng(lat, lng);
  map.setCenter(center);
  marker.setPosition(center);
  geocoder.coord2Address(lng, lat, function(result, status) {
    if (status === window.kakao.maps.services.Status.OK) {
      const addr = result[0].road_address?.address_name || result[0].address.address_name;
      address.value = addr;
      emit('update:address', addr);
    }
  });
}

function searchAddress() {
  if (!searchQuery.value.trim()) return;
  if (!window.kakao || !window.kakao.maps || !window.kakao.maps.services) {
    console.warn('카카오맵이 로드되지 않았습니다.');
    return;
  }
  const ps = new window.kakao.maps.services.Places();
  ps.keywordSearch(searchQuery.value, function(data, status) {
    if (status === window.kakao.maps.services.Status.OK) {
      searchResults.value = data;
    } else {
      searchResults.value = [];
    }
  });
}

function selectSearchResult(result) {
  let lat = result.y || result.latitude;
  let lng = result.x || result.longitude;
  setMapCenter(lat, lng);
  searchResults.value = [];
}

function initMap() {
  try {
    const container = document.getElementById('kakao-map');
    if (!container) {
      mapError.value = true;
      return;
    }

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const userLat = pos.coords.latitude;
          const userLng = pos.coords.longitude;
          
          const options = {
            center: new window.kakao.maps.LatLng(userLat, userLng),
            level: 3
          };
          
          map = new window.kakao.maps.Map(container, options);
          marker = new window.kakao.maps.Marker({
            position: options.center,
            map
          });

          window.kakao.maps.event.addListener(map, 'click', function(mouseEvent) {
            const latlng = mouseEvent.latLng;
            setMapCenter(latlng.getLat(), latlng.getLng());
          });

          setMapCenter(userLat, userLng);
          mapLoading.value = false;
        },
        (error) => {
          console.warn(error);
          const options = {
            center: new window.kakao.maps.LatLng(37.5665, 126.9780),
            level: 3
          };
          
          map = new window.kakao.maps.Map(container, options);
          marker = new window.kakao.maps.Marker({
            position: options.center,
            map
          });

          window.kakao.maps.event.addListener(map, 'click', function(mouseEvent) {
            const latlng = mouseEvent.latLng;
            setMapCenter(latlng.getLat(), latlng.getLng());
          });

          mapLoading.value = false;
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        }
      );
    } else {
      const options = {
        center: new window.kakao.maps.LatLng(37.5665, 126.9780),
        level: 3
      };
      
      map = new window.kakao.maps.Map(container, options);
      marker = new window.kakao.maps.Marker({
        position: options.center,
        map
      });

      window.kakao.maps.event.addListener(map, 'click', function(mouseEvent) {
        const latlng = mouseEvent.latLng;
        setMapCenter(latlng.getLat(), latlng.getLng());
      });

      mapLoading.value = false;
    }
  } catch (error) {
    console.error(error);
    mapError.value = true;
    mapLoading.value = false;
  }
}

function retryLoadMap() {
  mapLoading.value = true;
  mapError.value = false;

  if (map) {
    map = null;
  }
  if (marker) {
    marker = null;
  }
  if (geocoder) {
    geocoder = null;
  }

  setTimeout(() => {
    try {
      geocoder = new window.kakao.maps.services.Geocoder();
      initMap();
    } catch (error) {
      console.error(error);
      mapError.value = true;
      mapLoading.value = false;
    }
  }, 100);
}

onMounted(async () => {
  try {
    await loadKakaoMapScript();
    geocoder = new window.kakao.maps.services.Geocoder();
    initMap();
  } catch (error) {
    console.error(error);
    mapError.value = true;
    mapLoading.value = false;
  }
});

watch(address, (val) => {
  emit('update:address', val);
});
</script>

<style scoped>
#kakao-map {
  min-height: 256px;
  max-height: 320px;
}
</style> 