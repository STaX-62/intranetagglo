<template>
  <div id="news-frame">
    <div id="news-container" class="news-container">
      <div v-bind:class="isAdmin ? 'news-header admin-view' : 'news-header'">
        <h2 class="news-header-title">Actualités</h2>
        <input type="text" class="searchbar" v-model="news2" placeholder="Rechercher.." />
        <NewsAdd v-if="isAdmin" />
      </div>
      <div id="news-row" class="news-row">
        <NewsMedium id="news1" v-bind:news="news[0]" />
        <NewsMedium id="news2" v-bind:news="news[1]" />
        <NewsMedium id="news3" v-bind:news="news[2]" />
        <b-icon class="news-return" icon="arrow-return-left" @click="closeNews()"></b-icon>
      </div>
      <b-pagination class="news-pagination" v-model="currentPage" pills :total-rows="rows"></b-pagination>
    </div>
    <Apps class="Apps" />
  </div>
</template>

<script>
import NewsMedium from './news/news.comp'
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
    NewsMedium,
    Apps,
    NewsAdd
  },
  data: function () {
    return {
      actually: "",
      categorysearch: '',
      maxvisiblenews: 6,
      addNews: false,
      AddNewsModal: false,
      news: [],
      currentPage: 1,
      rows: 50,
    }
  },
  computed: {
    isAdmin() {
      return this.$store.state.usergroups.includes('admin')
    },
    news2() {
      console.log(this.news)
      return this.news
    },
    // categoryoptions() {
    //   var News = this.$store.state.News
    //   var CategoryArray = []
    //   for (var i = 0; i < News.length; i++) {
    //     CategoryArray.push(News[i].category)
    //   }
    //   const uniqueCaterogy = Array.from(new Set(CategoryArray))
    //   return uniqueCaterogy
    // },
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        this.$store.commit('updateSearch', value)
      }
    },
    // droptextc() {
    //   if (this.droptext == '') {
    //     return 'Filtre'
    //   }
    //   return this.droptext;
    // },
    // categoryfilter() {
    //   return this.$store.state.categoryfilter
    // },
    // filteredNews() {
    //   function groupcheck(neededgroups, usergroups) {
    //     var missinggroup = 0
    //     for (var i = 0; i < neededgroups.length; i++) if (!usergroups.includes(neededgroups[i])) missinggroup++;
    //     return missinggroup
    //   }
    //   function Searchedcheck(news, search) {
    //     if (news.title.toLowerCase().includes(search.toLowerCase())) return true
    //     else return false
    //   }
    //   function Categorycheck(category, categoryfilter) {
    //     if (categoryfilter == category || categoryfilter == '') return true
    //     else return false
    //   }

    //   function filter(news, search, category, categoryfilter) {
    //     if (Searchedcheck(news, search) && Categorycheck(category, categoryfilter)) return true
    //     else return false
    //   }

    //   var categoryfilter = this.$store.state.categoryfilter

    //   return News.filter(newsfilter => {
    //     if (!groupcheck(newsfilter.authgroup, this.user.groups)) {
    //       return filter(newsfilter, this.search, newsfilter.category, categoryfilter)
    //     }
    //     else return false
    //   }).slice(0, this.maxvisiblenews)
    // }
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
      let newsrow = document.getElementById('news-row')
      newsrow.classList.toggle(this.actually)
    }

  },
  beforeMount() {
    var url = `apps/intranetagglo/news/0`
    axios.post(generateUrl(url), 0, { type: 'application/json' })
      .then((response) => { this.news = response.data })
  },
  mounted() {
    console.log(this.news)
    var news = document.getElementsByClassName('news');
    let newsrow = document.getElementById('news-row')
    news[0].addEventListener('click', () => {
      if (!newsrow.classList.contains('left')) {
        newsrow.classList.toggle('left')
        this.actually = "left"
      }
    })
    news[1].addEventListener('click', () => {
      if (!newsrow.classList.contains('center')) {
        newsrow.classList.toggle('center')
        this.actually = "center"
      }
    })
    news[2].addEventListener('click', () => {
      if (!newsrow.classList.contains('right')) {
        newsrow.classList.toggle('right')
        this.actually = "right"
      }
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
  border: 3px solid var(--color-mode-2) !important;
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