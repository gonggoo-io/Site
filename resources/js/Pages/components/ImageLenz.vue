<template>
  <div class="relative">
    <div 
      class="bg-gray-100 h-[260px] md:h-[400px] rounded-2xl overflow-hidden flex items-center justify-center magnifier-container"
      @mousemove="handleMouseMove"
      @mouseleave="handleMouseLeave"
    >
      <img
        v-if="image"
        :src="image"
        :alt="alt"
        class="w-full h-full object-cover magnifier-image"
        ref="productImage"
      />
      <slot v-else />
      <div 
        v-if="showMagnifier && image"
        class="magnifier-lens"
        :style="magnifierStyle"
      ></div>
    </div>
    <p class="text-base text-gray-500 mt-2 text-center w-full">※ 구매 링크 미리보기 이미지입니다.</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  image: {
    type: String,
    default: ''
  },
  alt: {
    type: String,
    default: ''
  }
})

const productImage = ref(null)
const showMagnifier = ref(false)
const magnifierStyle = ref({})

const handleMouseMove = (event) => {
  if (!productImage.value) return
  const rect = productImage.value.getBoundingClientRect()
  const x = event.clientX - rect.left
  const y = event.clientY - rect.top
  const scale = 2
  const width = rect.width * scale
  const height = rect.height * scale
  const centerX = x - width / 2
  const centerY = y - height / 2
  magnifierStyle.value = {
    left: `${centerX}px`,
    top: `${centerY}px`,
    width: `${width}px`,
    height: `${height}px`,
    backgroundImage: `url(${props.image})`,
    backgroundPosition: `${-centerX}px ${-centerY}px`,
    backgroundSize: `${rect.width}px ${rect.height}px`,
    transform: `scale(${scale})`,
  }
  showMagnifier.value = true
}

const handleMouseLeave = () => {
  showMagnifier.value = false
}
</script>

<style scoped>
.magnifier-container {
  position: relative;
  cursor: crosshair;
}

.magnifier-image {
  pointer-events: none;
}

.magnifier-lens {
  position: absolute;
  width: 150px;
  height: 150px;
  border: 2px solid #2F9266;
  border-radius: 50%;
  background-repeat: no-repeat;
  background-color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  pointer-events: none;
  z-index: 10;
  overflow: hidden;
}

@media (max-width: 768px) {
  .magnifier-lens {
    display: none;
  }
  
  .magnifier-container {
    cursor: default;
  }
}
</style> 