<template>
  <div
    id="App"
    :class="darkmode ? 'dark' : ''"
    :pattern="patterns"
    :variant="backgroundColor"
    :isonsite="isOnSite"
  >
    <div id="settings-box" class="settings-box hidden">
      <b-icon id="cog" name="cog" class="cog" icon="gear"></b-icon>
      <label id="cog-label" for="cog" style="position:absolute;">Paramètres</label>
      <b-icon class="helpintra" icon="info-circle" @click="Intro()"></b-icon>
      <input type="checkbox" class="checkbox" id="checkbox" v-model="darkmode" />
      <label for="checkbox" class="label">
        <b-icon icon="moon" style="color: #f1c40f;"></b-icon>
        <b-icon icon="sun" style="color: #f39c12;"></b-icon>
        <div class="ball"></div>
      </label>
    </div>
    <Settings id="Settings" class="Settings hidden" />
    <Navigation class="Navbar" />
    <News />
  </div>
</template>

<script>
// @ is an alias to /src
import Navigation from '@/components/Navigation.vue'
import Settings from '@/components/Settings.vue'
import News from '@/components/News.vue'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import moment from '@nextcloud/moment'

export default {
  name: 'App',
  components: {
    Navigation,
    Settings,
    News
  },
  data() {
    return {
      darkmode: false,
      patterns: '6',
      backgroundColor: '2',
      isOnSite: false
    }
  },
  computed: {
    userlastlogin() {
      return this.$store.state.userlastlogin
    }
  },
  methods: {
    Intro() {
      this.$introJs.start()
    }
  },
  watch: {
    userlastlogin: function (val) {
      if (val) {
        var uploaddate = moment("2022-07-18").utc();
        if (uploaddate > val)
          setTimeout(() => {
            this.$introJs.start()
          }, 3000)

      }
    },
    darkmode: function (val) {
      if (val) {
        localStorage.setItem('intranetagglo_color_scheme', 'dark');
        this.$store.commit('setDarkmode', this.darkmode)
      }
      else {
        localStorage.setItem('intranetagglo_color_scheme', 'light');
        this.$store.commit('setDarkmode', this.darkmode)
      }
    }
  },
  mounted: function () {
    axios.get(generateUrl(`apps/intranetagglo/location`)).then(response => this.isOnSite = response.data)

    document.getElementById('cog').addEventListener("click", () => {
      document.getElementById('Settings').classList.toggle('hidden')
      document.getElementById('apps-container').classList.toggle('hidden')
      document.getElementById('settings-box').classList.toggle('hidden')
    });
  },
  created: function () {
    var current_scheme = localStorage.getItem('intranetagglo_color_scheme');
    var patterns = localStorage.getItem('intranetagglo_patterns');
    var variant = localStorage.getItem('intranetagglo_variant');

    if (current_scheme) {
      if (current_scheme === 'dark') {
        this.darkmode = !this.darkmode;
        this.$store.commit('setDarkmode', this.darkmode)
      }
    }
    if (patterns) {
      this.patterns = patterns;
    }
    if (variant) {
      this.backgroundColor = variant;
    }
  }
}
</script>