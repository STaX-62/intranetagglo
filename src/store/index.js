import Vue from "vue";
import Vuex from "vuex";
import axios from '@nextcloud/axios'
import { generateUrl, generateOcsUrl } from '@nextcloud/router'

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
      console.log(user)
      state.username = user[0]
      state.usergroups = user[1]
      console.log(state.username, state.usergroups)
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
  actions: {
    getGroupsOptions({ commit }) {
      axios.get(generateOcsUrl(`cloud/groups`, 2))
        .then(res => {
          commit('setGroupsOptions', res.data)
        })
    }
  },
  modules: {},
});
