<template>
  <div id="apps">
    <div id="apps-container" class="apps-container">
      <h2 class="apps-header">
        Applications
        <div id="apps-update-btn" v-if="isAdmin">
          <AppsUpdate />
        </div>
      </h2>
      <a
        class="apps-content-main"
        v-for="(app,index) in appsToDisplay()"
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
            this.$store.commit('setAppsUpdating', false)
          })
      }
    },
  },
  computed: {
    updating() {
      return this.$store.state.appsupdating
    },
    isAdmin() {
      return this.$store.state.usergroups.includes('admin')
    },
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        this.$store.commit('updateSearch', value)
      }
    },
    appsToDisplay() {
      var DisplayableaApp = this.appsarray;
      for (var y = 0; y < DisplayableaApp.length; y++) {
        var requiredGroups = DisplayableaApp[y].groups.split(';')
        var state = true
        console.log(DisplayableaApp)
        for (var i = 0; i < requiredGroups.length; i++) {
          if (!this.$store.state.user[1].includes(requiredGroups[i])) {
            state = false
          }
          console.log(state)
        }
        if (!state) {
          DisplayableaApp.splice(y, 1)
          console.log(DisplayableaApp)
        }
      }
      return DisplayableaApp
    },
  },
  methods: {
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
      apptoupdate: {},
      appsarray: []
    }
  }
}
</script>
