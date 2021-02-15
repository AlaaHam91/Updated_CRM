import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n";
import promise from "promise";
// import moment from "moment";
// APIs
import api from "@/services/api/ticket/write-report.api";
import finishApi from "@/services/api/control-panel/finish-type.api.js";
import actionApi from "@/services/api/control-panel/required-action.api.js";
import Vue from "vue";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("tickets"),
        disabled: false
      },
      {
        text: i18n.t("dailyReport"),
        disabled: false
      }
    ],
    searchHeaders: [
      { name: i18n.t("name"), value: "name" },
      { name: i18n.t("nameL"), value: "nameL" }
    ],
    typeItems: [
      { label: i18n.t("todo"), value: "todo" },
      { label: i18n.t("createTicket"), value: "createTicket" }
    ],
    todoHeaders: [
      { text: i18n.t("code"), value: "code" },
      { text: i18n.t("company"), value: "company" },
      { text: i18n.t("requestBy"), value: "requestBy" },
      { text: i18n.t("orderType"), value: "ordertype" },
      { text: i18n.t("title"), value: "title" },
      { text: i18n.t("date"), value: "ticket_date" },
      { text: i18n.t("appointmentDate"), value: "appointment_date" },
      { text: i18n.t("appointmentTime"), value: "appointment_time" }
    ],
    messages: [],
    // items
    executionStatusitems: [],
    executionCaseitems: [],
    endTypeItems: [
      { value: "Opened", label: i18n.t("opened") },
      { value: "Schedule", label: i18n.t("schedule") },
      { value: "Finish", label: i18n.t("finish") }
    ],
    indexTypeItems: [
      { label: i18n.t("company"), value: "company" },
      { label: i18n.t("contact"), value: "contact" },
      { label: i18n.t("phonebook"), value: "pBook" },
      { label: i18n.t("potentialAgent"), value: "pAgent" },
      { label: i18n.t("agent"), value: "agent" }
    ],
    personApi: [
      {
        api: "people-index/company.api.js",
        funcName: "getPerson",
        payLoad: null
      },
      { api: "people-index/contact.api.js" },
      { api: "people-index/phonebook.api.js" },
      { api: "people-index/potential-agent.api.js" },
      { api: "people-index/agent.api.js" }
    ],
    //form
    id: null,
    type: "todo",
    indexType: "company",
    company: null,
    person: null,
    ticket: null,
    date: null,
    fromTime: null,
    toTime: null,
    executionStatus: null,
    executionCase: null,
    actionReport: null,
    endType: null,
    actualValue: null,
    filesNames: []
  },

  getters: {
    getField
  },
  mutations: {
    updateField,
    messages(state, data) {
      state.messages = data.messages;
    }
  },

  actions: {
    async loadItem() {
      //   await dispatch("reset");
    },
    async reset({ state }) {
      state.id = 0;
      state.ticket = null;
      state.date = null;
      state.fromTime = null;
      state.toTime = null;
      state.executionStatus = null;
      state.executionCase = null;
      state.actionReport = null;
      state.endType = null;
      state.filesNames = [];
      state.messages = [];
    },
    async loadTheInitial({ state }) {
      //execuation case
      finishApi.getAll().then(res => (state.executionCaseitems = res));

      //executionStatusitems
      actionApi.getAll().then(res => (state.executionStatusitems = res));
    },
    async initiateData({ state }) {
      let data = {
        date: state.date,
        execution_status: state.executionStatus,
        time_from: Vue.moment(state.fromTime, "HH:mm").format("HH:mm:ss"),
        time_to: Vue.moment(state.toTime, "HH:mm").format("HH:mm:ss"),
        execution_case: state.executionCase,
        actual_value: state.actualValue,
        action_report: state.actionReport,
        EndType: state.endType
      };
      if (state.type == "todo") data["ticket_id"] = state.ticket;
      else if (state.type == "createTicket") {
        data["issued_for_company"] = state.company;
        data["request_by_c_person"] = state.person.c_person_id;
        data["order_type"] = 1;
        data["area"] = 1;
      }
      state.filesNames.forEach((element, i) => {
        data[`files[${i}]`] = element;
      });

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
          .catch(err => {
            commit("messages", { messages: err.data.message });
            reject();
          });
      });
    }
  }
};
