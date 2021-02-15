import { getField, updateField } from "vuex-map-fields";

export default {
  namespaced: true,
  state: {
    drawerOpen: false,
    appLoadingBar: false,
    displayMode: "tabs"
  },
  getters: {
    getField
  },
  mutations: {
    updateField,
    toggleDrawer(state) {
      state.drawerOpen = !state.drawerOpen;
      localStorage.setItem("drawer", state.drawerOpen);
    },
    setDrawer(state, data) {
      state.drawerOpen = data;
    },
    startBar(state) {
      state.appLoadingBar = true;
    },
    stopBar(state) {
      state.appLoadingBar = false;
    }
  },
  actions: {}
};
