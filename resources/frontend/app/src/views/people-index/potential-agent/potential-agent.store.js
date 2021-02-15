import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n";
import promise from "promise";
// APIs
import api from "@/services/api/people-index/potential-agent.api";
import userFieldsApi from "@/services/api/people-settings/additional.api.js";
import fileApi from "@/services/api/files/file.api";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("peopleIndex"),
        disabled: false
      },
      {
        text: i18n.t("potentialAgent"),
        disabled: false
      }
    ],
    tab: null,
    birthDateMenu: false,
    panel: null,
    messages: [],
    additionalFieldsTypes: [],
    touchAdditional: false,
    searchHeaders: [
      { name: i18n.t("name"), value: "name" },
      { name: i18n.t("nameL"), value: "nameL" }
    ],
    // items
    shareWithItems: [],
    interestItems: [],
    jobItems: [],
    activityItems: [],
    contactSourceItems: [],
    acquaintanceMethodItems: [],
    langItems: [],
    // modules
    mobileModule: null,
    emailModule: null,
    phoneModule: null,
    faxModule: null,
    addressModule: null,
    noteModule: null,
    locationModule: null,
    logModule: null,
    // form
    id: null,
    image: null,
    isActive: true,
    isManager: false,
    date: null,
    user: null,
    contactSource: null,
    name: null,
    nameL: null,
    job: null,
    company: null,
    jobTitle: null,
    activity: [],
    interest: [],
    acquaintanceMethod: null,
    acquaintance: null,
    language: null,
    birthDate: null,
    shareWith: [],
    phoneItems: [],
    faxItems: [],
    mobileItems: [],
    emailItems: [],
    addressItems: [],
    noteItems: [],
    locationItems: [],
    logItems: [],
    additionalFieldsValues: []
  },
  getters: {
    getField
  },
  mutations: {
    updateField,
    activityItems(state, data) {
      state.activityItems = data;
    },
    interestItems(state, data) {
      state.interestItems = data;
    },
    jobItems(state, data) {
      state.jobItems = data;
    },
    users(state, data) {
      state.shareWithItems = data;
    },
    messages(state, data) {
      state.messages = data.messages;
    },
    additionalFieldsTypes(state, data) {
      state.additionalFieldsTypes = [];
      data.forEach(element => {
        state.additionalFieldsTypes.push({
          type: element,
          val: null
        });
      });
    },
    tickets(state, data) {
      state.logItems = data;
    },
    contactSourceItems(state, data) {
      state.contactSourceItems = data;
    },
    acquaintanceMethodItems(state, data) {
      state.acquaintanceMethodItems = data;
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
      state.id = data.id;
      if (data.logo) dispatch("loadImage", data.logo);
      state.isActive = data.is_active;
      state.isManager = data.is_responsibility;
      state.date = data.creation_date;
      state.user = data.creation_user;
      state.company = data.company;
      state.contactSource = data.contact_source;
      state.name = data.name;
      state.nameL = data.latin_name;
      state.job = data.job;
      state.jobTitle = data.job_title;
      state.activity = data.activities[0];
      state.interest = data.importances[0];
      state.acquaintanceMethod = data.acquaintance_method;
      state.acquaintance = data.acquaintance;
      state.language = data.preferred_language;
      state.birthDate = data.birth_date;
      state.shareWith = data.share_with[0];

      let additional = data.additional_infos[0];
      if (additional)
        for (const key in additional) {
          let newItem = { type: additional[key].element };
          if (
            ["Select", "CheckGroup", "RadioGroup"].includes(
              additional[key].element.type
            )
          )
            newItem["val"] = parseInt(additional[key]["values"][0]);
          else newItem["val"] = additional[key]["values"][0];
          state.additionalFieldsValues.push(newItem);
        }

      // load contract info
      await data.contact_infos[0].forEach(item => {
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
          case "Position":
            state.locationItems.push({
              cm_position: item.cm_position,
              location: { lat: item.gps_location_x, lng: item.gps_location_y },
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          default:
            break;
        }
      });
    },
    reset({ state, dispatch }) {
      state.id = 0;
      state.image = null;
      state.isActive = true;
      state.isManager = false;
      state.date = null;
      state.user = null;
      state.contactSource = null;
      state.company = null;
      state.name = null;
      state.nameL = null;
      state.job = null;
      state.jobTitle = null;
      state.activity = [];
      state.interest = [];
      state.acquaintanceMethod = null;
      state.acquaintance = null;
      state.language = null;
      state.birthDate = null;
      state.shareWith = [];
      state.messages = [];
      state.phoneItems = [];
      state.faxItems = [];
      state.mobileItems = [];
      state.emailItems = [];
      state.addressItems = [];
      state.noteItems = [];
      state.locationItems = [];
      state.logItems = [];
      state.additionalFieldsValues = [];

      state.touchAdditional = false;
      state.additionalFieldsValues.forEach(element => {
        element.val = null;
      });

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

      if (state.locationModule)
        dispatch(`${state.locationModule}/clearData`, null, { root: true });
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

      if (state.locationModule)
        commit(`${state.locationModule}/touchValidation`, null, { root: true });
    },
    loadTheInitial({ commit, dispatch }) {
      dispatch(`cache/activity`, null, { root: true }).then(res =>
        commit("activityItems", res)
      );

      dispatch(`cache/importance`, null, { root: true }).then(res =>
        commit("interestItems", res)
      );

      dispatch(`cache/user`, null, { root: true }).then(res =>
        commit("users", res)
      );

      dispatch(`cache/job`, null, { root: true }).then(res =>
        commit("jobItems", res)
      );

      dispatch(`cache/contactSource`, null, { root: true }).then(res =>
        commit("contactSourceItems", res)
      );

      dispatch(`cache/acquaintanceMethod`, null, {
        root: true
      }).then(res => commit("acquaintanceMethodItems", res));

      dispatch(`cache/lang`, null, {
        root: true
      }).then(res => commit("langItems", res));

      userFieldsApi
        .getFor("Contact")
        .then(res => commit("additionalFieldsTypes", res));
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

      if (state.locationModule)
        state.locationItems = rootGetters[`${state.locationModule}/items`];
    },
    async initiateData({ state, dispatch }) {
      //load data from addresses, phones, mobiles, notes, ... modules
      await dispatch("loadFromModules");

      let data = {
        name: state.name,
        latin_name: state.nameL,
        birth_date: state.birthDate,
        company: state.company,
        is_active: state.isActive ? 1 : 0,
        is_responsibility: state.isManager ? 1 : 0,
        contact_source: state.contactSource,
        acquaintance_method: state.acquaintanceMethod,
        acquaintance: state.acquaintance,
        preferred_language: state.language,
        job: state.job,
        job_title: state.jobTitle
      };

      if (state.id > 0 && !state.image) data["remove_logo"] = "true";

      for (let j = 0; j < state.shareWith.length; j++)
        data[`share_with[${j}]`] = await state.shareWith[j];

      for (let j = 0; j < state.activity.length; j++)
        data[`activities[${j}]`] = await state.activity[j];

      for (let j = 0; j < state.interest.length; j++)
        data[`importances[${j}]`] = await state.interest[j];

      let i = 0;
      for (let j = 0; j < state.addressItems.length; j++) {
        if (state.addressItems[j].status == "none") continue;

        if (state.addressItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.addressItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.addressItems[j].status;
        data[`contact_infos[${i}][type]`] = "Address";
        data[`contact_infos[${i}][a_d]`] = state.addressItems[j].a_d;
        data[`contact_infos[${i}][cm_address]`] =
          state.addressItems[j].cm_address;
        data[`contact_infos[${i}][address_value]`] =
          state.addressItems[j].address;
        i++;
      }

      for (let j = 0; j < state.mobileItems.length; j++) {
        if (state.mobileItems[j].status == "none") continue;

        if (state.mobileItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.mobileItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.mobileItems[j].status;
        data[`contact_infos[${i}][type]`] = "Mobile";
        data[`contact_infos[${i}][country]`] = state.mobileItems[j].country;
        data[`contact_infos[${i}][cm_mobile]`] = state.mobileItems[j].cm_mobile;
        data[`contact_infos[${i}][value]`] = state.mobileItems[j].mobile;
        if (state.mobileItems[j].socialMedia)
          for (let k = 0; k < state.mobileItems[j].socialMedia.length; k++)
            data[`contact_infos[${i}][social_media][${k}]`] =
              state.mobileItems[j].socialMedia[k];
        i++;
      }
      for (let j = 0; j < state.phoneItems.length; j++) {
        if (state.phoneItems[j].status == "none") continue;

        if (state.phoneItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.phoneItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.phoneItems[j].status;
        data[`contact_infos[${i}][type]`] = "Phone";
        data[`contact_infos[${i}][a_d]`] = state.phoneItems[j].a_d;
        data[`contact_infos[${i}][cm_phone]`] = state.phoneItems[j].cm_phone;
        data[`contact_infos[${i}][value]`] = state.phoneItems[j].phone;
        data[`contact_infos[${i}][transfer_no]`] =
          state.phoneItems[j].extension;
        i++;
      }

      for (let j = 0; j < state.faxItems.length; j++) {
        if (state.faxItems[j].status == "none") continue;

        if (state.faxItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.faxItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.faxItems[j].status;
        data[`contact_infos[${i}][type]`] = "Fax";
        data[`contact_infos[${i}][a_d]`] = state.faxItems[j].a_d;
        data[`contact_infos[${i}][cm_fax]`] = state.faxItems[j].cm_fax;
        data[`contact_infos[${i}][value]`] = state.faxItems[j].fax;
        i++;
      }
      for (let j = 0; j < state.emailItems.length; j++) {
        if (state.emailItems[j].status == "none") continue;

        if (state.emailItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.emailItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.emailItems[j].status;
        data[`contact_infos[${i}][type]`] = "Email";
        data[`contact_infos[${i}][cm_email]`] = state.emailItems[j].cm_email;
        data[`contact_infos[${i}][value]`] = state.emailItems[j].email;
        if (state.emailItems[j].socialMedia)
          for (let k = 0; k < state.emailItems[j].socialMedia.length; k++)
            data[`contact_infos[${i}][social_media][${k}]`] =
              state.emailItems[j].socialMedia[k];
        i++;
      }
      for (let j = 0; j < state.noteItems.length; j++) {
        if (state.noteItems[j].status == "none") continue;

        if (state.noteItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.noteItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.noteItems[j].status;
        data[`contact_infos[${i}][type]`] = "Note";
        data[`contact_infos[${i}][cm_note]`] = state.noteItems[j].cm_note;
        data[`contact_infos[${i}][note_value]`] = state.noteItems[j].note;
        data[`contact_infos[${i}][declaration]`] = state.noteItems[j].notice;
        i++;
      }

      for (let j = 0; j < state.locationItems.length; j++) {
        if (state.locationItems[j].status == "none") continue;

        if (state.locationItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.locationItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.locationItems[j].status;
        data[`contact_infos[${i}][type]`] = "Position";
        data[`contact_infos[${i}][cm_position]`] =
          state.locationItems[j].cm_position;
        data[`contact_infos[${i}][gps_location_x]`] =
          state.locationItems[j].location.lat;
        data[`contact_infos[${i}][gps_location_y]`] =
          state.locationItems[j].location.lng;
        i++;
      }

      let k = 0;
      for (let j = 0; j < state.additionalFieldsValues.length; j++) {
        data[`additional_infos[${k}][id]`] =
          state.additionalFieldsValues[j].type.id;
        data[`additional_infos[${k}][values][0][name]`] =
          state.additionalFieldsValues[j].val;
        data[`additional_infos[${k}][values][0][latin_name]`] =
          state.additionalFieldsValues[j].val;

        k++;
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
            .catch(err => {
              commit("messages", { messages: err.data.message });
              reject();
            });
        else if (state.id > 0)
          api
            .update(state.id, data)
            .then(() => {
              commit("messages", { messages: [] });
              resolve();
            })
            .catch(err => {
              commit("messages", { messages: err.data.message });
              reject();
            });
      });
    },
    delete({ state }) {
      return new promise(async (resolve, reject) => {
        api
          .delete(state.id)
          .then(() => true)
          .catch(() => reject());
      });
    },
    load({ state, commit, dispatch }) {
      api.get(state.id).then(res => dispatch("loadItem", res));
      api.getTickets(state.id).then(res => commit("tickets", res));
    },
    first({ state, dispatch }) {
      api.first(state.id).then(res => dispatch("loadItem", res));
    },
    previous({ state, dispatch }) {
      api.previous(state.id).then(res => dispatch("loadItem", res));
    },
    next({ state, dispatch }) {
      api.next(state.id).then(res => dispatch("loadItem", res));
    },
    last({ state, dispatch }) {
      api.last(state.id).then(res => dispatch("loadItem", res));
    },
    storeImage({ state }) {
      api.image(state.image, state.id).then(() => true);
    }
  }
};
