<template>
  <Header />
  <Container>
    <div class="flex flex-col min-h-screen">
      <div class="flex flex-col lg:flex-row flex-1 gap-10">
        <Sidebar active="completed" />
        <main class="flex-1 pt-6 lg:pt-10 lg:mt-20 px-0 w-full">
          <div class="text-black font-semibold text-3xl mb-1">
            ✅ 완료된 거래
            <span class="text-lg text-gray-700 font-medium"> · 2025. 01.01 ~ 05.01</span>
          </div>
          <div class="text-md text-gray-500 font-medium">※ 거래가 모두 완료되었습니다.</div>
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
                <span class="text-primary font-semibold text-sm sm:text-base">1/12</span>
              </div>
            </div>
          </section>
        </main>
      </div>
      <Footer />
    </div>
  </Container>
</template>

<script setup>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import Container from '../components/Container.vue'
import Sidebar from '../components/Sidebar.vue'
</script> 