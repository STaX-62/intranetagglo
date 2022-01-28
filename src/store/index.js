import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    search: "",
    // The left navigation pane (categories, settings, etc.)
    appNavigation: {
      // It can be hidden in small browser windows (e.g., on mobile phones)
      visible: true,
      refreshRequired: false,
    },
    categoryfilter: '',
    Menu: null,
    // Loading and saving states to determine which loader icons to show.
    // State of -1 is reserved for News and edit views to be set when the
    // User loads the app at one of these locations and has to wait for an
    // asynchronous News loading.
    loadingNews: 0,
    // This is used if when a News is reloaded in edit or view
    reloadingNews: 0,
    // A News save is in progress
    savingNews: false,
    // Updating the News directory is in progress
    updatingNewsDirectory: false,
    // Category which is being updated (name)
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
    }
  },
  modules: {},
});
