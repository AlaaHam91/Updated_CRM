import { getField, updateField } from "vuex-map-fields";
import i18n from "./../../../plugins/i18n";
import promise from "promise";
// APIs
import api from "@/services/api/control-panel/project.api";
import userApi from "@/services/api/users/user.api";
import countryApi from "@/services/api/company-data/country.api";
import branchApi from "@/services/api/company-data/branch.api";
import departmentApi from "@/services/api/company-data/department.api";
import { isEmpty } from "lodash";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("controlPanel"),
        disabled: false
      },
      {
        text: i18n.t("project"),
        disabled: false
      }
    ],
    searchHeaders: [
      { name: i18n.t("name"), value: "name" },
      { name: i18n.t("nameL"), value: "nameL" }
    ],
    companySearchHeaders: [
      { name: i18n.t("name"), value: "name" },
      { name: i18n.t("nameL"), value: "nameL" }
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
    typeItems: [
      { label: i18n.t("company"), value: "company" },
      { label: i18n.t("contact"), value: "contact" },
      { label: i18n.t("phonebook"), value: "pBook" },
      { label: i18n.t("potentialAgent"), value: "pAgent" },
      { label: i18n.t("agent"), value: "agent" }
    ],
    tab: null,
    messages: [],
    branches: [],
    departments: [],
    employees: [],
    countries: [],
    // form
    type: "company",
    company: null,
    person: null, //requestBy
    id: null,
    code: null,
    name: null,
    nameL: null,
    employee: false,
    country: null,
    branch: null,
    department: null,
    location: { lat: null, lng: null }
  },
  getters: {
    getField
  },
  mutations: {
    updateField,
    messages(state, data) {
      state.messages = data.messages;
    },
    branches(state, data) {
      state.branches = data;
    },
    departments(state, data) {
      state.departments = data;
    },
    countries(state, data) {
      state.countries = data;
    },
    employees(state, data) {
      state.employees = data;
    }
  },
  actions: {
    async loadItem({ state, dispatch }, data) {
      await dispatch("reset");
      state.id = data.id;
      state.code = data.code;
      state.name = data.name ? data.name.ar : null;
      state.nameL = data.name ? data.name.en : null;
      state.employee = data.employee_id;
      state.country = data.country_id;
      state.branch = data.branch_id;
      state.department = data.department_id;
      state.location.lat = data.GPS_Location_X;
      state.location.lng = data.GPS_Location_Y;
      state.person = data.company_person_id;
      state.company = data.company_id;
    },
    async reset({ state, commit }) {
      state.id = 0;
      state.code = null;
      state.name = null;
      state.nameL = null;
      state.employee = false;
      state.country = null;
      state.branch = null;
      state.department = null;
      state.location = {};
      state.type = "company";
      state.company = null;
      state.person = null;
      await commit("messages", { messages: [] });
    },
    loadTheInitial({ commit }) {
      userApi.getAll().then(res => {
        commit("employees", res);
      });
      countryApi.getAll().then(res => {
        commit("countries", res);
      });
      branchApi.getAll().then(res => {
        commit("branches", res);
      });
      departmentApi.getAll().then(res => {
        commit("departments", res);
      });
    },
    initiateData({ state }) {
      let data = {
        name: state.name,
        latin_name: state.nameL,
        code: state.code,
        employee_id: state.employee,
        branch_id: state.branch,
        department_id: state.department,
        country_id: state.country,
        GPS_Location_X:
          isEmpty(state.location) == false ? state.location.lat : null,
        GPS_Location_Y:
          isEmpty(state.location) == false ? state.location.lng : null,

        company_person_id: state.person.c_person_id,
        company_id: state.company
      };
      return data;
    },
    async create({ commit, dispatch }) {
      let data = await dispatch("initiateData");

      await api
        .create(data)
        .then(() => {
          commit("messages", { messages: [] });
          return true;
        })
        .catch(err => {
          commit("messages", { messages: err.data.message });
          return false;
        });
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
          .then(() => true)
          .catch(() => reject());
      });
    },
    load({ dispatch, state }) {
      api.get(state.id).then(res => dispatch("loadItem", res));
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
    }
  }
};
