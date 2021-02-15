import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n";
import promise from "promise";
// APIs
import api from "@/services/api/ticket/ticket.api";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("createTicket"),
        disabled: false
      }
    ],
    tab: null,
    typeItems: [
      { label: i18n.t("company"), value: "company" },
      { label: i18n.t("contact"), value: "contact" },
      { label: i18n.t("phonebook"), value: "pBook" },
      { label: i18n.t("potentialAgent"), value: "pAgent" },
      { label: i18n.t("agent"), value: "agent" }
    ],
    searchHeaders: [
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
    statusItems: [
      { value: "Normal", label: i18n.t("normal") },
      { value: "Important", label: i18n.t("important") },
      { value: "Urgent", label: i18n.t("urgent") }
    ],
    messages: [],
    touchPersonInfo: false,
    invalidPerson: false,
    //form
    personInfo: {
      company: null,
      person: null
    },
    department: null,
    branch: null,
    orderType: 1,
    status: "Normal",
    title: null,
    details: null,
    country: null,
    remoteFiles: [],
    //contact info items
    phoneItems: [],
    faxItems: [],
    mobileItems: [],
    emailItems: [],
    addressItems: [],
    locationItems: [],
    // modules
    phoneModule: null,
    faxModule: null,
    mobileModule: null,
    emailModule: null,
    addressModule: null,
    locationModule: null,
    //by wizard fields
    ticket: null, //id
    activateCode: null,
    //by project fields
    project: null
  },

  getters: {
    getField
  },
  mutations: {
    updateField,
    messages(state, data) {
      state.messages = data.messages;
    },
    ticket(state, id) {
      state.ticket = id;
    }
  },

  actions: {
    async loadContactInfo({ state }) {
      if (state.personInfo.person.contact_info)
        await state.personInfo.person.contact_infos.forEach(item => {
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
            case "Position":
              state.locationItems.push({
                cm_position: item.cm_position,
                location: {
                  lat: item.gps_location_x,
                  lng: item.gps_location_y
                },
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
      state.touchPersonInfo = false;
      state.personInfo = {
        company: null,
        person: null
      };
      state.department = null;
      state.branch = null;
      state.orderType = 1;
      state.status = "Normal";
      state.title = null;
      state.details = null;
      state.country = null;
      state.remoteFiles = [];
      state.ticket = null;
      // by wizard fields
      state.activateCode = null;
      state.ticket = null;
      //by project
      state.project = null;

      await commit("messages", { messages: [] });
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

      if (state.locationModule)
        dispatch(`${state.locationModule}/clearData`, null, { root: true });
    },
    async initiateData({ state }) {
      let data = {
        issued_for_company: state.personInfo.company,
        request_by_c_person: state.personInfo.person.c_person_id,
        issued_for_department: state.department,
        issued_for_branch: state.branch,
        order_type: state.orderType,
        order_status: state.status,
        title: state.title,
        details: state.details,
        area: state.country.id,
        project: state.project ? state.project : undefined
      };

      state.remoteFiles.forEach((element, i) => {
        data[`files[${i}]`] = element.remoteFileName;
      });

      return data;
    },
    async createTicket({ commit, dispatch }) {
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
    },
    async createTicketByWizard({ commit, dispatch }) {
      return new promise(async (resolve, reject) => {
        let data = await dispatch("initiateData");

        api
          .createByWizard(data)
          .then(res => {
            let id = res.data.content.ticket_id;
            commit("ticket", id);

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
    activateTicket({ commit, state }) {
      return new promise(async (resolve, reject) => {
        let data = {
          code: state.activateCode
        };
        api
          .activateTicket(data, state.id)
          .then(res => {
            commit("messages", {
              messages: Array.isArray(res.data.message)
                ? res.data.message
                : [res.data.message]
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
    }
  }
};
