import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

import ui from "./modules/ui.store.js";
import auth from "./modules/auth.store.js";
import cache from "./modules/cache.store";

const dataState = createPersistedState({
  paths: ["auth"]
  //
});

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    ui,
    auth,
    cache
  },
  plugins: [dataState]
});
