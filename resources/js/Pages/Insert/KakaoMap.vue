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
    <div id="kakao-map" class="w-full h-64 rounded mb-2"></div>
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
let map = null;
let marker = null;
let geocoder = null;

function loadKakaoMapScript() {
  return new Promise((resolve) => {
    if (window.kakao && window.kakao.maps) {
      resolve();
      return;
    }
    const script = document.createElement('script');
    script.src =
      '//dapi.kakao.com/v2/maps/sdk.js?appkey=b7fa5f4e55762ebaa26fbc9c642b7378&libraries=services&autoload=false';
    script.onload = () => {
      window.kakao.maps.load(resolve);
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

onMounted(async () => {
  await loadKakaoMapScript();
  geocoder = new window.kakao.maps.services.Geocoder();
  const container = document.getElementById('kakao-map');
  const options = {
    center: new window.kakao.maps.LatLng(37.5665, 126.9780),
    level: 3
  };
  map = new window.kakao.maps.Map(container, options);
  marker = new window.kakao.maps.Marker({
    position: options.center,
    map
  });

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        setMapCenter(pos.coords.latitude, pos.coords.longitude);
      },
      () => {
      }
    );
  }

  window.kakao.maps.event.addListener(map, 'click', function(mouseEvent) {
    const latlng = mouseEvent.latLng;
    setMapCenter(latlng.getLat(), latlng.getLng());
  });
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