<template>
  <div
    id="Home"
    v-bind:class="darkmodestring"
    ref="Home"
    v-bind:pattern="patterns"
    v-bind:variant="backgroundcolor"
  >
    <div id="settings-box" class="settings-box hidden">
      <b-icon id="cog" name="cog" class="cog" icon="gear"></b-icon>
      <label id="cog-label" for="cog" style="position:absolute">Paramètres</label>
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

export default {
  name: 'Home',
  components: {
    Navigation,
    Settings,
    News
  },
  data: function () {
    return {
      darkmodestring: null,
      darkmode: null,
      patterns: '6',
      backgroundcolor: '2'
    }
  },
  watch: {
    darkmode: function (val) {
      if (val) {
        localStorage.setItem('color_scheme', 'dark');
        this.darkmodestring = 'dark'
        this.darkmode = 'dark'
      }
      else {
        localStorage.setItem('color_scheme', 'light');
        this.darkmodestring = ''
      }
    }
  },
  mounted: function () {
    axios.get(generateUrl(`apps/intranetagglo/location`)).then(response => console.log(response))
    document.getElementById('cog').addEventListener("click", () => {
      document.getElementById('Settings').classList.toggle('hidden')
      document.getElementById('apps-container').classList.toggle('hidden')
      document.getElementById('settings-box').classList.toggle('hidden')
    });
  },
  created: function () {
    var current_scheme = localStorage.getItem('color_scheme');
    var patterns = localStorage.getItem('patterns');
    var variant = localStorage.getItem('variant');

    if (current_scheme) {
      if (current_scheme === 'dark') {
        this.darkmodestring = 'dark';
        this.darkmode = true
      }
      else {
        this.darkmodestring = '';
      }
    }
    if (patterns) {
      this.patterns = patterns;
    }
    if (variant) {
      this.backgroundcolor = variant;
    }
  }
}
</script>