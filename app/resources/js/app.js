import { createApp } from 'vue/dist/vue.esm-bundler.js'
import HelloWorld from './components/HelloWorld.vue'
import ExampleComponent from './components/ExampleComponent.vue'

const app = createApp({})
app.component('hello-world', HelloWorld)
app.component('example-component', ExampleComponent);
app.mount('#app')
