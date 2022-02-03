import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    search: "",
    username: "",
    usergroups: [],
    groupsoptions: [],
    categoryfilter: '',
    appsupdating: false,
    menuupdating: false,
    newsupdating: false
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
    setGroupsOptions(state, groups) {
      state.groupsoptions = groups
    },
    setAppsUpdating(state, updating) {
      state.appsupdating = updating
    },
    setMenuUpdating(state, updating) {
      state.menuupdating = updating
    },
    setNewsUpdating(state, updating) {
      state.newsupdating = updating
    }
  },
  modules: {},
});
