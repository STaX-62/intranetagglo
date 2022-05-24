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
    isAdmin: false,
    groupsoptions: [],
    categoryoptions: [],
    categoryfilter: '',
    appsupdating: false,
    menuupdating: false,
    newsupdating: false,
    adminMenus: [],
    newsfocus: ""
  },
  getters: {
    News_length: state => {
      return state.News.length
    }
  },
  mutations: {
    updateNewsFocus(state, value) {
      if (value == 0) state.newsfocus = 'left';
      if (value == 1) state.newsfocus = 'center';
      if (value == 2) state.newsfocus = 'right';
      if (value == 3) state.newsfocus = '';
    },
    updateAdminMenus(state, adminMenus) {
      state.adminMenus = adminMenus;
    },
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
