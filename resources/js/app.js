// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp, Link, router } from '@inertiajs/vue3'
import '../css/app.css'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue')
    return pages[`./Pages/${name}.vue`]()
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.component('Link', Link)
    app.mount(el)
  },
  progress: {
    delay: 100,
    color: '#2F9266',
    includeCSS: true,
    showSpinner: false,
  },
})
