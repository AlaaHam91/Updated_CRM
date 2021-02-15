import axios from "@/plugins/api";
import promise from "promise";
import api from "@/services/api/auth/auth.api.js";
import fileApi from "@/services/api/files/file.api";

export default {
  namespaced: true,
  state: {
    // token: localStorage.getItem("token") ? localStorage.getItem("token") : null,
    token: null,
    permissions: {},
    // permissions: localStorage.getItem("permission")
    //   ? JSON.parse(localStorage.getItem("permission"))
    //   : {},
    user: {
      name: null,
      nameL: null,
      image: null
    }
  },

  getters: {
    isSignedIn(state) {
      return state.token !== null;
    },
    can: state => (section, permission) => {
      if (Object.prototype.hasOwnProperty.call(state.permissions, section))
        return state.permissions[section].indexOf(permission) !== -1;
      else return false;
    }
  },

  mutations: {
    signin(state, data) {
      state.token = data.token;
      state.permissions = data.permission;
    },
    signout(state) {
      state.token = null;
      state.permissions = null;
      state.user = {
        name: null,
        nameL: null,
        image: null
      };
    },
    profile(state, data) {
      state.user.name = data.person_info[0].name.ar;
      state.user.nameL = data.person_info[0].name.en;
    }
  },

  actions: {
    signin(context, credentials) {
      return new promise((resolve, reject) => {
        api
          .signin(credentials.email, credentials.password)
          .then(res => {
            const token = res.data.message.token;
            // localStorage.setItem("token", token);
            // localStorage.setItem("permission", JSON.stringify(res.permission));
            context.commit("signin", res.data.message);
            axios.defaults.headers.common["Authorization"] = "Bearer " + token;
            context.dispatch("getProfile");
            resolve();
          })
          .catch(err => reject(err));
      });
    },
    signout(context) {
      if (context.getters.isSignedIn) {
        return new promise((resolve, reject) => {
          api
            .signout()
            .then(() => {
              resolve();
            })
            .catch(() => {
              reject();
            })
            .finally(() => {
              // localStorage.removeItem("token");
              context.commit("signout");
            });
        });
      }
    },
    getProfile({ commit, dispatch }) {
      api.profile().then(res => {
        commit("profile", res);
        if (res.image[0]) dispatch("loadImage", res.image[0]);
      });
    },
    loadImage({ state }, name) {
      fileApi
        .getFile(name)
        .then(res => (state.user.image = URL.createObjectURL(res.data)));
    }
  }
};
