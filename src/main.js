import Vue from 'vue'
import App from './App.vue'
import store from './store'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import introJs from 'intro.js'
introJs().setOptions({ nextLabel: "Suivant", prevLabel: "Précédent", skipLabel: "Passer", doneLabel: "Terminer" });

Vue.prototype.$introJs = introJs;
import 'intro.js/introjs.css';

import './style/Apps.css';
import './style/General.css';
import './style/News.css';
import './theme/theme.css';
import vueConfig from '../vue.config'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.config.productionTip = false

new Vue({
  store,
  render: h => h(App),
}).$mount('#app')
