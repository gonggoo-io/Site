<template>
  <Header />

  <Container class="w-full max-w-none">
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <aside
          class="w-full lg:w-[220px] bg-white border-b lg:border-b-0 lg:border-r border-[#eee] pt-6 lg:pt-10 mt-20 lg:min-h-full"
        >
          <ul class="flex flex-row lg:flex-col gap-x-6 lg:gap-x-0 px-6 lg:px-0 justify-center items-center w-full">
            <li
              :class="[
                'text-lg mb-0 lg:mb-6 cursor-pointer whitespace-nowrap',
                selectedMenu === 'applied' ? 'text-black font-bold' : 'text-gray-400'
              ]"
            >
              진행중인 거래
            </li>
            <li 
              :class="[
                'text-lg mb-0 lg:mb-6 cursor-pointer whitespace-nowrap',
                selectedMenu === 'joined' ? 'text-black font-bold' : 'text-gray-400'
              ]"
            >
              배송 중인 거래
            </li>
            <li
              :class="[
                'text-lg mb-0 lg:mb-6 cursor-pointer whitespace-nowrap',
                selectedMenu === 'shipping' ? 'text-black font-bold' : 'text-gray-400'
              ]"
            >
              완료된 거래
            </li>
          </ul>
        </aside>

        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
          <div class="text-black font-semibold text-3xl mb-1">
            📫 총 4건
            <span class="text-lg text-gray-700 font-medium"> · 2025. 01.01 ~ 05.01</span>
          </div>
          <div class="text-md text-gray-500 font-medium">※ 참여를 취소하면 취소한 공구에 다시 참여할 수 없어요.</div>
          <div class="text-black font-semibold text-2xl mt-12 mb-2 pb-2 border-b border-gray-200">2025.04.23</div>
          <section class="bg-white rounded-xl p-6 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between border border-gray-200/60 shadow-sm transition-all duration-200 w-full">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-[3px] mr-4 flex-shrink-0 flex items-center justify-center">
              <div class="w-8 h-8 bg-blue-400 rounded-[2px]"></div>
            </div>
            <div class="w-full">
              <div class="font-semibold mb-1 text-xl text-gray-800">국산 안전 접지 개별 2구 3구 4구 5구 6구 멀티탭</div>
              <div class="font-normal text-xs sm:text-sm text-gray-500 flex items-center whitespace-normal">
                01.선없는멀티탭절전형 1구 멀티탭(스위치)화이트 (-4,150원)
                <span class="mx-2 text-gray-300 hidden sm:inline">·</span>
                <img src="/public/images/dashboard-users.svg" alt="users" class="size-4 mr-1" />
                <span class="text-[#2F9266] font-semibold text-sm sm:text-base">1/12</span>
              </div>
              <button class="block sm:hidden w-full mt-4 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium text-sm flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                나가기
              </button>
            </div>
            <button class="hidden sm:block ml-4 p-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium text-sm whitespace-nowrap flex-shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </section>
        </main>
      </div>
      <Footer />
    </div>
  </Container>
</template>

<script setup>
import { ref } from 'vue'
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import Container from './components/Container.vue'

const selectedMenu = ref('applied')
</script>
