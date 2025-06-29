<template>
  <Header />

  <Container>
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <aside
          class="w-full lg:w-[220px] bg-white border-b lg:border-b-0 lg:border-r border-[#eee] pt-6 lg:pt-10 mt-20 lg:min-h-full"
        >
          <ul class="flex flex-row lg:flex-col gap-x-6 lg:gap-x-0 px-6 lg:px-0 overflow-x-auto">
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

        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-6 lg:px-0">
          <div class="text-black font-semibold text-2xl mb-4">
            📫 총 4건
            <span class="text-lg text-gray-700 font-medium"> · 2025. 01.01 ~ 05.01</span>
          </div>
          <section class="bg-gray-50 rounded-xl p-6 mb-5 flex items-center justify-between border border-gray-100">
            <div>
              <div class="font-semibold mb-1 text-xl">국산 안전 접지 개별 2구 3구 4구 5구 6구 멀티탭</div>
              <div class="font-normal text-sm text-gray-600 flex items-center">
                01.선없는멀티탭절전형 1구 멀티탭(스위치)화이트 (-4,150원)
                <span class="mx-2 text-gray-300">·</span>
                <img src="/public/images/dashboard-users.svg" alt="users" class="size-4 mr-1" />
                <span class="text-gray-500 text-xs sm:text-sm">1/12</span>
              </div>
            </div>
            <button class="ml-4 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition font-medium text-sm">참여 취소</button>
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
