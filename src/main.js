import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import vue2Editor from './plugins/vue2Editor'
import vueEasyLightbox from './plugins/vueEasyLightbox'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router';
import './theme/theme.css'
Vue.config.productionTip = false

Vue.prototype.$groups = []
Vue.prototype.$categories = []
Vue.prototype.$isAdmin = false

axios.get(generateUrl('apps/intranetagglo/news/category'), {
  params: {
    search: '',
  },
}).then(res => {
  Vue.prototype.$categories = res.data
})

axios.get(generateUrl(`apps/intranetagglo/isadmin`))
  .then(response => {
    Vue.prototype.$isAdmin = response.data
    if (response.data) {
      axios.get(generateUrl('apps/intranetagglo/api/groups'), {
        params: {
          search: '',
        },
      }).then(res => {
        Vue.prototype.$groups = res.data
      })
    }
  })

new Vue({
  vuetify,
  vue2Editor,
  vueEasyLightbox,
  render: h => h(App)
}).$mount('#app')
