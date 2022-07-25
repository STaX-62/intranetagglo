import Vue from 'vue'
import App from './App.vue'
import store from './store'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '@mdi/font/css/materialdesignicons.css'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import introJs from 'intro.js'
import axios from '@nextcloud/axios'
import VueEasyLightbox from 'vue-easy-lightbox'
Vue.use(VueEasyLightbox)

Vue.prototype.$introJs = introJs()
  .setOptions({
    nextLabel: "Suivant",
    prevLabel: "Précédent",
    doneLabel: "Terminer",
    showBullets: false,
    showProgress: true,
  });

Vue.prototype.$axios = axios;

import 'intro.js/introjs.css';

import './style/Apps.css';
import './style/General.css';
import './style/News.css';
import './theme/theme.css';

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.config.productionTip = false

new Vue({
  store,
  render: h => h(App),
}).$mount('#app')
