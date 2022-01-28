<template>
  <div id="apps">
    <div id="apps-container" class="apps-container">
      <h2 class="apps-header">
        Applications
        <div id="apps-update-btn" v-if="userid.groups.includes('admin')">
          <AppsUpdate v-bind:appsarray.sync="appsarray" />
        </div>
      </h2>
      <a
        class="apps-content-main"
        v-for="(app,index) in appsarray"
        :key="index"
        @click="Open(app,index,updating)"
      >
        <div class="apps-update-indicator" v-if="userid.groups.includes('admin') && updating">
          <b-icon icon="gear"></b-icon>
        </div>
        <b-icon class="apps-icon" v-bind:icon="app.icon"></b-icon>
        <div class="apps-title">{{app.title}}</div>
        <a v-bind:id="'link'+index" v-bind:href="app.link" target="_blank"></a>
      </a>
    </div>
  </div>
</template>

<script>
import store from '@/store/index.js'
import AppsUpdate from '@/components/apps/apps-update.bdd.vue'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
export default {
  name: 'Apps',
  store: store,
  components: {
    AppsUpdate
  },
  computed: {
    appsoptions() {
      var News = this.$store.state.News
      var AppsArray = []
      for (var i = 0; i < News.length; i++) {
        AppsArray.push(News[i].apps)
      }
      const uniqueCaterogy = Array.from(new Set(AppsArray))
      return uniqueCaterogy
    },
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        this.$store.commit('updateSearch', value)
      }
    }
  },
  methods: {
    SetUpdating() {
      this.oldapparray = this.apparray
      this.updating = true
    },
    Return() {
      this.apparray = this.oldapparray
      this.updating = false
    },
    Save() {
      this.updating = false
    },
    Open(app, index, updating) {
      if (!updating) document.getElementById('link' + index).click();
      else if (updating && this.userid.groups.includes('admin')) {
        this.apptoupdate[index] = app;
        this.modal = true;
      }
    },
    AppsSet(value) {
      this.droptext = value
      console.log(this.droptext)
      this.$store.commit('updateCategories', value)
    }
  },
  mounted() {
    var url = `apps/intranetagglo${'/apps'}`
    axios.get(generateUrl(url))
      .then(response => (this.appsarray = response.data))
  },
  data: function () {
    return {
      droptext: '',
      modal: false,
      userid: {
        name: 'Clément',
        groups: ['admin', 'info']
      },
      updating: false,
      oldapparray: {},
      apptoupdate: {},
      appsarray: {
        0: {
          'id': 1,
          'title': 'Annuaire téléphonique',
          'link': 'http://10.200.1.5/annuaire/',
          'icon': 'telephone',
          'fixed': true
        },
        1: {
          'id': 2,
          'title': 'Intervention Informatique',
          'link': 'http://chouette.ca2bm.local/glpi/front/central.php',
          'icon': 'display',
          'fixed': true
        },
        2: {
          'id': 3,
          'title': 'Accès au webmail',
          'link': 'http://chouette.ca2bm.local/roundcube/',
          'icon': 'envelope',
          'fixed': true
        },
        3: {
          'id': 4,
          'title': 'CIVIL RH/GF',
          'link': 'https://civil.ca2bm.local/',
          'icon': 'display',
          'fixed': true
        },
        4: {
          'id': 5,
          'title': 'Astre RH',
          'link': 'http://192.168.1.6:9998/astre',
          'icon': 'collection',
          'fixed': false
        },
        5: {
          'id': 6,
          'title': 'Astre Finances',
          'link': 'http://192.168.1.5:7001/astre/gf',
          'icon': 'calculator',
          'fixed': false
        },
        6: {
          'id': 7,
          'title': 'SIG',
          'link': 'https://sig.ca2bm.fr/intrageo/',
          'icon': 'map',
          'fixed': false
        },
        7: {
          'id': 8,
          'title': 'CartADS',
          'link': 'https://sig.ca2bm.fr/adscs',
          'icon': 'map',
          'fixed': false
        },
        8: {
          'id': 9,
          'title': 'Réseau des Médiathèques ',
          'link': 'https://bibbercktse.opale-sud.local/RDWeb',
          'icon': 'book',
          'fixed': false
        },
        9: {
          'id': 10,
          'title': 'Marcoweb',
          'link': 'http://zebre.ca2bm.local/',
          'icon': 'journal',
          'fixed': false
        }
      },
      selected: null,
      options: [
        { value: null, text: 'Please select an option' },
        { value: 'a', text: 'This is First option' },
        { value: 'b', text: 'Selected Option' },
        { value: { C: '3PO' }, text: 'This is an option with object value' },
        { value: 'd', text: 'This one is disabled', disabled: true }
      ]
    }
  }
}
</script>
