<template>
  <div id="news-frame">
    <div id="news-container" class="news-container">
      <div v-bind:class="isAdmin ? 'news-header admin-view' : 'news-header'">
        <h2 class="news-header-title">Actualités</h2>
        <input
          type="text"
          class="searchbar"
          v-model="search"
          placeholder="Rechercher.."
          @change="textSearch()"
        />
        <NewsAdd v-if="isAdmin" />
      </div>
      <div id="news-row" class="news-row" :focus="newfocus">
        <NewsComp
          :id="'news'+index"
          v-for="(n,index) in getNews"
          :key="index"
          :arrayid="index"
          :news="news[index]"
        />
        <b-icon
          class="news-return"
          icon="arrow-return-left"
          @click="closeNews()"
          v-if="newfocus != ''"
        ></b-icon>
      </div>
      <b-pagination
        class="news-pagination"
        v-model="currentPage"
        pills
        :total-rows="rows"
        :per-page="3"
      ></b-pagination>
    </div>
    <Apps class="Apps" />
  </div>
</template>

<script>
import NewsComp from './news/news.comp'
import Apps from './Apps'
import NewsAdd from './news/news-add.bdd'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'News',
  props: {
    msg: String
  },
  components: {
    NewsComp,
    Apps,
    NewsAdd
  },
  watch: {
    updating: function (val) {
      if (val) {
        var url = 'apps/intranetagglo/'
        if (this.$store.state.usergroups.includes('admin')) {
          url += `news/${this.currentPage - 1}`
        }
        else {
          url += `newsG/${this.currentPage - 1}`
        }
        axios.post(generateUrl(url), { 'id': (this.currentPage - 1) * 3, 'search': this.search }, { type: 'application/json' })
          .then((response) => {
            this.news = response.data[0];
            this.rows = response.data[1];
            this.$store.commit('setNewsUpdating', false)
          })
      }
    },
  },
  data: function () {
    return {
      categorysearch: '',
      addNews: false,
      AddNewsModal: false,
      news: [],
      page: 1,
      rows: 0,
      focus: "",
      timer: undefined
    }
  },
  computed: {
    newfocus() {
      console.log('focus change : ' + this.$store.state.newsfocus)
      return this.$store.state.newsfocus;
    },
    updating() {
      return this.$store.state.newsupdating
    },
    getNews() {
      return this.news
    },
    isAdmin() {
      return this.$store.state.usergroups.includes('admin')
    },
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        clearTimeout(this.timer)
        this.timer = setTimeout(() => {
          this.$store.commit('setNewsUpdating', true)
        }, 250)
        this.$store.commit('updateSearch', value)
      }
    },
    currentPage: {
      get() {
        return this.page
      },
      set(value) {
        this.page = value
        this.$store.commit('setNewsUpdating', true)
      }
    },
  },
  methods: {
    handleScroll() {
      const els = document.querySelectorAll('.news')
      setInterval(function () {
      }, 1000)
      els.forEach((el) => {
        const elTop = el.getBoundingClientRect().top
        const elBottom = el.getBoundingClientRect().bottom
        if (elTop >= 0 || elBottom <= 0) {
          this.isActive = false
          el.classList.remove('active')
        } if (elTop <= 300 && elBottom >= 0) {
          this.isActive = true
          el.classList.add('active')
        }
      })
    },
    closeNews() {
      this.$store.commit('updateNewsFocus', 3)
    }
  },
  mounted() {
    axios.get(generateUrl(`apps/intranetagglo/user`))
      .then(response => {
        this.$store.commit('setUser', response.data)
        var url = 'apps/intranetagglo/'
        if (response.data[1].includes('admin')) {
          url += `news/0`
          this.$store.dispatch("getGroupsOptions");
          this.$store.dispatch("getCategoryOptions", '');
        }
        else {
          url += `newsG/0`
        }
        axios.post(generateUrl(url), { 'id': 0, 'search': "" }, { type: 'application/json' })
          .then((response) => {
            this.news = response.data[0];
            this.rows = response.data[1];
            this.$store.commit('setNewsUpdating', false)
          })
      })

  },
  destroyed() {
    var target = document.getElementById('news-container');
    target.removeEventListener('scroll', this.handleScroll);
  }
}
</script>


<style scoped>
.searchbar {
  flex: 0 0 62.333%;
  border-radius: 10px !important;
  border: 2px solid rgba(221, 221, 221, 0.185) !important;
  box-shadow: 2px 2px 3px rgb(55 84 170 / 15%), 2px 2px 3px rgb(55 84 170 / 15%),
    7px 7px 15px rgb(55 84 170 / 15%), -7px -7px 20px rgb(0 0 0 / 10%),
    inset 0px 0px 4px rgb(255 255 255 / 0%),
    inset 7px 7px 15px rgb(55 84 170 / 15%),
    inset -7px -7px 20px rgb(255 255 255), 0px 0px 4px rgb(255 255 255 / 20%) !important;
  background-color: var(--color-mode-2) !important;
  padding: 5px 15px !important;
  outline: none !important;
  color: #535d74 !important;
  height: auto !important;
  margin: 0 !important;
  font-size: 16px !important;
}

.admin-view .searchbar {
  flex: 0 0 50%;
}
</style>