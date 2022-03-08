import Vue from 'vue'
import { generateFilePath } from '@nextcloud/router'
import { getRequestToken } from '@nextcloud/auth'
import Dashboard from './views/Dashboard'

// eslint-disable-next-line
__webpack_nonce__ = btoa(getRequestToken())

// eslint-disable-next-line
__webpack_public_path__ = generateFilePath('intranetagglo', '', 'js/')

Vue.prototype.OC = OC
Vue.prototype.OCA = OCA

document.addEventListener('DOMContentLoaded', function () {
    OCA.Dashboard.register('intranetagglo', (el) => {
        const View = Vue.extend(Dashboard)
        new View({
            propsData: {},
        }).$mount(el)
    })
})