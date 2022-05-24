<template>
  <div id="news-frame">
    <div id="news-container" class="news-container">
      <div v-bind:class="isAdmin ? 'news-header admin-view' : 'news-header'">
        <h2 class="news-header-title">Actualités</h2>
        <input type="text" class="searchbar" v-model="search" placeholder="Rechercher.." />
        <button id="news-filtres">
          <b-icon icon="filter"></b-icon>
        </button>
        <Filtres />
        <NewsAdd v-if="isAdmin" />
      </div>
      <div id="news-row" class="news-row" :focus="newfocus">
        <div class="news-wrapper" v-if="isAdmin">
          <NewsCompAdmin
            :id="'news'+index"
            v-for="(n,index) in getNews"
            :key="index"
            :arrayid="index"
            :news="news[index]"
          />
        </div>
        <div class="news-wrapper" v-else>
          <NewsComp
            :id="'news'+index"
            v-for="(n,index) in getNews"
            :key="index"
            :arrayid="index"
            :news="news[index]"
          />
        </div>
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
import NewsCompAdmin from './news/admin-news.comp'
import NewsComp from './news/news.comp'
import Apps from './Apps'
import NewsAdd from './news/news-add.bdd'
import Filtres from './news/news-filtres.comp.vue'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'News',
  props: {
    msg: String
  },
  components: {
    NewsCompAdmin,
    NewsComp,
    Apps,
    NewsAdd,
    Filtres
  },
  watch: {
    updating: function (val) {
      if (val) {
        var url = `apps/intranetagglo/news/${this.currentPage - 1}`
        axios.post(generateUrl(url), { 'id': (this.currentPage - 1) * 3, 'search': this.search, 'categories': this.categoryfilter.join(';') }, { type: 'application/json' })
          .then((response) => {
            this.news = response.data[0];
            this.rows = response.data[1];
            console.log(response.data)
            this.$store.commit('setNewsUpdating', false)
          })
      }
    },
  },
  data: function () {
    return {
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
      return this.$store.state.isAdmin
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
    categoryfilter: {
      get() {
        return this.$store.state.categoryfilter
      },
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
    this.search = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    axios.get(generateUrl(`apps/intranetagglo/isadmin`))
      .then(response => {
        console.log('isAdmin:', response.data)
        this.$store.commit('setisAdmin', response.data)
        if (response.data) {
          this.$store.dispatch("getGroupsOptions");
          this.$store.dispatch("getCategoryOptions", '');
        }
      })
    axios.get(generateUrl(`apps/intranetagglo/user`)).then(response => {
      this.$store.commit('setUser', response.data)
    })
    var url = `apps/intranetagglo/news/${this.currentPage - 1}`
    axios.post(generateUrl(url), { 'id': 0, 'search': this.search, 'categories': '' }, { type: 'application/json' })
      .then((response) => {
        this.news = response.data[0];
        this.rows = response.data[1];
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
  flex: 0 0 61%;
  border-top-left-radius: 10px !important;
  border-bottom-left-radius: 10px !important;
  border: 2px solid var(--color-mode-4) !important;
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
@media (max-width: 1100px) {
  .searchbar {
    flex: 0 0 95%;
  }
}
</style>