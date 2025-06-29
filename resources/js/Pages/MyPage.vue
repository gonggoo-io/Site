<template>
  <Header />
  <main class="pt-[72px] min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="w-full max-w-md md:max-w-2xl lg:max-w-3xl xl:max-w-4xl mx-auto py-8 px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12">
      
      <!-- 헤더 -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">마이페이지</h1>
        <p class="text-gray-600">거래 내역과 배송 정보를 한눈에 확인하세요</p>
      </div>

      <!-- 프로필 카드 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-4">
          <div class="relative">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#2F9266] to-[#25804d] flex items-center justify-center text-2xl font-bold text-white shadow-lg">
              희
            </div>
            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <span class="font-bold text-xl text-gray-900">희성</span>
              <span class="px-2 py-0.5 bg-[#2F9266]/10 text-[#2F9266] text-xs font-medium rounded-full">활성 사용자</span>
            </div>
            <div class="text-gray-500 text-sm mb-2">shinhuiseong07@gmail.com</div>
            <div class="flex items-center gap-4 text-xs text-gray-400">
              <span>가입일: 2024.01.15</span>
              <span>•</span>
              <span>거래 완료: 12건</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 배송 조회 카드 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-[#2F9266]/10 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-[#2F9266]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">배송 조회</h2>
            <p class="text-sm text-gray-500">택배사와 운송장 번호로 실시간 배송 현황을 확인하세요</p>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="space-y-2">
            <label for="courier" class="block text-sm font-medium text-gray-700">택배사</label>
            <select id="courier" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2F9266]/20 focus:border-[#2F9266] transition-all duration-200">
              <option value="">택배사를 선택하세요</option>
              <option value="cj">CJ대한통운</option>
              <option value="hanjin">한진택배</option>
              <option value="lotte">롯데택배</option>
              <option value="logen">로젠택배</option>
              <option value="post">우체국택배</option>
            </select>
          </div>
          <div class="space-y-2">
            <label for="invoice" class="block text-sm font-medium text-gray-700">운송장 번호</label>
            <input id="invoice" type="text" placeholder="1234567890" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2F9266]/20 focus:border-[#2F9266] transition-all duration-200" />
          </div>
          <div class="flex items-end">
            <button type="button" class="w-full px-6 py-3 bg-gradient-to-r from-[#2F9266] to-[#25804d] text-white font-semibold rounded-xl hover:shadow-lg hover:scale-105 transform transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#2F9266]/50">
              <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
              조회하기
            </button>
          </div>
        </div>
      </div>

      <!-- 내가 신청한 거래 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">내가 신청한 거래</h2>
            <p class="text-sm text-gray-500">구매 요청한 상품들을 확인하세요</p>
          </div>
          <div class="ml-auto">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">2건</span>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=facearea&w=128&q=80" alt="맥북 프로 16인치 2021" class="w-16 h-16 rounded-xl object-cover shadow-sm" />
            <div class="flex-1">
              <div class="font-semibold text-gray-900 mb-1">맥북 프로 16인치 2021</div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">중고 거래</span>
                <span class="text-xs text-gray-400">2024.06.25</span>
              </div>
              <div class="text-lg font-bold text-[#2F9266]">1,900,000원</div>
            </div>
            <div class="text-right">
              <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <span class="text-xs text-gray-500">대기중</span>
            </div>
          </div>
          
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=facearea&w=128&q=80" alt="아이폰 15 프로" class="w-16 h-16 rounded-xl object-cover shadow-sm" />
            <div class="flex-1">
              <div class="font-semibold text-gray-900 mb-1">아이폰 15 프로</div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">직거래</span>
                <span class="text-xs text-gray-400">2024.06.20</span>
              </div>
              <div class="text-lg font-bold text-[#2F9266]">1,200,000원</div>
            </div>
            <div class="text-right">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <span class="text-xs text-gray-500">완료</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 내가 참여한 거래 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">내가 참여한 거래</h2>
            <p class="text-sm text-gray-500">판매했거나 구매한 상품들을 확인하세요</p>
          </div>
          <div class="ml-auto">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">2건</span>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
            <img src="https://images.unsplash.com/photo-1512499617640-c2f999098c01?auto=format&fit=facearea&w=128&q=80" alt="에어팟 프로 2세대" class="w-16 h-16 rounded-xl object-cover shadow-sm" />
            <div class="flex-1">
              <div class="font-semibold text-gray-900 mb-1">에어팟 프로 2세대</div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">중고 거래</span>
                <span class="text-xs text-gray-400">2024.06.15</span>
              </div>
              <div class="text-lg font-bold text-[#2F9266]">250,000원</div>
            </div>
            <div class="text-right">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <span class="text-xs text-gray-500">완료</span>
            </div>
          </div>
          
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
            <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=facearea&w=128&q=80" alt="닌텐도 스위치 OLED" class="w-16 h-16 rounded-xl object-cover shadow-sm" />
            <div class="flex-1">
              <div class="font-semibold text-gray-900 mb-1">닌텐도 스위치 OLED</div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">택배 거래</span>
                <span class="text-xs text-gray-400">2024.06.10</span>
              </div>
              <div class="text-lg font-bold text-[#2F9266]">350,000원</div>
            </div>
            <div class="text-right">
              <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <span class="text-xs text-gray-500">완료</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 배송 중인 거래 -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">배송 중인 거래</h2>
            <p class="text-sm text-gray-500">현재 배송 진행 중인 상품들을 확인하세요</p>
          </div>
          <div class="ml-auto">
            <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-medium rounded-full animate-pulse">2건</span>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:shadow-sm transition-all duration-200 cursor-pointer">
            <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=facearea&w=128&q=80" alt="삼성 갤럭시 워치 6" class="w-16 h-16 rounded-xl object-cover shadow-sm" />
            <div class="flex-1">
              <div class="font-semibold text-gray-900 mb-1">삼성 갤럭시 워치 6</div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">택배 거래</span>
                <span class="text-xs text-gray-400">2024.06.28</span>
              </div>
              <div class="text-lg font-bold text-blue-600">320,000원</div>
            </div>
            <div class="text-right">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-4 h-4 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
              </div>
              <span class="text-xs text-blue-600 font-medium">배송중</span>
            </div>
          </div>
          
          <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:shadow-sm transition-all duration-200 cursor-pointer">
            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=facearea&w=128&q=80" alt="LG 그램 17" class="w-16 h-16 rounded-xl object-cover shadow-sm" />
            <div class="flex-1">
              <div class="font-semibold text-gray-900 mb-1">LG 그램 17</div>
              <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">중고 거래</span>
                <span class="text-xs text-gray-400">2024.06.27</span>
              </div>
              <div class="text-lg font-bold text-blue-600">1,500,000원</div>
            </div>
            <div class="text-right">
              <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-4 h-4 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
              </div>
              <span class="text-xs text-blue-600 font-medium">배송중</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
  <Footer />
</template>

<script setup>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
</script>