<template>
  <div id="news-frame">
    <div id="news-container" class="news-container">
      <div id="news-row" class="news-row" :focus="newfocus">
        <div class="news-alerts" v-intro="'Ici vous seront partagé les informations temporaires'">
          <div class="alert-header">
            <h2 class="alert-header-title" style="border-top: solid 2px var(--color-secondary);">
              Alertes
              <alert-admin-create v-if="isAdmin" />
            </h2>
          </div>
          <div class="alert-wrapper">
            <div :id="'alert'+index" v-for="(a,index) in getAlerts" :key="index">
              <alert :alert="alerts[index]" />
            </div>
            <div class="alert-empty" v-if="Empty_Alerts != ''">{{Empty_Alerts}}</div>
          </div>
        </div>
        <div class="news-block" v-if="isAdmin" v-intro="'Ici vous seront partagé les actualités'">
          <div class="news-header">
            <h2
              class="news-header-title"
              style="border-top: 2px solid var(--color-primary-category);"
            >
              Actualités
              <news-admin-create />
            </h2>
            <input
              type="text"
              class="searchbar"
              v-model="search"
              placeholder="Rechercher.."
              v-intro="'Vous pouvez rechercher des actualités et alertes'"
            />
            <button id="news-filtres" v-intro="'ainsi qu\'utiliser divers filtres ici'">
              <b-icon icon="filter"></b-icon>
            </button>
            <NewsFiltrage :minDate="minDate" />
          </div>
          <div class="news-wrapper">
            <news-admin
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
            v-model="currentNewsPage"
            pills
            :total-rows="rows"
            :per-page="2"
          ></b-pagination>
        </div>
        <div class="news-block" v-else>
          <div class="news-header">
            <h2
              class="news-header-title"
              style="border-top: 2px solid var(--color-primary-category);"
            >Actualités</h2>
            <input type="text" class="searchbar" v-model="search" placeholder="Rechercher.." />
            <button id="news-filtres">
              <b-icon icon="filter"></b-icon>
            </button>
            <NewsFiltrage :minDate="minDate" />
          </div>
          <div class="news-wrapper">
            <news-comp
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
            v-model="currentNewsPage"
            pills
            :total-rows="rows"
            :per-page="2"
          ></b-pagination>
        </div>
      </div>
    </div>
    <Apps class="Apps" />
  </div>
</template>

<script>
import NewsAdmin from './news/NewsAdmin'
import NewsComp from './news/NewsComp'
import Alert from './news/Alert'
import Apps from './Apps'
import NewsAdminCreate from './news/NewsAdminCreate'
import AlertAdminCreate from './news/AlertAdminCreate'
import NewsFiltrage from './news/NewsFiltrage'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'News',
  props: {
    msg: String
  },
  components: {
    NewsAdmin,
    NewsComp,
    Alert,
    Apps,
    NewsAdminCreate,
    AlertAdminCreate,
    NewsFiltrage
  },
  watch: {
    updating: function (val) {
      if (val) {
        var url = `apps/intranetagglo/news/${this.currentNewsPage - 1}`
        axios.post(generateUrl(url), { 'id': (this.currentNewsPage - 1) * this.newsperpage, 'limit': this.newsperpage, 'search': this.search, 'categories': this.categoryfilter.join(';'), dateFilter: this.dateFilter }, { type: 'application/json' })
          .then((response) => {
            this.news = response.data[0];
            this.rows = response.data[1];
            this.minDate = response.data[2].time;
            this.$store.commit('setNewsUpdating', false)
          })
        url = `apps/intranetagglo/alerts`
        axios.post(generateUrl(url), { 'search': this.search }, { type: 'application/json' })
          .then((response) => {
            this.alerts = response.data;
          })
      }
    },
  },
  data: function () {
    return {
      addNews: false,
      AddNewsModal: false,
      news: [],
      alerts: [],
      newsPage: 1,
      alertsPage: 1,
      newsperpage: 2,
      rows: 0,
      focus: "",
      timer: undefined,
      minDate: 0
    }
  },
  computed: {
    newfocus() {
      return this.$store.state.newsfocus;
    },
    updating() {
      return this.$store.state.newsupdating
    },
    getNews() {
      return this.news
    },
    getAlerts() {
      return this.alerts
    },
    Empty_Alerts() {
      return this.alerts.length > 0 ? "" : "pas de nouvelle alerte"
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
    dateFilter: {
      get() {
        return this.$store.state.datefilter
      },
    },
    currentNewsPage: {
      get() {
        return this.newsPage
      },
      set(value) {
        this.newsPage = value
        this.$store.commit('setNewsUpdating', true)
      }
    }
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
      this.$store.commit('updateNewsFocus', 2)
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
        }
      })
    this.$store.dispatch("getCategoryOptions", '');
    axios.get(generateUrl(`apps/intranetagglo/user`)).then(response => {
      this.$store.commit('setUser', response.data)
    })
    var url = `apps/intranetagglo/news/${this.currentNewsPage - 1}`
    axios.post(generateUrl(url), { 'id': 0, 'limit': this.newsperpage, 'search': this.search, 'categories': '', dateFilter: this.dateFilter }, { type: 'application/json' })
      .then((response) => {
        this.news = response.data[0];
        this.rows = response.data[1];
        this.minDate = response.data[2].time;
      })
    url = `apps/intranetagglo/alerts`
    axios.post(generateUrl(url), { 'search': this.search }, { type: 'application/json' })
      .then((response) => {
        this.news = response.data;
      })
  },
  destroyed() {
    var target = document.getElementById('news-container');
    target.removeEventListener('scroll', this.handleScroll);
  }
}
</script>


<style scoped>
.alert-header-title {
  width: 100%;
  margin: 0;
  display: flex;
  align-items: center;
  color: var(--color-mode-contrast-1);
  justify-content: center;
  text-align: center;
  background: url("../patterns/brilliant.png"), var(--color-mode-2);
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  box-shadow: 2px 2px 3px rgb(55 84 170 / 15%), 2px 2px 3px rgb(55 84 170 / 15%),
    7px 7px 15px rgb(55 84 170 / 15%), -7px -7px 20px rgb(0 0 0 / 10%);
}

.news-header,
.alert-header {
  display: flex;
  height: 60px;
}

.news-alerts {
  display: flex;
  flex-direction: column;
  height: 100%;
  z-index: 2;
  flex: 0 0 32%;
  margin-right: 1%;
  background-color: var(--color-secondary-transparency) !important;
  border-radius: 10px;
}
.alert-wrapper {
  height: calc(100% - 62px);
  overflow-y: auto;
}

.alert-empty {
  color: var(--color-light);
  text-align: center;
  margin-top: 20px;
}

.searchbar {
  flex: 0 0 39%;
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
  height: 50px !important;
  margin: 0 !important;
  font-size: 16px !important;
}

@media (max-width: 1100px) {
  .news-alerts {
    display: none;
    margin: 0 2%;
  }
  .news-wrapper {
    margin: 0 2%;
  }
  .news-pagination {
    margin: 0 2%;
  }
  .searchbar {
    margin-left: 2% !important;
  }
  .alert-wrapper {
    height: 100%;
    overflow-y: auto;
  }
}
@media (max-width: 780px) {
  .searchbar {
    margin-left: 0 !important;
  }
}
</style>