import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n";
import promise from "promise";
import _ from "lodash";
// APIs
import api from "@/services/api/people-index/company.api";
import userFieldsApi from "@/services/api/people-settings/additional.api.js";
import fileApi from "../../../services/api/files/file.api";

export default {
  namespaced: true,

  state: {
    breadcrumbs: [
      {
        text: i18n.t("peopleIndex"),
        disabled: false
      },
      {
        text: i18n.t("company"),
        disabled: false
      }
    ],
    tab: null,
    searchHeaders: [
      { text: i18n.t("name"), value: "name" },
      { text: i18n.t("nameL"), value: "latin_name" }
    ],
    langHeaders: [
      { text: i18n.t("name"), value: "name" },
      { text: i18n.t("nameL"), value: "nameL" }
    ],
    activityItems: [],
    shareWithItems: [],
    interestItems: [],
    contactSourceItems: [],
    acquaintanceMethodItems: [],
    langItems: [],
    numberOfEmpsItems: [
      { value: "1-10", label: "1 - 10" },
      { value: "11-50", label: "11 - 50" },
      { value: "51-100", label: "51 - 100" },
      { value: "101-500", label: "101 - 500" },
      { value: "501-1000", label: "501 - 1000" },
      { value: "1001-2500", label: "1001 - 2500" },
      { value: "More2500", label: " > 2500" }
    ],
    types: [
      { value: "Main", label: i18n.t("main") },
      { value: "Branch", label: i18n.t("branch") }
    ],
    messages: [],
    resetDocs: false,
    additionalFieldsTypes: [],
    touchAdditional: false,
    // modules
    phoneModule: null,
    faxModule: null,
    mobileModule: null,
    emailModule: null,
    addressModule: null,
    noteModule: null,
    personModule: null,
    docModule: null,
    logModule: null,
    // form
    id: null,
    image: null,
    type: "Main",
    isActive: true,
    name: null,
    nameL: null,
    contactSource: null,
    date: null,
    user: null,
    parent: null,
    language: null,
    activity: [],
    interest: [],
    acquaintanceMethod: null,
    acquaintance: null,
    shareWith: [],
    numberOfEmps: null,
    additionalFieldsValues: [],
    // tables items
    phoneItems: [],
    faxItems: [],
    mobileItems: [],
    emailItems: [],
    addressItems: [],
    noteItems: [],
    personItems: [],
    docItems: [],
    logItems: [],
    archivesItems: []
  },

  getters: {
    getField
  },

  mutations: {
    updateField,
    SET_ACTIVITY_ITEMS(state, data) {
      state.activityItems = data;
    },
    SET_INTERESET_ITEMS(state, data) {
      state.interestItems = data;
    },
    SET_USERS(state, data) {
      state.shareWithItems = data;
    },
    SET_MESSAGES(state, data) {
      state.messages = data.messages;
    },
    SET_ADDITIONAL_TYPES(state, data) {
      state.additionalFieldsTypes = [];
      data.forEach(element => {
        state.additionalFieldsTypes.push({
          type: element,
          val: null
        });
      });
    },
    SET_TICKETS(state, data) {
      state.logItems = data;
    },
    SET_DOCS(state, data) {
      state.docItems = [];
      let items = [];
      data.forEach(item => {
        let doc = {
          document: item.document,
          number: item.number,
          document_source: item.document_source,
          start_date: item.start_date,
          end_date: item.end_date,
          note: item.notes,
          files: []
        };
        if (item.files)
          item.files.forEach(file => {
            doc.files.push(file.url);
          });

        items.push(doc);
      });
      state.docItems = _.cloneDeep(items);
    },
    SET_CONTACT_SOURCES(state, data) {
      state.contactSourceItems = data;
    },
    SET_ACQUINTANCE_METHODS_ITEMS(state, data) {
      state.acquaintanceMethodItems = data;
    },
    SET_LANG_ITEMS(state, data) {
      state.langItems = data;
    }
  },

  actions: {
    loadImage({ state }, name) {
      fileApi
        .getFile(name)
        .then(res => (state.image = URL.createObjectURL(res.data)));
    },
    async loadItem({ state, dispatch, commit }, data) {
      await dispatch("reset");
      state.id = data.id;
      if (data.logo) dispatch("loadImage", data.logo);
      state.type = data.type;
      state.isActive = data.is_active;
      state.name = data.name;
      state.nameL = data.latin_name;
      state.contactSource = data.contact_source;
      state.parent = data.parent_company;
      state.date = data.creation_date;
      state.user = data.creation_user;
      state.language = data.preferred_language;
      state.activity = data.activities[0];
      state.interest = data.importances[0];
      state.acquaintanceMethod = data.acquaintance_method;
      state.acquaintance = data.acquaintance;
      state.shareWith = data.share_with[0];
      state.numberOfEmps = data.user_count;

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

          default:
            break;
        }
      });
      commit("SET_DOCS", data.documents);
    },
    loadPersonItems({ state }, data) {
      state.personItems = data;
    },
    async reset({ state, commit, dispatch }) {
      state.id = 0;
      state.image = null;
      state.type = "Main";
      state.isActive = true;
      state.name = null;
      state.nameL = null;
      state.contactSource = null;
      state.date = null;
      state.user = null;
      state.parent = null;
      state.language = null;
      state.activity = [];
      state.interest = [];
      state.acquaintanceMethod = null;
      state.acquaintance = null;
      state.shareWith = [];
      state.numberOfEmps = null;
      //
      state.phoneItems = [];
      state.faxItems = [];
      state.mobileItems = [];
      state.emailItems = [];
      state.addressItems = [];
      state.noteItems = [];
      state.personItems = [];
      state.docItems = [];
      state.logItems = [];
      state.archivesItems = [];

      await commit("SET_MESSAGES", { messages: [] });

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

      if (state.docModule)
        dispatch(`${state.docModule}/clearData`, null, { root: true });
      state.resetDocs = true;
      state.resetDocs = false;
      state.touchAdditional = false;
      state.additionalFieldsValues.forEach(element => {
        element.val = null;
      });
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

      if (state.docModule)
        commit(`${state.docModule}/touchValidation`, null, { root: true });

      state.touchAdditional = true;
    },
    loadTheInitial({ state, commit, dispatch }) {
      dispatch(`cache/activity`, null, { root: true }).then(res =>
        commit("SET_ACTIVITY_ITEMS", res)
      );
      dispatch(`cache/importance`, null, { root: true }).then(res =>
        commit("SET_INTERESET_ITEMS", res)
      );
      dispatch(`cache/user`, null, { root: true }).then(res =>
        commit("SET_USERS", res)
      );
      dispatch(`cache/contactSource`, null, { root: true }).then(res =>
        commit("SET_CONTACT_SOURCES", res)
      );
      dispatch(`cache/acquaintanceMethod`, null, { root: true }).then(res =>
        commit("SET_ACQUINTANCE_METHODS_ITEMS", res)
      );
      dispatch(`cache/lang`, null, { root: true }).then(res =>
        commit("SET_LANG_ITEMS", res)
      );

      if (state.id == 0)
        dispatch(`cache/document`, null, { root: true }).then(res => {
          for (let i = 0; i < res.length; i++)
            docs.push({
              document: res[i].id,
              number: null,
              document_source: null,
              start_date: null,
              end_date: null,
              note: null,
              files: []
            });

          commit("SET_DOCS", docs);
        });

      userFieldsApi
        .getFor("Company")
        .then(res => commit("SET_ADDITIONAL_TYPES", res));
      let docs = [];
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

      if (state.docModule)
        state.docItems = rootGetters[`${state.docModule}/items`];
    },
    async initiateData({ state, dispatch }) {
      //load data from addresses, phones, mobiles, notes, ... modules
      await dispatch("loadFromModules");

      let data = {
        name: state.name,
        latin_name: state.nameL,
        company_type: state.type,
        parent_company: state.parent ? state.parent : undefined,
        contact_source: state.contactSource,
        acquaintance_method: state.acquaintanceMethod,
        preferred_language: state.language,
        acquaintance: state.acquaintance,
        user_count: state.numberOfEmps,
        is_active: state.isActive ? 1 : 0
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

      for (let j = 0; j < state.docItems.length; j++) {
        data[`documents[${j}][id]`] = state.docItems[j].document;
        data[`documents[${j}][number]`] = state.docItems[j].number;
        data[`documents[${j}][document_source]`] = state.docItems[j].source;
        data[`documents[${j}][start_date]`] = state.docItems[j].issueDate;
        data[`documents[${j}][end_date]`] = state.docItems[j].expireDate;
        data[`documents[${j}][notes]`] = state.docItems[j].note;

        if (state.docItems[j].files)
          for (let f = 0; f < state.docItems[j].files.length; f++) {
            if (state.docItems[j].status == "Add")
              data[`documents[${j}][file][${f}]`] = state.docItems[j].name;
            else if (state.docItems[j].status == "Delete")
              data[`documents[${j}][remove_files][${f}]`] =
                state.docItems[j].name;
          }
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
              commit("SET_MESSAGES", { messages: [] });
              resolve();
            })
            .catch(res => {
              commit("SET_MESSAGES", {
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
              commit("SET_MESSAGES", { messages: [] });
              resolve();
            })
            .catch(res => {
              commit("SET_MESSAGES", {
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
          .then(() => true)
          .catch(() => reject());
      });
    },
    load({ state, dispatch, commit }) {
      api.get(state.id).then(res => dispatch("loadItem", res));
      api.getPerson(state.id).then(res => dispatch("loadPersonItems", res));
      api.getTickets(state.id).then(res => commit("SET_TICKETS", res));
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
