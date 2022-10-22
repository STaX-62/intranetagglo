<template>
  <v-app fixed dark>
    <Navigation v-if="isOnSite">
    </Navigation>
    <v-main>
      <v-app-bar elevation="4">
        <v-toolbar-title>Actualités</v-toolbar-title>
        <v-spacer></v-spacer>
        <Searchbar @searchfilters="Filters" :notfound="totalNewsLength == 0"
          data-intro="Vous pouvez rechercher des actualités et alertes grâce à cette barre de recherche (qui basculera automatiquement le mode d'affichage en mode archives)"
          data-title="Barre de Recherche" data-step="4">
        </Searchbar>
        <v-btn :text="$vuetify.theme.dark" class="mr-2 archivesbtn" :admin="$isAdmin"
          @click="archivesMode = !archivesMode; archives = []; $refs.News.lazyArchivesCounter = 0"
          :color="$vuetify.theme.dark ? 'accent' : ''" large
          data-intro="Retrouvez toutes les anciennes actualités de la CA2BM dans la section archives ou cherchez simplement via la barre de recherche"
          data-title="Archives" data-step="6">
          <v-icon class="mr-2" v-if="!archivesMode">mdi-archive</v-icon>
          {{ archivesMode ? 'Retour' : 'Archives' }}
        </v-btn>
        <v-btn class="addbtn" fab small elevation="1" @click="openDialog = 5; newsToUpdate = EmptyNews" v-if="$isAdmin">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-app-bar>

      <v-row style="margin:0; height: 80%;">
        <News ref="News" :alertEmpty="alertEmpty"></News>
        <Alerts v-if="!archivesMode && !alertEmpty" @alertempty="alertEmpty = $event"></Alerts>
      </v-row>
    </v-main>
    <Applications v-if="isOnSite"></Applications>
  </v-app>
</template>

<script>
import Navigation from './components/Navigation.vue';
import Alerts from './components/Alerts.vue';
import News from './components/News.vue';
import Searchbar from './components/Searchbar.vue';
import Applications from './components/Applications.vue';
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router';
import moment from 'moment'

export default {
  name: 'App',
  components: {
    Navigation,
    Alerts,
    News,
    Applications,
    Searchbar
  },
  methods: {
    getLocation() {
      axios.get(generateUrl(`apps/intranetagglo/location`)).then(response => {
        this.isOnSite = response.data
      })
    },
    Filters(search, categories, months) {
      this.archivesMode = true
      this.filters = {
        search: search,
        categories: categories,
        month: months == '' ? months : moment(months).toISOString(),
        nextmonth: months == '' ? months : moment(months).endOf('month').toISOString()
      }
      this.$refs.News.GetNews()
    },
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
    archivesMode: false,
    isOnSite: false,
    alertEmpty: false,
    filters: {
      search: '',
      categories: '',
      month: '',
      nextmonth: ''
    },
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