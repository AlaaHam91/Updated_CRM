import { getField, updateField } from "vuex-map-fields";
import promise from "promise";
// APIs
import api from "@/services/api/auth/profile.api";
import fileApi from "@/services/api/files/file.api";

export default {
  namespaced: true,

  state: {
    messages: [],
    langItems: [],
    type: null,
    //
    image: null,
    newImage: null,
    name: null,
    nameL: null,
    username: null,
    email: null,
    lang: null,
    // modules
    phoneModule: null,
    faxModule: null,
    mobileModule: null,
    emailModule: null,
    addressModule: null,
    noteModule: null,
    // tables items
    phoneItems: [],
    faxItems: [],
    mobileItems: [],
    emailItems: [],
    addressItems: [],
    noteItems: []
  },

  getters: {
    getField
  },

  mutations: {
    updateField,
    messages(state, data) {
      state.messages = data.messages;
    },
    langItems(state, data) {
      state.langItems = data;
    }
  },

  actions: {
    loadImage({ state }, name) {
      fileApi
        .getFile(name)
        .then(res => (state.image = URL.createObjectURL(res.data)));
    },
    async loadItem({ state, dispatch }, data) {
      await dispatch("reset");
      if (data.image[0]) dispatch("loadImage", data.image[0]);
      state.name = data.person_info[0].name.ar;
      state.nameL = data.person_info[0].name.en;
      state.username = data.user_info[0].user_name;
      state.email = data.user_info[0].email;
      state.type = data.user_info.user_type;

      await data.contact_infos.forEach(item => {
        switch (item.type) {
          case "Address":
            state.addressItems.push({
              cm_address: item.cm_address,
              a_d: item.a_d,
              address: item.address_value,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Mobile":
            state.mobileItems.push({
              cm_mobile: item.cm_mobile,
              country: item.country,
              mobile: item.value,
              socialMedia: item.social_media,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Phone":
            state.phoneItems.push({
              cm_phone: item.cm_phone,
              a_d: item.a_d,
              country: item.country,
              phone: item.value,
              extension: item.transfer_no,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Fax":
            state.faxItems.push({
              cm_fax: item.cm_fax,
              a_d: item.a_d,
              country: item.country,
              fax: item.value,
              extension: item.transfer_no,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Email":
            state.emailItems.push({
              cm_email: item.cm_email,
              email: item.value,
              socialMedia: item.social_media,
              contact_info: item.contact_info,
              status: "none"
            });
            break;

          case "Note":
            state.noteItems.push({
              cm_note: item.cm_note,
              note: item.note_value,
              notice: item.declaration,
              contact_info: item.contact_info,
              status: "none"
            });
            break;

          default:
            break;
        }
      });
    },
    async reset({ state, commit, dispatch }) {
      state.image = null;
      state.name = null;
      state.nameL = null;
      state.username = null;
      state.email = null;

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

      if (state.addressModule)
        dispatch(`${state.addressModule}/clearData`, null, { root: true });

      if (state.noteModule)
        dispatch(`${state.noteModule}/clearData`, null, { root: true });
    },
    touchDatatables({ state, commit }) {
      if (state.phoneModule)
        commit(`${state.phoneModule}/touchValidation`, null, { root: true });

      if (state.faxModule)
        commit(`${state.faxModule}/touchValidation`, null, { root: true });

      if (state.mobileModule)
        commit(`${state.mobileModule}/touchValidation`, null, { root: true });

      if (state.emailModule)
        commit(`${state.emailModule}/touchValidation`, null, { root: true });

      if (state.addressModule)
        commit(`${state.addressModule}/touchValidation`, null, {
          root: true
        });

      if (state.noteModule)
        commit(`${state.noteModule}/touchValidation`, null, { root: true });
    },
    loadTheInitial({ dispatch, commit }) {
      dispatch(`cache/lang`, null, { root: true }).then(res =>
        commit("langItems", res)
      );
    },
    loadFromModules({ state, rootGetters }) {
      // populte data table items from store modules

      if (state.phoneModule)
        state.phoneItems = rootGetters[`${state.phoneModule}/items`];

      if (state.faxModule)
        state.faxItems = rootGetters[`${state.faxModule}/items`];

      if (state.mobileModule)
        state.mobileItems = rootGetters[`${state.mobileModule}/items`];

      if (state.emailModule)
        state.emailItems = rootGetters[`${state.emailModule}/items`];

      if (state.addressModule)
        state.addressItems = rootGetters[`${state.addressModule}/items`];

      if (state.noteModule)
        state.noteItems = rootGetters[`${state.noteModule}/items`];
    },
    async initiateData({ state, dispatch }) {
      //load data from addresses, phones, mobiles, notes, ... modules
      await dispatch("loadFromModules");

      let form = new FormData();
      form.append(`name`, state.name);
      form.append(`latin_name`, state.nameL);
      form.append(`user_name`, state.username);
      form.append(`email`, state.email);
      if (state.newImage) form.append(`image`, state.newImage);
      if (state.type !== "App\\crmEmployee" && state.lang)
        form.append("preferred_language", state.lang);

      let i = 0;

      for (let j = 0; j < state.addressItems.length; j++) {
        form.append(
          `contact_infos[${i}][status]`,
          state.addressItems[j].status
        );
        form.append(`contact_infos[${i}][type]`, "Address");
        form.append(`contact_infos[${i}][a_d]`, state.addressItems[j].a_d);
        form.append(
          `contact_infos[${i}][cm_address]`,
          state.addressItems[j].cm_address
        );
        form.append(
          `contact_infos[${i}][address_value]`,
          state.addressItems[j].address
        );
        i++;
      }

      for (let j = 0; j < state.mobileItems.length; j++) {
        form.append(`contact_infos[${i}][status]`, state.mobileItems[j].status);
        form.append(`contact_infos[${i}][type]`, "Mobile");
        form.append(
          `contact_infos[${i}][country]`,
          state.mobileItems[j].country
        );
        form.append(
          `contact_infos[${i}][cm_mobile]`,
          state.mobileItems[j].cm_mobile
        );
        form.append(`contact_infos[${i}][value]`, state.mobileItems[j].mobile);
        if (state.mobileItems[j].socialMedia)
          for (let k = 0; k < state.mobileItems.socialMedia.length; k++)
            form.append(
              `contact_infos[${i}][social_media][${k}]`,
              state.mobileItems.socialMedia[k]
            );
        i++;
      }

      for (let j = 0; j < state.phoneItems.length; j++) {
        form.append(`contact_infos[${i}][status]`, state.phoneItems[j].status);
        form.append(`contact_infos[${i}][type]`, "Phone");
        form.append(`contact_infos[${i}][a_d]`, state.phoneItems[j].a_d);
        form.append(
          `contact_infos[${i}][cm_phone]`,
          state.phoneItems[j].cm_phone
        );
        form.append(`contact_infos[${i}][value]`, state.phoneItems[j].phone);
        form.append(
          `contact_infos[${i}][transfer_no]`,
          state.phoneItems[j].extension
        );
        i++;
      }

      for (let j = 0; j < state.faxItems.length; j++) {
        form.append(`contact_infos[${i}][status]`, state.faxItems[j].status);
        form.append(`contact_infos[${i}][type]`, "Fax");
        form.append(`contact_infos[${i}][a_d]`, state.faxItems[j].a_d);
        form.append(`contact_infos[${i}][cm_fax]`, state.faxItems[j].cm_fax);
        form.append(`contact_infos[${i}][value]`, state.faxItems[j].fax);
        i++;
      }

      for (let j = 0; j < state.emailItems.length; j++) {
        form.append(`contact_infos[${i}][status]`, state.emailItems[j].status);
        form.append(`contact_infos[${i}][type]`, "Email");
        form.append(
          `contact_infos[${i}][cm_email]`,
          state.emailItems[j].cm_email
        );
        form.append(`contact_infos[${i}][value]`, state.emailItems[j].email);
        if (state.emailItems[j].socialMedia)
          for (let k = 0; k < state.emailItems.socialMedia.length; k++)
            form.append(
              `contact_infos[${i}][social_media][${k}]`,
              state.emailItems.socialMedia[k]
            );
        i++;
      }

      for (let j = 0; j < state.noteItems.length; j++) {
        form.append(`contact_infos[${i}][status]`, state.noteItems[j].status);
        form.append(`contact_infos[${i}][type]`, "Note");
        form.append(`contact_infos[${i}][cm_note]`, state.noteItems[j].cm_note);
        form.append(`contact_infos[${i}][note_value]`, state.noteItems[j].note);
        form.append(
          `contact_infos[${i}][declaration]`,
          state.noteItems[j].notice
        );
        i++;
      }

      return form;
    },
    async save({ commit, dispatch }) {
      return new promise(async (resolve, reject) => {
        let form = await dispatch("initiateData");

        api
          .update(form)
          .then(res => {
            commit("messages", {
              messages: Array.isArray(res.data.content)
                ? res.data.content
                : [res.data.content]
            });
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
    load({ dispatch }) {
      api.get().then(res => dispatch("loadItem", res));
    }
  }
};
