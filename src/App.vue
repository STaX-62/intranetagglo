<template>
  <v-app fixed dark>
    <Navigation>
    </Navigation>
    <v-main>
      <v-row style="margin:0; height: 100%;">
        <Alerts v-if="!alertshidden"></Alerts>
        <News @closealerts="alertshidden = $event"></News>
      </v-row>
    </v-main>
    <Applications></Applications>
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