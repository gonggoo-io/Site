
<template>
</template>

<script setup>
import { onMounted, onUnmounted, watch } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  image: {
    type: String,
    required: true
  }
})

let originalTitle = ''
const metaProperties = ['og:title', 'og:description', 'og:image']

const updateMetaTag = (property, content) => {
  let meta = document.querySelector(`meta[property="${property}"]`)
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('property', property)
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', content)
}

const updateMetaTags = () => {
  if (!originalTitle) {
    originalTitle = document.title
  }

  if (props.title) {
    document.title = props.title
    updateMetaTag('og:title', props.title)
  }

  if (props.description) {
    updateMetaTag('og:description', props.description)
  }

  if (props.image) {
    updateMetaTag('og:image', props.image)
  }

  console.log('OpenGraph image:', props.image)
}

const restoreOriginalMetaTags = () => {
  if (originalTitle) {
    document.title = originalTitle
  }

  metaProperties.forEach(prop => {
    const tag = document.querySelector(`meta[property="${prop}"]`)
    if (tag) {
      tag.remove()
    }
  })
}

onMounted(() => {
  updateMetaTags()
})

onUnmounted(() => {
  restoreOriginalMetaTags()
})

watch(() => [props.title, props.description, props.image], () => {
  updateMetaTags()
})
</script>
