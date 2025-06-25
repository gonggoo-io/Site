<template>
  <header
    class="bg-white/60 backdrop-blur-sm py-4 transition-all duration-200 fixed top-0 left-0 right-0 z-50"
    :class="{}"
  >
    <Container :flex="true" class="flex items-center justify-between">
      <div class="flex items-center gap-10">
        <Link href="/dashboard" class="flex items-center gap-2 hover:opacity-80 transition">
          <span class="text-2xl font-semibold text-black nexon-gothic">gonggoo</span>
        </Link>
      </div>

      <div class="hidden md:flex gap-4 items-center">
        <template v-if="auth?.user">
          <Link href="/select-insert-type" class="flex items-center gap-2 text-base text-[#2B2D36] font-normal transition-colors px-3.5 py-1.5 rounded-md hover:bg-gray-100">
            공구 등록하기
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
          </Link>
          <div class="relative" ref="desktopDropdownContainer">
            <button 
              @click="toggleDropdown" 
              class="flex items-center hover:opacity-80 transition"
            >
              <div class="w-9 h-9 rounded-full bg-[#2F9266] flex items-center justify-center text-white font-medium text-xl leading-none">
                <span class="flex items-center justify-center w-full h-full">{{ auth.user.name.charAt(0) }}</span>
              </div>
            </button>

            <div 
              v-if="isDropdownOpen"
              class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
            >
              <Link
                href="/logout"
                method="post"
                as="button"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                </svg>
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

      <div class="md:hidden flex items-center" ref="mobileMenuButton">
        <button @click="toggleMobileMenu">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>
      </div>
    </Container>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div v-if="isMobileMenuOpen" ref="mobileMenuContainer" class="absolute top-full right-4 mt-2 w-56 md:hidden origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none py-1">
        <div class="py-1">
          <template v-if="auth?.user">
            <Link href="/select-insert-type" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" @click="toggleMobileMenu">
              공구 등록하기
            </Link>
            <Link href="/logout" method="post" as="button" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" @click="toggleMobileMenu">
              로그아웃
            </Link>
          </template>
          <template v-else>
            <Link href="/signin" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" @click="toggleMobileMenu">
              로그인
            </Link>
            <Link href="/signup" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" @click="toggleMobileMenu">
              회원가입
            </Link>
          </template>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import Container from './Container.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted, watch } from 'vue';

const page = usePage();
const currentPath = computed(() => page.url);
const auth = computed(() => page.props.auth);

const isDropdownOpen = ref(false);
const isMobileMenuOpen = ref(false);

const desktopDropdownContainer = ref(null);
const mobileMenuButton = ref(null);
const mobileMenuContainer = ref(null);

watch(isMobileMenuOpen, (newVal) => {
  if (newVal) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeDropdown = () => {
  isDropdownOpen.value = false;
};

const handleClickOutside = (event) => {
  if (desktopDropdownContainer.value && !desktopDropdownContainer.value.contains(event.target)) {
    isDropdownOpen.value = false;
  }
  if (
    mobileMenuButton.value &&
    !mobileMenuButton.value.contains(event.target) &&
    mobileMenuContainer.value &&
    !mobileMenuContainer.value.contains(event.target)
  ) {
    isMobileMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
  document.body.style.overflow = '';
});
</script>

<style scoped>
.rotate-180 {
  transform: rotate(180deg);
}
</style>