import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n";
import promise from "promise";
// import _ from "lodash";

import api from "@/services/api/people-settings/additional.api";
// import compareItems from "@/mixins/compare-items.vue";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("indexSettings"),
        disabled: false
      },
      {
        text: i18n.t("additionalFields"),
        disabled: false
      }
    ],
    messages: [],
    types: [
      {
        id: 1,
        group: 1,
        type: "Text",
        label: i18n.t("text"),
        icon: "mdi-form-textbox"
      },
      {
        id: 2,
        group: 1,
        type: "Password",
        label: i18n.t("password"),
        icon: "mdi-form-textbox-password"
      },
      {
        id: 3,
        group: 1,
        type: "Email",
        label: i18n.t("email"),
        icon: "mdi-email-outline"
      },
      {
        id: 4,
        group: 1,
        type: "TextArea",
        label: i18n.t("textArea"),
        icon: "mdi-form-textarea"
      },
      {
        id: 5,
        group: 1,
        type: "Date",
        label: i18n.t("date"),
        icon: "mdi-calendar"
      },
      // {
      //   id: 6,
      //   group: 1,
      //   type: "DateTime",
      //   label: i18n.t("datetime"),
      //   icon: "mdi-calendar-clock"
      // },
      {
        id: 7,
        group: 1,
        type: "Time",
        label: i18n.t("time"),
        icon: "mdi-timetable"
      },
      {
        id: 8,
        group: 1,
        type: "H1",
        label: "H1",
        icon: "mdi-label"
      },
      {
        id: 9,
        group: 1,
        type: "H2",
        label: "H2",
        icon: "mdi-label"
      },
      {
        id: 10,
        group: 1,
        type: "H3",
        label: "H3",
        icon: "mdi-label"
      },
      {
        id: 11,
        group: 2,
        type: "Number",
        label: i18n.t("number"),
        icon: "mdi-numeric"
      },
      {
        id: 12,
        group: 3,
        type: "Select",
        label: i18n.t("select"),
        icon: "mdi-form-dropdown"
      },
      {
        id: 13,
        group: 3,
        type: "CheckGroup",
        label: i18n.t("checkbox"),
        icon: "mdi-checkbox-marked-outline"
      },
      {
        id: 14,
        group: 3,
        type: "RadioGroup",
        label: i18n.t("radio"),
        icon: "mdi-radiobox-marked"
      },
      {
        id: 15,
        group: 4,
        type: "FileUploader",
        label: i18n.t("file"),
        icon: "mdi-paperclip"
      }
    ],
    optionsHeaders: [
      {
        text: i18n.t("name"),
        value: "name",
        width: "150px",
        type: "string",
        readonly: false,
        visible: true,
        rules: [{ type: "required", message: i18n.t("required") }]
      },
      {
        text: i18n.t("nameL"),
        value: "nameL",
        width: "150px",
        type: "string",
        readonly: false,
        visible: true,
        rules: [{ type: "required", message: i18n.t("required") }]
      },
      {
        text: i18n.t("defaultVal"),
        value: "isDefault",
        width: "100px",
        type: "checkbox",
        readonly: false,
        visible: true
      }
    ],
    accessHeaders: [
      {
        text: i18n.t("accessType"),
        value: "accessType",
        width: "150px",
        type: "select",
        options: [
          { value: "PhoneBook", label: i18n.t("phonebook") },
          { value: "Company", label: i18n.t("company") },
          { value: "Contact", label: i18n.t("contact") },
          { value: "PotentialAgent", label: i18n.t("potentialAgent") },
          { value: "Agent", label: i18n.t("agent") }
        ],
        itemValue: "value",
        itemText: "label",
        readonly: false,
        visible: true,
        rules: [{ type: "required", message: i18n.t("required") }]
      },
      {
        text: i18n.t("visible"),
        value: "isDisplay",
        width: "100px",
        type: "checkbox",
        readonly: false,
        visible: true,
        rules: [{ type: "required", message: i18n.t("required") }]
      },
      {
        text: i18n.t("isRequired"),
        value: "isRequired",
        width: "100px",
        type: "checkbox",
        readonly: false,
        visible: true
      },
      {
        text: i18n.t("isAlert"),
        value: "isAlert",
        width: "100px",
        type: "checkbox",
        readonly: false,
        visible: true
      },
      {
        text: i18n.t("period"),
        value: "period",
        width: "100px",
        type: "number",
        readonly: false,
        visible: true
      }
    ],
    optionsTableStore: null,
    accessTableStore: null,
    // properties of item
    id: null,
    type: null,
    order: null,
    group: null,
    name: null,
    nameL: null,
    defaultVal: null,
    defaultValL: null,
    maxLength: 10,
    min: 10,
    max: 20,
    step: 1,
    multiSelect: false,
    multiFile: false,
    accessTableItems: [],
    optionsTableItems: []
  },
  getters: {
    getField
  },
  mutations: {
    updateField,
    messages(state, data) {
      state.messages = data.messages;
    },
    loadItem(state, item) {
      state.id = item.id;
      state.order = item.order_number;
      state.group = state.types.find(e => e.type == item.type).group;
      state.icon = state.types.find(e => e.type == item.type).icon;
      state.type = item.type;
      state.name = item.name;
      state.nameL = item.latin_name;

      if (item.config.value) state.defaultVal = item.config.value;
      if (item.config.latin_value) state.defaultValL = item.config.latin_value;
      if (
        [
          "Text",
          "Password",
          "Email",
          "TextArea",
          "Timymce",
          "Quil",
          "Date",
          "DateTime",
          "Time"
        ].includes(item.type)
      )
        if (item.config.max_length) state.maxLength = item.config.max_length;
      if (item.type === "Number") {
        state.min = item.config.min;
        state.max = item.config.max;
        state.step = item.config.step;
      }
      if (["Select", "CheckGroup", "RadioGroup"].includes(item.type))
        state.multiSelect = item.allow_multi_select == 1 ? true : false;
      if (["FileUploader", "FineUploader"].includes(item.type))
        state.multiFile = item.allow_multi_file;
      state.accessTableItems = [];
      item.access.forEach((access, i) => {
        state.accessTableItems[i] = {};
        state.accessTableItems[i]["accessType"] = access.access_type;
        state.accessTableItems[i]["isDisplay"] =
          access.is_display == 1 ? true : false;
        state.accessTableItems[i]["isRequired"] =
          access.required == "Required" ? true : false;
        state.accessTableItems[i]["isAlert"] =
          access.is_alert == 1 ? true : false;
        state.accessTableItems[i]["period"] = access.period;
      });
      if (item.config.options) {
        state.optionsTableItems = [];
        item.config.options.forEach((option, j) => {
          state.optionsTableItems[j] = {};
          state.optionsTableItems[j]["db_id"] = option.id;
          state.optionsTableItems[j]["name"] = option.name;
          state.optionsTableItems[j]["nameL"] = option.latin_name;
          state.optionsTableItems[j]["isDefault"] =
            option.is_default == 1 ? true : false;
        });
      }
    }
  },

  actions: {
    async reset({ state, commit, dispatch }) {
      state.type = null;
      state.order = null;
      state.name = null;
      state.nameL = null;
      state.defaultVal = null;
      state.defaultValL = null;
      state.maxLength = null;
      state.min = 10;
      state.max = 20;
      state.step = 1;
      state.multiSelect = false;
      state.multiFile = false;

      await commit("messages", { messages: [] });

      //reset data tables
      if (state.optionsTableStore)
        dispatch(`${state.optionsTableStore}/clearData`, null, { root: true });
      if (state.accessTableStore)
        dispatch(`${state.accessTableStore}/clearData`, null, { root: true });
    },
    async touchDatatables({ state, commit, dispatch }) {
      await dispatch("loadFromModules");
      if (state.optionsTableStore)
        commit(`${state.optionsTableStore}/touchValidation`, null, {
          root: true
        });
      if (state.accessTableStore)
        commit(`${state.accessTableStore}/touchValidation`, null, {
          root: true
        });
    },
    loadTheInitial() {
      //
    },
    loadFromModules({ state, rootGetters }) {
      if (state.optionsTableStore)
        state.optionsTableItems =
          rootGetters[`${state.optionsTableStore}/items`];
      if (state.accessTableStore)
        state.accessTableItems = rootGetters[`${state.accessTableStore}/items`];
    },
    async initiateData({ state }) {
      let i = 0;
      let data = {};

      if (state.id) data[`additional_infos[${i}][additional_field]`] = state.id;
      if (state.id == 0) data[`additional_infos[${i}][status]`] = "Add";
      else if (state.id > 0) data[`additional_infos[${i}][status]`] = "Update";
      data[`additional_infos[${i}][order_number]`] = state.order;
      data[`additional_infos[${i}][type]`] = state.type;
      data[`additional_infos[${i}][name]`] = state.name;
      data[`additional_infos[${i}][latin_name]`] = state.nameL;
      if (state.defaultVal)
        data[`additional_infos[${i}][default_value]`] = state.defaultVal;

      if (state.defaultValL)
        data[`additional_infos[${i}][latin_default_value]`] = state.defaultValL;
      //access info
      state.accessTableItems.forEach((access, k) => {
        data[`additional_infos[${i}][access][${k}][access_type]`] =
          access.accessType;
        data[
          `additional_infos[${i}][access][${k}][is_display]`
        ] = access.isDisplay ? 1 : 0;
        data[
          `additional_infos[${i}][access][${k}][required]`
        ] = access.isRequired ? "Required" : "NotRequired";
        data[`additional_infos[${i}][access][${k}][is_alert]`] = access.isAlert
          ? 1
          : 0;
        if (access.period)
          data[`additional_infos[${i}][access][${k}][period]`] = access.period;
      });
      //properties
      if (state.group == 1)
        data[`additional_infos[${i}][max_length]`] = state.maxLength;
      else if (state.group == 2) {
        data[`additional_infos[${i}][min]`] = state.min;
        data[`additional_infos[${i}][max]`] = state.max;
        data[`additional_infos[${i}][step]`] = state.step;
      } else if (state.group == 3) {
        data[`additional_infos[${i}][allow_multi_select]`] = state.multiSelect
          ? 1
          : 0;
        //options
        state.optionsTableItems.forEach((option, j) => {
          if (option.status == "Update")
            data[`additional_infos[${i}][options][${j}][id]`] = option.db_id;
          data[`additional_infos[${i}][options][${j}][name]`] = option.name;
          data[`additional_infos[${i}][options][${j}][latin_name]`] =
            option.nameL;
          data[
            `additional_infos[${i}][options][${j}][is_default]`
          ] = option.isDefault ? 1 : 0;
          data[`additional_infos[${i}][options][${j}][status]`] = option.status;
        });
      } else if (state.group == 4) {
        data[`additional_infos[${i}][allow_multi_file]`] = state.multiFile;
      }
      return data;
    },
    async save({ commit, dispatch }) {
      return new promise(async (resolve, reject) => {
        let data = await dispatch("initiateData");

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
      });
    },
    async load({ state, commit, dispatch }) {
      await dispatch("reset");
      await api.get(state.id).then(res => commit("loadItem", res));
    },
    delete({ state }) {
      return new promise(async (resolve, reject) => {
        api
          .delete(state.id)
          .then(() => resolve())
          .catch(() => reject());
      });
    }
  }
};
