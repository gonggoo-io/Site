<template>
  <aside
    class="w-full lg:w-[200px] bg-white pt-3 lg:pt-7 mt-20 lg:min-h-full lg:ml-[-8px]"
  >
    <ul class="flex flex-row lg:flex-col gap-x-6 lg:gap-x-0 px-4 lg:px-2 justify-start items-start w-full">
      <li
        :class="[
          'text-lg mb-0 lg:mb-6 cursor-pointer whitespace-nowrap text-left w-full py-3',
          active === 'applied' ? 'text-black font-bold' : 'text-gray-400'
        ]"
        @click="goTo('/mypage/applied')"
      >
        마이페이지
      </li>
      <!-- <li
        :class="[
          'text-lg mb-0 lg:mb-6 cursor-pointer whitespace-nowrap text-left w-full py-3',
          active === 'shipping' ? 'text-black font-bold' : 'text-gray-400'
        ]"
        @click="goTo('/mypage/shipping')"
      >
        배송 중인 거래
      </li>
      <li
        :class="[
          'text-lg mb-0 lg:mb-6 cursor-pointer whitespace-nowrap text-left w-full py-3',
          active === 'completed' ? 'text-black font-bold' : 'text-gray-400'
        ]"
        @click="goTo('/mypage/completed')"
      >
        완료된 거래
      </li> -->
    </ul>
  </aside>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
const props = defineProps({
  active: {
    type: String,
    required: true
  }
});
const goTo = (path) => {
  router.visit(path);
};
</script> 