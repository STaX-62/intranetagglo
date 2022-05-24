<template>
  <div id="apps">
    <div id="apps-container" class="apps-container">
      <h2 class="apps-header">
        Applications
        <div id="apps-update-btn" v-if="isAdmin">
          <AppsUpdate />
        </div>
      </h2>
      <div class="apps-block">
        <a
          class="apps-content-main"
          v-for="(app,index) in apps"
          :key="index"
          v-bind:href="app.link"
          target="_blank"
        >
          <b-icon class="apps-icon" v-bind:icon="app.icon"></b-icon>
          <div class="apps-title">{{app.title}}</div>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import AppsUpdate from '@/components/apps/apps-update.bdd.vue'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'Apps',
  components: {
    AppsUpdate
  },

  watch: {
    updating: function (val) {
      if (val) {
        var url = `apps/intranetagglo${'/apps'}`
        axios.get(generateUrl(url))
          .then((response) => {
            this.apps = response.data;
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
    UserGroups() {
      return this.$store.state.usergroups
    },
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        this.$store.commit('updateSearch', value)
      }
    },
  },
  methods: {
    AppsSet(value) {
      this.droptext = value
      this.$store.commit('updateCategories', value)
    }
  },
  mounted() {
    axios.get(generateUrl(`apps/intranetagglo${'/appsG'}`))
      .then(response => (this.apps = response.data))
  },
  data: function () {
    return {
      droptext: '',
      modal: false,
      apptoupdate: {},
      apps: []
    }
  }
}
</script>
