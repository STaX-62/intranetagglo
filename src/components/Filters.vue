<template>
  <div id="Filters">
    <div class="Category-filter">
      <h2 class="title">Rechercher</h2>
      <div class="searchbar-container">
        <input type="text" class="searchbar" v-model="search" placeholder="Rechercher.." />
        <!-- <b-dropdown
          :text="droptextc"
          block
          split
          split-variant="outline-dark"
          variant="dark"
          class="Filter"
        >
          <b-dropdown-item v-if="categoryfilter != ''">
            <b-button @click="CategorySet('')">x</b-button>
          </b-dropdown-item>
          <b-dropdown-item
            v-for="(categoryoption,index) in categoryoptions"
            :key="index"
            @click="CategorySet(categoryoption)"
          >{{categoryoption}}</b-dropdown-item>
        </b-dropdown> -->
      </div>
      <!-- <div class="Category" @click="SelectCategory()">Note de service</div>
      <div class="Category">Note d'information</div>
      <div class="Category">Commité d'entreprise</div>
      <div class="Category">Personnel</div>
      <div class="Category"></div>
      <div class="Category"></div>
      <div class="Category">Informatique</div>
      <div class="Category">Divers</div> -->
      <!-- <b-dropdown-item v-if="categoryfilter != ''">
          <b-button @click="CategorySet('')">x</b-button>
        </b-dropdown-item>
        <b-dropdown-item    
          v-for="(categoryoption,index) in categoryoptions"
          :key="index"
          @click="CategorySet(categoryoption)"
        >{{categoryoption}}</b-dropdown-item>
      </b-dropdown>-->
    </div>
  </div>
</template>

<script>
import store from '@/store/index.js'
export default {
  name: 'Filters',
  store: store,
  computed: {
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
    droptextc() {
      if (this.droptext == '') {
        return 'Filtre'
      }
      return this.droptext;
    },
    categoryfilter() {
      return this.$store.state.categoryfilter
    }
  },
  methods: {
    SelectCategory() {

    },
    CategorySet(value) {
      this.droptext = value
      console.log(this.droptext)
      this.$store.commit('updateCategories', value)
    }
  },
  data: function () {
    return {
      image: require('../assets/test.png'), //require('../assets/test.png') //'/nextcloud/apps/intranetca/src/assets/test.png',
      droptext: ''
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.title {
  text-align: center;
  grid-area: Title;
}
.Category-filter {
  width: auto;
  display: grid;
  grid-template-columns: 120px 120px;
  grid-auto-rows: auto 120px 120px 120px 120px 120px;
  grid-auto-flow: dense;
  grid-gap: 15px 15px;
  justify-content: center;
  vertical-align: middle;
  overflow-y: auto;
  overflow-x: hidden;
  width: 100%;
  height: 100%;
  padding-top: 20px;
  padding-bottom: 20px;
  grid-template-areas:
    "Title Title"
    "Searchbar Searchbar"
    ". .";
}
.Category {
  display: flex;
  align-items: center;
  justify-content: center;
  border: solid 2px;
  border-radius: 0.25rem;
  font-weight: 400;
  padding: 0.375rem 0.75rem;
  text-align: center;
  border-color: var(--color-secondary-category);
  color: var(--color-light);
  background-color: var(--color-secondary-category);
  font-size: 0 em;
  transition: 0.5s;
  box-shadow: rgb(0 0 0 / 20%) 0px 3px 1px -2px,
    rgb(0 0 0 / 14%) 0px 2px 2px 0px, rgb(0 0 0 / 12%) 0px 1px 5px 0px;
}
.Category:hover {
  border-color: var(--color-secondary-category);
  background-color: var(--color-secondary-light);
  color: var(--color-dark);
}
.searchbar-container {
  grid-area: Searchbar;
}
.searchbar {
  height: 50px !important;
  width: 90% !important;
  margin-top: 20px !important;
  margin: auto !important;
  border: none !important;
  border: 2px solid var(--color-dark) !important;
  border-radius: 15px !important;
  padding: 0.5rem 1rem !important;
  color: var(--color-dark) !important;
  background-color: #d7d8da5e !important;
  transition: all 0.3s ease !important;
  backdrop-filter: blur(5px) !important;
  box-shadow: rgb(0 0 0 / 20%) 0px 2px 1px -2px,
    rgb(0 0 0 / 14%) 0px 1px 2px 0px, rgb(0 0 0 / 12%) 0px 1px 5px 0px;
}
.searchbar::placeholder {
  color: var(--color-dark) !important;
  font-size: 1em !important;
}
.searchbar:focus {
  color: var(--color-dark) !important;
  outline: none !important;
  border: 2px solid #212529 !important;
  box-shadow: 0 0 10px var(--color-light) !important;
}
</style>