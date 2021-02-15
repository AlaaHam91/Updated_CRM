import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n";
// import promise from "promise";
// APIs
import api from "@/services/api/ticket/ticket.api";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("ticket"),
        disabled: false
      }
    ],
    tab: null,
    orderStatusItems: [
      { value: "Normal", label: i18n.t("normal") },
      { value: "Important", label: i18n.t("important") },
      { value: "Urgent", label: i18n.t("urgent") }
    ],
    statusItems: [
      { value: "Open", label: i18n.t("open") },
      { value: "Assign", label: i18n.t("assign") },
      { value: "Schedule", label: i18n.t("schedule") },
      { value: "Redirect", label: i18n.t("redirect") },
      { value: "Share", label: i18n.t("share") },
      { value: "UnderProcess", label: i18n.t("underProcess") },
      { value: "Esclate", label: i18n.t("escalate") },
      { value: "Finish", label: i18n.t("finish") },
      { value: "Close", label: i18n.t("close") }
    ],
    actions: [
      {
        action: "assign",
        name: i18n.t("assign"),
        color: "primary",
        icon: "mdi-account-plus-outline",
        permission: "Can Assign Ticket"
      },
      {
        action: "schedule",
        name: i18n.t("schedule"),
        color: "primary",
        icon: "mdi-calendar-today",
        permission: "Can Schedule Ticket"
      },
      {
        action: "redirect",
        name: i18n.t("redirect"),
        color: "primary",
        icon: "mdi-forward",
        permission: "Can Redirect Ticket"
      },
      {
        action: "finish",
        name: i18n.t("finish"),
        color: "primary",
        icon: "mdi-flag-checkered",
        permission: "Can Finish Ticket"
      },
      {
        action: "escalate",
        name: i18n.t("escalate"),
        color: "primary",
        icon: "mdi-account-arrow-right-outline",
        permission: "Can Escalate Ticket"
      },
      {
        action: "share",
        name: i18n.t("share"),
        color: "primary",
        icon: "mdi-share-variant",
        permission: "Can Share Ticket"
      },
      {
        action: "report",
        name: i18n.t("report"),
        color: "primary",
        icon: "mdi-file-document-edit-outline",
        permission: "Can WriteReport Ticket"
      },
      {
        action: "addNote",
        name: i18n.t("addNote"),
        color: "primary",
        icon: "mdi-sticker-plus-outline",
        permission: "Can AddNote Ticket"
      },
      {
        action: "sendEmail",
        name: i18n.t("sendEmail"),
        color: "primary",
        icon: "mdi-email-outline",
        permission: "Can SendEmail Ticket"
      },
      {
        action: "sendSms",
        name: i18n.t("sendSms"),
        color: "primary",
        icon: "mdi-cellphone-message",
        permission: "Can SendSMS Ticket"
      },
      {
        action: "closeTicket",
        name: i18n.t("closeTicket"),
        color: "primary",
        icon: "mdi-close-outline",
        permission: "Can Close Ticket"
      }
    ],
    allowedOperations: [],
    //form
    id: null,
    code: null,
    company: null,
    status: null,
    orderStatus: null,
    title: null,
    date: null,
    requestedBy: null,
    department: null,
    branch: null,
    orderType: null,
    details: null,
    //contact info items
    phoneItems: [],
    mobileItems: [],
    emailItems: [],
    locationItems: [],
    // modules
    phoneModule: null,
    mobileModule: null,
    emailModule: null,
    locationModule: null
  },

  getters: {
    getField
  },
  mutations: {
    updateField,
    allowedOperations(state, data) {
      state.allowedOperations = data;
    }
  },

  actions: {
    async loadItem({ state }, data) {
      state.code = data.code;
      state.company =
        i18n.locale == "en" ? data.company.latin_name : data.company.name;
      state.status = state.statusItems.find(
        e => e.value === data.current_status
      ).label;
      state.orderStatus = state.orderStatusItems.find(
        e => e.value === data.order_status
      ).label;
      state.title = data.title;
      state.date = data.date;
      state.requestedBy =
        i18n.locale == "en" ? data.request_by.latin_name : data.request_by.name;
      state.department =
        i18n.locale == "en" ? data.department.latin_name : data.department.name;
      state.branch =
        i18n.locale == "en" ? data.branch.latin_name : data.branch.name;
      state.orderType =
        i18n.locale == "en" ? data.order_type.latin_name : data.order_type.name;
      state.details = data.description;

      //   await state.request_by.forEach(item => {
      //     switch (item) {
      //       case "mobiles":
      //         state.mobileItems.push({
      //           cm_mobile: item.cm_mobile,
      //           country: item.country,
      //           mobile: item.value,
      //           socialMedia: item.social_media,
      //           contact_info: item.contact_info,
      //           status: "none"
      //         });
      //         break;
      //       case "telephones":
      //         state.phoneItems.push({
      //           cm_phone: item.cm_phone,
      //           a_d: item.a_d,
      //           country: item.country,
      //           phone: item.value,
      //           extension: item.transfer_no,
      //           contact_info: item.contact_info,
      //           status: "none"
      //         });
      //         break;
      //       case "emails":
      //         state.emailItems.push({
      //           cm_email: item.cm_email,
      //           email: item.value,
      //           socialMedia: item.social_media,
      //           contact_info: item.contact_info,
      //           status: "none"
      //         });
      //         break;
      //       case "locations":
      //         state.locationItems.push({
      //           cm_position: item.cm_position,
      //           location: { lat: item.gps_location_x, lng: item.gps_location_y },
      //           contact_info: item.contact_info,
      //           status: "none"
      //         });
      //         break;

      //       default:
      //         break;
      //     }
      //   });
    },
    loadTheInitial({ state, commit }) {
      api.getAllowedOperations(state.id).then(res => {
        commit("allowedOperations", res);
      });
    },
    load({ state, dispatch }) {
      api.get(state.id).then(res => dispatch("loadItem", res));
    }
  }
};
