import promise from "promise";
import activityApi from "@/services/api/control-panel/activity.api.js";
import importanceApi from "@/services/api/people-settings/importance.api.js";
import userApi from "@/services/api/users/user.api.js";
import documentApi from "@/services/api/people-settings/document.api";
import jobApi from "@/services/api/users/job.api.js";
import contactSourceApi from "@/services/api/people-settings/contact-source.api";
import acquaintanceMethodApi from "@/services/api/people-settings/acquaintance-method.api";
import langApi from "@/services/api/people-settings/language.api";
import countryApi from "@/services/api/company-data/country.api";
import cmApi from "@/services/api/control-panel/communication-method.api";
import adApi from "@/services/api/control-panel/administrative-division.api.js";
import socialApi from "@/services/api/people-settings/social-media.api.js";

export default {
  namespaced: true,
  state: {
    activity: {
      items: [],
      cached: false
    },
    importance: {
      items: [],
      cached: false
    },
    user: {
      items: [],
      cached: false
    },
    document: {
      items: [],
      cached: false
    },
    job: {
      items: [],
      cached: false
    },
    contactSource: {
      items: [],
      cached: false
    },
    acquaintanceMethod: {
      items: [],
      cached: false
    },
    lang: {
      items: [],
      cached: false
    },
    country: {
      items: [],
      cached: false
    },
    cm: {
      //Communication Methods
      items: [],
      cached: false
    },
    ad: {
      //admin dis
      items: [],
      cached: false
    },
    social: {
      items: [],
      cached: false
    }
  },

  getters: {},

  mutations: {},

  actions: {
    activity({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.activity.cached)
          activityApi
            .getAll()
            .then(res => {
              state.activity.items = res;
              // state.activity.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.activity.items);
      });
    },
    importance({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.activity.cached)
          importanceApi
            .getAll()
            .then(res => {
              state.importance.items = res;
              // state.importance.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.importance.items);
      });
    },
    user({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.activity.cached)
          userApi
            .getAll()
            .then(res => {
              state.user.items = res;
              // state.user.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.user.items);
      });
    },
    document({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.document.cached)
          documentApi
            .getAll()
            .then(res => {
              state.document.items = res;
              // state.document.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.document.items);
      });
    },
    job({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.job.cached)
          jobApi
            .getAll()
            .then(res => {
              state.job.items = res;
              // state.job.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.job.items);
      });
    },
    contactSource({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.contactSource.cached)
          contactSourceApi
            .getAll()
            .then(res => {
              state.contactSource.items = res;
              // state.contactSource.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.contactSource.items);
      });
    },
    acquaintanceMethod({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.acquaintanceMethod.cached)
          acquaintanceMethodApi
            .getAll()
            .then(res => {
              state.acquaintanceMethod.items = res;
              // state.acquaintanceMethod.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.acquaintanceMethod.items);
      });
    },
    lang({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.lang.cached)
          langApi
            .getAll()
            .then(res => {
              state.lang.items = res;
              // state.lang.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.lang.items);
      });
    },
    country({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.country.cached)
          countryApi
            .getAll()
            .then(res => {
              state.country.items = res;
              // state.country.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.country.items);
      });
    },
    cm({ state }, type) {
      return new promise(async (resolve, reject) => {
        if (!type) {
          console.log(
            "%c Error in store/cache: type is required for dispatch cm",
            "background: Brown;"
          );
          reject();
        }
        if (!state.cm.cached)
          cmApi
            .getAll()
            .then(res => {
              state.cm.items = res;
              // state.cm.cached = true;
              resolve(res.filter(e => e.section == type));
            })
            .catch(err => reject(err));
        else resolve(state.cm.items.filter(e => e.section == type));
      });
    },
    ad({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.ad.cached)
          adApi
            .getAll()
            .then(res => {
              state.ad.items = res;
              // state.ad.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.ad.items);
      });
    },
    social({ state }) {
      return new promise(async (resolve, reject) => {
        if (!state.social.cached)
          socialApi
            .getAll()
            .then(res => {
              state.social.items = res;
              // state.social.cached = true;
              resolve(res);
            })
            .catch(err => reject(err));
        else resolve(state.social.items);
      });
    }
  }
};
