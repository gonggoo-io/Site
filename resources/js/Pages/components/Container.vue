<template>
  <div class="w-full px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12">
    <div :class="['w-full sm:w-[640px] md:w-[768px] lg:w-[1024px] xl:w-[1240px] mx-auto', { 'flex justify-between items-center': flex }]">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  flex: {
    type: Boolean,
    default: false
  }
});
</script>