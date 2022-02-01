import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    search: "",
    user: [],
    categoryfilter: '',
    categoryUpdating: null,
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
    setUser(state, user){
      console.log(user)
      state.user = user;
      console.log(state.user)
      // state.user.push(user)
    }
  },
  modules: {},
});
