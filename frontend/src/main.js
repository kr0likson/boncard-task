import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import 'element-plus/dist/index.css';
import ElementPlus from 'element-plus';
import store from "@/store.js";

const app = createApp(App)
app.use(ElementPlus)
app.use(store)
app.mount('#app')