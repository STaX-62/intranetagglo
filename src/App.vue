<template>
  <v-app fixed dark>
    <Navigation v-if="isOnSite">
    </Navigation>
    <v-main>
      <v-row style="margin:0; height: 100%;">
        <Alerts v-if="!alertshidden" @alertempty="alertEmpty = $event"></Alerts>
        <News @closealerts="alertshidden = $event" :alertEmpty="alertEmpty"></News>
      </v-row>
    </v-main>
    <Applications v-if="isOnSite"></Applications>
  </v-app>
</template>

<script>
import Navigation from './components/Navigation.vue';
import Alerts from './components/Alerts.vue';
import News from './components/News.vue';
import Applications from './components/Applications.vue';
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router';

export default {
  name: 'App',
  components: {
    Navigation,
    Alerts,
    News,
    Applications
  },
  methods: {
    getLocation() {
      axios.get(generateUrl(`apps/intranetagglo/location`)).then(response => {
        this.isOnSite = response.data
      })
    }
  },
  mounted() {
    this.getLocation()
    var current_scheme = localStorage.getItem('intranetagglo_color_scheme');
    if (current_scheme === 'dark') {
      this.$vuetify.theme.dark = true
    }
    else {
      this.$vuetify.theme.dark = false
    }
    setTimeout(() => {
      if (!localStorage.getItem('intranetagglo_firsttime')) {
        localStorage.setItem('intranetagglo_firsttime', true);
        this.$introJs.start()
      }
    }, 2000)
  },
  data: () => ({
    alertshidden: false,
    isOnSite: false,
    alertEmpty: false
  }),
};
</script>
<style>
.maincol {
  height: 100%;
}

@media (min-width: 600px) {
  .maincol {
    height: auto;
  }
}

@media (max-width: 694px) {
  #app .v-main__wrap>.row {
    height: auto !important;
  }
}
</style>