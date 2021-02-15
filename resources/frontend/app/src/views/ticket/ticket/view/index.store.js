import { getField, updateField } from "vuex-map-fields";
import i18n from "@/plugins/i18n.js";
import api from "@/services/api/ticket/ticket.api.js";
import requiredActionApi from "@/services/api/control-panel/required-action.api.js";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("tickets"),
        disabled: false
      },
      {
        text: i18n.t("index"),
        disabled: false
      }
    ],
    searchHeaders: [
      { name: i18n.t("name"), value: "name" },
      { name: i18n.t("nameL"), value: "nameL" }
    ],
    filterTypes: [
      {
        labelEn: "==  Equal",
        labelAr: "==  مساوي",
        display: "==",
        value: "EqualTo"
      },
      {
        labelEn: "?  Contain",
        labelAr: "?  يحوي",
        display: "?",
        value: "Contain"
      },
      {
        labelEn: "^  Start with",
        labelAr: "^  يبدأ",
        display: "^",
        value: "StartWith"
      },
      {
        labelEn: "$  End with",
        labelAr: "$  ينتهي",
        display: "$",
        value: "EndWith"
      },
      {
        labelEn: "==  Not equal",
        labelAr: "==  لا يساوي",
        display: "==",
        value: "NotEqualTo"
      },
      {
        labelEn: "<  Less",
        labelAr: "<  أصغر",
        display: "<",
        value: "Less"
      },
      {
        labelEn: "<=  Less or Equal",
        labelAr: "<=  أصغر أو يساوي",
        display: "<=",
        value: "LessOrEqual"
      },
      {
        labelEn: ">  Greater",
        labelAr: "أكبر >",
        display: "<",
        value: "Greater"
      },
      {
        labelEn: ">=  Greater or Equal",
        labelAr: ">=  أكبر أو يساوي",
        display: ">=",
        value: "GreaterOrEqual"
      },
      {
        labelEn: "!^  Not start with",
        labelAr: "!^  لا يبدأ",
        display: "!^",
        value: "NotStartWith"
      }
    ],
    stateItems: [
      { text: i18n.t("opened"), value: "OpenedTicket", icon: "mdi-asterisk" },
      {
        text: i18n.t("redirected"),
        value: "RedirectedTicket",
        icon: "mdi-forward"
      },
      {
        text: i18n.t("escalated"),
        value: "EscalatedTicket",
        icon: "mdi-account-arrow-right-outline"
      },
      {
        text: i18n.t("shared"),
        value: "SharedTicket",
        icon: "mdi-share-variant"
      },
      {
        text: i18n.t("assigned"),
        value: "AssignedTicket",
        icon: "mdi-account-plus-outline"
      },
      {
        text: i18n.t("scheduled"),
        value: "ScheduledTicket",
        icon: "mdi-calendar-today"
      },
      {
        text: i18n.t("underProcess"),
        value: "UnderProcessedTicket",
        icon: "mdi-cog-sync-outline"
      },
      {
        text: i18n.t("waitingForCloseByClient"),
        value: "WaitingClientCloseTicket",
        icon: "mdi-alert-box-outline"
      },
      {
        text: i18n.t("finished"),
        value: "FinishedTicket",
        icon: "mdi-flag-checkered"
      },
      {
        text: i18n.t("partiallyClosed"),
        value: "PartiallyClosedTicket",
        icon: "mdi-flag-outline"
      },
      {
        text: i18n.t("fullyClosed"),
        value: "FullyClosedTicket",
        icon: "mdi-flag"
      }
    ],
    headers: [
      { text: i18n.t("code"), value: "code" },
      { text: i18n.t("company"), value: "company" },
      { text: i18n.t("requestBy"), value: "requestBy" },
      { text: i18n.t("department"), value: "department" },
      { text: i18n.t("branch"), value: "branch" },
      { text: i18n.t("orderType"), value: "ordertype" },
      { text: i18n.t("title"), value: "title" },
      { text: i18n.t("date"), value: "date" },
      { text: i18n.t("time"), value: "time" }
    ],
    dateRangeItems: [
      { text: "4-7", from: 4, to: 7 },
      { text: "6-10", from: 6, to: 10 },
      { text: "11-15", from: 11, to: 15 },
      { text: "16-30", from: 16, to: 30 },
      { text: "30+", from: 31, to: 1000 }
    ],
    executionTypeItems: [],
    ticketState: 0,
    items: [],
    tableHeadersFilter: null,
    //filters
    dateRange: 4,
    user: null,
    employee: null,
    ownTickets: false,
    expectedTime: null,
    expectedTimeFilter: "EqualTo",
    executionDate: null,
    executionDateFilter: "EqualTo",
    executionTime: null,
    executionType: null,
    executionTimeFilter: "EqualTo"
  },

  getters: {
    getField
  },

  mutations: {
    updateField,
    load(state, data) {
      state.items = [];
      data.forEach(item => {
        state.items.push({
          id: item.id,
          code: item.code,
          company:
            i18n.locale == "en" ? item.company.latin_name : item.company.name,
          requestBy:
            i18n.locale == "en"
              ? item.request_by.latin_name
              : item.request_by.name,
          department:
            i18n.locale == "en"
              ? item.department.latin_name
              : item.department.name,
          branch:
            i18n.locale == "en" ? item.branch.latin_name : item.branch.name,
          ordertype:
            i18n.locale == "en"
              ? item.order_type.latin_name
              : item.order_type.name,
          title: item.title,
          date: item.date,
          time: item.time
        });
      });
    }
  },

  actions: {
    load({ state, commit }) {
      let url = state.stateItems[state.ticketState].value;
      //date from , to:
      url += `?date_from=${state.dateRangeItems[state.dateRange].from}`;
      if (state.dateRange < 4)
        url += `&date_to=${state.dateRangeItems[state.dateRange].to}`;
      //table filters:
      if (state.tableHeadersFilter) url += state.tableHeadersFilter;
      //special state filters
      if (state.ticketState == 0) {
        if (state.user) url += `&user=${state.user}`;
        if (state.employee) url += `&employee=${state.employee}`;
        url += `&only_own_tickets=${state.ownTickets ? 1 : 0}`;
      } else if (state.ticketState == 4) {
        if (state.employee) url += `&employee=${state.employee}`;
        if (state.expectedTime) url += `&expected_time=${state.expectedTime}`;
      } else if (state.ticketState == 5) {
        if (state.executionDate)
          url += `&execution_date=${state.executionDate}`;
        if (state.executionDateFilter)
          url += `&execution_date_filter=${state.executionDateFilter}`;
        if (state.executionTime)
          url += `&execution_time=${state.executionTime}`;
        if (state.executionTimeFilter)
          url += `&execution_time_filter=${state.executionTimeFilter}`;
        if (state.executionType)
          url += `&execution_type=${state.executionType}`;
      } else if ([6, 7, 8, 9, 10].includes(state.ticketState)) {
        if (state.user) url += `&user=${state.user}`;
        if (state.employee) url += `&employee=${state.employee}`;
        url += `&is_own_tickets=${state.ownTickets ? 1 : 0}`;
      }
      api
        .get(url)
        .then(res => commit("load", res))
        .catch(() => {
          //
        });
    },
    loadInitial({ state }) {
      requiredActionApi.getAll().then(res => (state.executionTypeItems = res));
    }
  }
};
