import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    search: "",
    username: "",
    usergroups: [],
    categoryfilter: '',
    categoryUpdating: null,
    appsupdating: false
  },
  getters: {
    News_length: state => {
      return state.News.length
    }
  },
  mutations: {
    updateSearch(state, search) {
      state.search = search;
    },
    updateCategories(state, categoryfilter) {
      state.categoryfilter = categoryfilter;
    },
    setUser(state, user) {
      state.username = user[0]
      state.usergroups = user[1]
    },
    setAppsUpdating(state, updating){
      state.appsupdating = updating
    }
  },
  modules: {},
});
