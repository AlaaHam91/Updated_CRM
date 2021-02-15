import { getField, updateField } from "vuex-map-fields";
import i18n from "./../../../plugins/i18n";
import promise from "promise";
import mixin from "./../../../mixins/compare-items";
// APIs
import api from "../../../services/api/control-panel/communication-method.api";
import adApi from "@/services/api/control-panel/administrative-division.api";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("controlPanel"),
        disabled: false
      },
      {
        text: i18n.t("communicationMethods"),
        disabled: false
      }
    ],
    messages: [],
    // modules
    phoneModule: null,
    faxModule: null,
    mobileModule: null,
    // form
    id: null,
    name: null,
    nameL: null,
    section: null,
    isRequired: false,
    isDefault: false,
    phoneItems: [],
    faxItems: [],
    mobileItems: [],
    addressItems: [],
    newAddressItems: []
  },
  getters: {
    getField
  },
  mutations: {
    updateField,

    messages(state, data) {
      state.messages = data.messages;
    },
    addressItems(state, data) {
      state.addressItems = data;
    }
  },

  actions: {
    async reset({ state, commit, dispatch }) {
      state.id = 0;
      state.name = null;
      state.nameL = null;
      state.section = null;
      state.isRequired = false;
      state.isDefault = false;

      await commit("messages", { messages: [] });

      //reset data tables
      if (state.phoneModule)
        dispatch(`${state.phoneModule}/clearData`, null, { root: true });

      if (state.faxModule)
        dispatch(`${state.faxModule}/clearData`, null, { root: true });

      if (state.mobileModule)
        dispatch(`${state.mobileModule}/clearData`, null, { root: true });

      if (state.emailModule)
        dispatch(`${state.emailModule}/clearData`, null, { root: true });

      if (state.noteModule)
        dispatch(`${state.noteModule}/clearData`, null, { root: true });

      if (state.locationModule)
        dispatch(`${state.locationModule}/clearData`, null, { root: true });

      if (state.websiteModule)
        dispatch(`${state.websiteModule}/clearData`, null, { root: true });
    },
    async loadItem({ state, dispatch }, data) {
      await dispatch("reset");
      state.id = data.id;
      state.name = data.name;
      state.nameL = data.latin_name;
      state.section = data.section;
      state.isRequired = data.is_required == 1 ? true : false;
      state.isDefault = data.is_default == 1 ? true : false;

      switch (data.section) {
        case "Address":
          data.config.forEach(item => {
            state.addressItems.push({
              country: item.country,
              administrativeDivision: {
                ...item.a_d,
                name: item.a_d.name,
                nameL: item.a_d.latin_name
              },
              address_config: item.address_config
            });
          });
          break;

        case "Mobile":
          data.config.forEach(item => {
            state.mobileItems.push({
              country: item.country,
              pattern: item.pattern,
              length: item.number_length,
              prefix: item.prefix,
              config: item.config,
              status: "none"
            });
          });
          break;

        case "Phone":
          data.config.forEach(async item => {
            await adApi
              .getByCountry(item.a_d.id)
              .then(res => {
                state.phoneItems.push({
                  country: item.country,
                  pattern: item.pattern,
                  length: item.number_length,
                  prefix: item.prefix,
                  administrativeDivision: item.a_d.id,
                  config: item.config,
                  status: "none",
                  adOptions: res
                });
              })
              .catch(() => {});
          });
          break;

        case "Fax":
          data.config.forEach(async item => {
            await adApi
              .getByCountry(item.a_d.id)
              .then(res => {
                state.faxItems.push({
                  country: item.country,
                  pattern: item.pattern,
                  length: item.number_length,
                  prefix: item.prefix,
                  administrativeDivision: item.a_d.id,
                  config: item.config,
                  status: "none",
                  adOptions: res
                });
              })
              .catch(() => {});
          });

          break;

        default:
          break;
      }
    },
    touchDatatables({ state, commit }) {
      if (state.phoneModule)
        commit(`${state.phoneModule}/touchValidation`, null, { root: true });

      if (state.faxModule)
        commit(`${state.faxModule}/touchValidation`, null, { root: true });

      if (state.mobileModule)
        commit(`${state.mobileModule}/touchValidation`, null, { root: true });
    },
    loadFromModules({ state, rootGetters }) {
      // populte data-table items from store modules

      if (state.phoneModule)
        state.phoneItems = rootGetters[`${state.phoneModule}/items`];

      if (state.faxModule)
        state.faxItems = rootGetters[`${state.faxModule}/items`];

      if (state.mobileModule)
        state.mobileItems = rootGetters[`${state.mobileModule}/items`];
    },
    async initiateData({ state, dispatch }) {
      //load data from  phones, mobiles, ... modules
      await dispatch("loadFromModules");

      let data = {
        section: state.section,
        name: state.name,
        latin_name: state.nameL,
        is_default: state.isDefault ? 1 : 0,
        is_required: state.isRequired ? 1 : 0
      };
      let i = 0;
      switch (state.section) {
        case "Address":
          for (let j = 0; j < state.addressItems.length; j++) {
            for (
              let k = 0;
              k < state.addressItems[j].address_config.length;
              k++
            ) {
              if (state.addressItems[j].status == "none") continue;

              if (state.addressItems[j].status !== "Add")
                data[`address_config[${i}][cm_address_config]`] =
                  state.addressItems[j].address_config[k].config;
              data[`address_config[${i}][status]`] =
                state.addressItems[j].status;
              data[`address_config[${i}][country]`] =
                state.addressItems[j].country;
              data[`address_config[${i}][a_d]`] =
                state.addressItems[j].administrativeDivision;

              data[`address_config[${i}][name]`] =
                state.addressItems[j].address_config[k].name;
              data[`address_config[${i}][latin_name]`] =
                state.addressItems[j].address_config[k].latin_name;
              data[`address_config[${i}][is_required]`] =
                state.addressItems[j].address_config[k].is_required == true
                  ? 1
                  : 0;
              i++;
            }
          }
          break;
        case "Mobile":
          for (let j = 0; j < state.mobileItems.length; j++) {
            if (state.mobileItems[j].status == "none") continue;

            if (state.mobileItems[j].status !== "Add")
              data[`mobile_configs[${j}][cm_mobile_config]`] =
                state.mobileItems[j].config;
            data[`mobile_configs[${j}][status]`] = state.mobileItems[j].status;
            data[`mobile_configs[${j}][country]`] =
              state.mobileItems[j].country;
            data[`mobile_configs[${j}][pattern]`] =
              state.mobileItems[j].pattern;
            data[`mobile_configs[${j}][number_length]`] =
              state.mobileItems[j].length;
            if (state.mobileItems[j].prefix)
              for (let k = 0; k < state.mobileItems[j].prefix.length; k++)
                data[`mobile_configs[${j}][prefix][${k}][value]`] =
                  typeof state.mobileItems[j].prefix[k] === "object"
                    ? state.mobileItems[j].prefix[k].prefix
                    : state.mobileItems[j].prefix[k];
          }
          break;
        case "Phone":
          for (let j = 0; j < state.phoneItems.length; j++) {
            if (state.phoneItems[j].status == "none") continue;

            if (state.phoneItems[j].status !== "Add")
              data[`phone_configs[${j}][cm_phone_config]`] =
                state.phoneItems[j].config;
            data[`phone_configs[${j}][status]`] = state.phoneItems[j].status;
            data[`phone_configs[${j}][a_d]`] =
              state.phoneItems[j].administrativeDivision;
            data[`phone_configs[${j}][country]`] = state.phoneItems[j].country;
            data[`phone_configs[${j}][pattern]`] = state.phoneItems[j].pattern;
            data[`phone_configs[${j}][number_length]`] =
              state.phoneItems[j].length;
            if (state.phoneItems[j].prefix)
              for (let k = 0; k < state.phoneItems[j].prefix.length; k++)
                data[`phone_configs[${j}][prefix][${k}][value]`] =
                  typeof state.phoneItems[j].prefix[k] === "object"
                    ? state.phoneItems[j].prefix[k].prefix
                    : state.phoneItems[j].prefix[k];
          }
          break;
        case "Fax":
          for (let j = 0; j < state.faxItems.length; j++) {
            if (state.faxItems[j].status == "none") continue;

            if (state.faxItems[j].status !== "Add")
              data[`fax_configs[${j}][cm_fax_config]`] =
                state.faxItems[j].config;
            data[`fax_configs[${j}][status]`] = state.faxItems[j].status;
            data[`fax_configs[${j}][a_d]`] =
              state.faxItems[j].administrativeDivision;
            data[`fax_configs[${j}][country]`] = state.faxItems[j].country;
            data[`fax_configs[${j}][pattern]`] = state.faxItems[j].pattern;
            data[`fax_configs[${j}][number_length]`] = state.faxItems[j].length;
            if (state.faxItems[j].prefix)
              for (let k = 0; k < state.faxItems[j].prefix.length; k++)
                data[`fax_configs[${j}][prefix][${k}][value]`] =
                  typeof state.faxItems[j].prefix[k] === "object"
                    ? state.faxItems[j].prefix[k].prefix
                    : state.faxItems[j].prefix[k];
          }
          break;
        default:
          break;
      }
      return data;
    },
    async save({ state, commit, dispatch }) {
      return new promise(async (resolve, reject) => {
        let data = await dispatch("initiateData");

        if (state.id == 0)
          api
            .create(data)
            .then(() => {
              commit("messages", { messages: [] });
              resolve();
            })
            .catch(res => {
              commit("messages", {
                messages: Array.isArray(res.data.message)
                  ? res.data.message
                  : [res.data.message]
              });
              reject();
            });
        else if (state.id > 0)
          api
            .update(state.id, data)
            .then(() => {
              commit("messages", { messages: [] });
              resolve();
            })
            .catch(res => {
              commit("messages", {
                messages: Array.isArray(res.data.message)
                  ? res.data.message
                  : [res.data.message]
              });
              reject();
            });
      });
    },
    delete({ state }) {
      return new promise(async (resolve, reject) => {
        api
          .delete(state.id)
          .then(() => resolve())
          .catch(() => reject());
      });
    },
    load({ state, dispatch }) {
      api
        .get(state.id)
        .then(res => dispatch("loadItem", res))
        .catch(() => {});
    },
    UpdateAddressItems({ state, commit }) {
      let result = mixin.methods.compareItems(
        state.addressItems,
        state.newAddressItems
      );

      commit("addressItems", result);
    }
  }
};
