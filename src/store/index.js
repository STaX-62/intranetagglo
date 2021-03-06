import Vue from "vue";
import Vuex from "vuex";
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    search: "",
    username: "",
    usergroups: [],
    userlastlogin: undefined,
    location: false,
    isAdmin: false,
    groupsoptions: [],
    categoryoptions: [],
    categoryfilter: [],
    datefilter: {
      start: "",
      end: ""
    },
    appsupdating: false,
    menuupdating: false,
    newsupdating: false,
    adminMenus: [],
    newsfocus: "",
    darkmode: false
  },
  getters: {
    News_length: state => {
      return state.News.length
    }
  },
  mutations: {
    updateNewsFocus(state, value) {
      if (value == 0) state.newsfocus = 'left';
      if (value == 1) state.newsfocus = 'right';
      if (value == 2) state.newsfocus = '';
    },
    updateAdminMenus(state, adminMenus) {
      state.adminMenus = adminMenus;
    },
    updateSearch(state, search) {
      state.search = search;
    },
    updateCategories(state, categoryfilter) {
      state.newsupdating = true
      state.categoryfilter = categoryfilter;
    },
    updateStartDate(state, date) {
      state.newsupdating = true
      state.datefilter.start = date
    },
    updateEndDate(state, date) {
      state.newsupdating = true
      state.datefilter.end = date
    },
    setUser(state, user) {
      state.username = user[0]
      state.usergroups = user[1]
      state.userlastlogin = user[2]
    },
    setLocation(state, value) {
      state.location = value;
    },
    setDarmode(state, value) {
      state.darkmode = value;
    },
    setisAdmin(state, value) {
      state.isAdmin = value;
    },
    setGroupsOptions(state, groups) {
      state.groupsoptions = groups
    },
    setCategoryOptions(state, category) {
      state.categoryoptions = category.data
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
    getCategoryOptions({ commit }, search) {
      axios.get(generateUrl('apps/intranetagglo/news/category'), {
        params: {
          search: search,
        },
      }).then(res => {
        commit('setCategoryOptions', res)
      })
    },
    getGroupsOptions({ commit }) {
      axios.get(generateUrl('apps/intranetagglo/api/groups'), {
        params: {
          search: '',
        },
      }).then(res => {
        commit('setGroupsOptions', res.data)
      })
    }
  },
  modules: {},
});
