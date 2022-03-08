import Vue from 'vue'
import App from './App.vue'
import store from './store'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'


import './style/Apps.css';
import './style/General.css';
import './style/News.css';
import './theme/theme.css';


Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.config.productionTip = false
// eslint-disable-next-line
__webpack_nonce__ = btoa(getRequestToken())
// eslint-disable-next-line
console.log(window.OC)
// eslint-disable-next-line
console.log(OC)

new Vue({
  store,
  render: h => h(App),
}).$mount('#app')
