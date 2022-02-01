<template>
  <div id="apps">
    <div id="apps-container" class="apps-container">
      <h2 class="apps-header">
        Applications
        <div id="apps-update-btn" v-if="isAdmin">
          <AppsUpdate v-bind:updating.sync="updating" />
        </div>
      </h2>
      <a
        class="apps-content-main"
        v-for="(app,index) in appsToDisplay"
        :key="index"
        v-bind:href="app.link"
        target="_blank"
      >
        <b-icon class="apps-icon" v-bind:icon="app.icon"></b-icon>
        <div class="apps-title">{{app.title}}</div>
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
  watch: {
    updating: function (val) {
      if (val) {
        var url = `apps/intranetagglo${'/apps'}`
        axios.get(generateUrl(url))
          .then((response) => {
            this.appsarray = response.data;
            this.updating = !val;
          })
      }
    },
  },
  computed: {
    isAdmin() {
      return this.$store.state.user[1].includes('admin')
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
    appsToDisplay() {
      for (var y = 0; y < requiredGroups.length; y++) {
        var requiredGroups = this.appsarray[y].groups.split(';')
        var state = true
        for (var i = 0; i < requiredGroups.length; i++) {
          if (!this.$store.state.user[1].includes(requiredGroups[i])) {
            state = false
          }
        }
        if (!state) {
          this.appsarray.splice(y, 1)
        }
      }
      return this.appsarray
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
      updating: false,
      apptoupdate: {},
      appsarray: []
    }
  }
}
</script>
