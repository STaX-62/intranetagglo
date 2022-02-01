<template>
  <div id="news-frame">
    <div id="news-container" class="news-container">
      <div v-bind:class="isAdmin ? 'news-header admin-view' : 'news-header'">
        <h2 class="news-header-title">Actualités</h2>
        <input type="text" class="searchbar" v-model="search" placeholder="Rechercher.." />
        <NewsAdd v-if="isAdmin" />
      </div>
      <div id="news-row" class="news-row">
        <NewsMedium id="news1" v-bind:news="appsarray[0]" />
        <NewsMedium id="news2" v-bind:news="appsarray[1]" />
        <NewsMedium id="news3" v-bind:news="appsarray[2]" />
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

      appsarray: [],
      currentPage: 1,
      rows: 50,
    }
  },
  computed: {
    isAdmin() {
      var user = this.$store.state.user
      console.log(this.$store.state.user)
      console.log(user)
      return user[1].includes('admin')
    },
    categoryoptions() {
      var News = this.$store.state.News
      var CategoryArray = []
      for (var i = 0; i < News.length; i++) {
        CategoryArray.push(News[i].category)
      }
      const uniqueCaterogy = Array.from(new Set(CategoryArray))
      return uniqueCaterogy
    },
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
  mounted() {
    var url = `apps/intranetagglo/news/0`
    axios.get(generateUrl(url))
      .then((response) => {
        this.appsarray = response.data
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
      })
  },
  destroyed() {
    var target = document.getElementById('news-container');
    target.removeEventListener('scroll', this.handleScroll);
  }
}
</script>