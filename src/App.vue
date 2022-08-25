<template>
  <v-app fixed dark>
    <Navigation v-if="$isOnSite">
    </Navigation>
    <v-main>
      {{ $isOnSite }}
      <v-row style="margin:0; height: 100%;">
        <Alerts v-if="!alertshidden"></Alerts>
        <News @closealerts="alertshidden = $event"></News>
      </v-row>
    </v-main>
    <Applications v-if="$isOnSite"></Applications>
  </v-app>
</template>

<script>
import Navigation from './components/Navigation.vue';
import Alerts from './components/Alerts.vue';
import News from './components/News.vue';
import Applications from './components/Applications.vue';

export default {
  name: 'App',
  components: {
    Navigation,
    Alerts,
    News,
    Applications
  },
  mounted() {
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
</style>