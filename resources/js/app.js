import {createApp} from 'vue'

require('./bootstrap')
import '../../src/input.css'
import App from './components/App.vue'
import Store from './store'
import Axios from './axios.js'
import Router from './router'
import jwt_decode from 'jwt-decode'
import FlashMessage from '@smartweb/vue-flash-message';

import LeftBar from './components/LeftBar.vue'
import Menu from './components/Menu.vue'
import Footer from './components/Footer.vue'

const app = createApp(App)
app.use(FlashMessage);
app.component('Footer', Footer)
app.component('LeftBar', LeftBar)
app.component('Menu', Menu)
app.config.globalProperties.$axios = Axios;
app.config.globalProperties.$jwt_decode = jwt_decode;
app.use(Store)
app.use(Router)

app.mount('#app')