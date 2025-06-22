<template>
  <header
    class="bg-white/60 backdrop-blur-sm py-4 border-b transition-all duration-200 fixed top-0 left-0 right-0 z-50"
    :class="{ 'border-b': currentPath !== '/' }"
  >
    <Container :flex="true" class="flex items-center justify-between">
      <div class="flex items-center gap-10">
        <Link href="/" class="flex items-center gap-2 hover:opacity-80 transition">
          <img src="/public/images/logo.svg" alt="logo" class="w-5 h-5" />
          <span class="text-xl font-bold text-[#2F9266]">공구</span>
        </Link>
        <template v-if="auth?.user">
          <Link
            href="/auto-insert"
            class="text-base text-gray-900 hover:text-[#2F9266] font-normal transition-colors"
          >
            새로운 공구 만들기
          </Link>
        </template>
      </div>

      <div class="flex gap-3 items-center">
        <template v-if="auth?.user">
          <div class="relative">
            <button 
              @click="toggleDropdown" 
              class="flex items-center gap-2 hover:opacity-80 transition"
            >
              <div class="w-8 h-8 rounded-full bg-[#2F9266] flex items-center justify-center text-white font-medium leading-none">
                <span class="flex items-center justify-center h-full">{{ auth.user.name.charAt(0) }}</span>
              </div>
              <span class="text-sm text-gray-700">{{ auth.user.name }}</span>
              <svg 
                class="w-4 h-4 text-gray-500 transition-transform" 
                :class="{ 'rotate-180': isDropdownOpen }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <div 
              v-if="isDropdownOpen"
              class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
            >
              <Link
                href="/logout"
                method="post"
                as="button"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                로그아웃
              </Link>
            </div>
          </div>
        </template>

        <template v-else>
          <Link
            href="/signin"
            class="px-3.5 py-1.5 text-[14px] border border-gray-300 rounded-md hover:bg-gray-100 hover:text-gray-800 transition-colors"
          >
            로그인
          </Link>
          <Link
            href="/signup"
            class="px-3.5 py-1.5 text-[14px] border border-[#2F9266] bg-[#2F9266] text-white rounded-md hover:bg-[#247A4F] hover:border-[#247A4F] transition-colors"
          >
            회원가입
          </Link>
        </template>
      </div>
    </Container>
  </header>
</template>

<script setup>
import Container from './Container.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const page = usePage();
const currentPath = computed(() => page.url);
const auth = computed(() => page.props.auth);

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
  isDropdownOpen.value = false;
};

const handleClickOutside = (event) => {
  const dropdown = document.querySelector('.relative');
  if (dropdown && !dropdown.contains(event.target)) {
    closeDropdown();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.rotate-180 {
  transform: rotate(180deg);
}
</style>
