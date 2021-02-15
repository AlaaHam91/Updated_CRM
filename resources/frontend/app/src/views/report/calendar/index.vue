<template>
  <div>
    <list-card
      :title="$t('calendarReport')"
      :headers="headers"
      :items="items"
      :loading="loading"
      @loadData="load"
      @view-item="viewItem"
      :edit-btn="false"
      :delete-btn="false"
      :view-btn="true"
      :breadcrumbs="breadcrumbs"
      :filters="false"
      :hideToolBar="true"
    >
      <template v-slot:top>
        <v-expansion-panels class="mb-4">
          <v-expansion-panel>
            <v-expansion-panel-header>
              <div>
                <v-icon class="mr-6" color="primary">
                  mdi-filter-outline
                </v-icon>
                {{ $t("filters") }}
              </div>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-row>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('date')"
                  ></div>
                  <v-menu
                    v-model="dateM"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <div class="d-flex">
                        <v-text-field
                          v-model="date"
                          outlined
                          dense
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          clearable
                          hide-details
                        ></v-text-field>
                      </div>
                    </template>
                    <v-date-picker
                      v-model="date"
                      @input="dateM = false"
                      :locale="$i18n.locale"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('dateType')"
                  ></div>
                  <v-select
                    v-model="dateType"
                    :items="dateTypeItems"
                    item-text="label"
                    item-value="value"
                    dense
                    outlined
                    hide-details
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('ticketState')"
                  ></div>
                  <v-autocomplete
                    v-model="ticketState"
                    :items="ticketStateItems"
                    item-value="value"
                    item-text="label"
                    hide-selected
                    chips
                    clearable
                    deletable-chips
                    dense
                    outlined
                    multiple
                    small-chips
                    hide-details
                  ></v-autocomplete>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('requiredActions')"
                  ></div>
                  <v-autocomplete
                    v-model="requiredAction"
                    :items="requiredActionItems"
                    item-value="id"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    hide-selected
                    chips
                    clearable
                    deletable-chips
                    dense
                    outlined
                    multiple
                    small-chips
                    hide-details
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('finishType')"
                  ></div>
                  <v-autocomplete
                    v-model="finishType"
                    :items="finishTypeItems"
                    item-value="id"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    hide-selected
                    chips
                    clearable
                    deletable-chips
                    dense
                    outlined
                    multiple
                    small-chips
                    hide-details
                  ></v-autocomplete>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('employee')"
                  ></div>
                  <v-autocomplete
                    v-model="employee"
                    :items="employeesItems"
                    item-value="id"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    hide-selected
                    chips
                    clearable
                    deletable-chips
                    dense
                    outlined
                    multiple
                    small-chips
                    hide-details
                  ></v-autocomplete>
                </v-col>
              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </template>
    </list-card>
  </div>
</template>

<script>
import listCard from "@/components/list-card";
import api from "@/services/api/ticket/report.api";
import finishApi from "@/services/api/control-panel/finish-type.api.js";
import actionApi from "@/services/api/control-panel/required-action.api.js";
import userApi from "@/services/api/users/user.api";

export default {
  name: "calendar-report",

  components: {
    "list-card": listCard
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("code"),
          value: "code",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("company"),
          value: "company",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("requestBy"),
          value: "requestBy",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("department"),
          value: "department",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("branch"),
          value: "branch",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("orderType"),
          value: "ordertype",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("title"),
          value: "title",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("date"),
          value: "date",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("time"),
          value: "time",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("actions"),
          align: "center",
          value: "actions",
          sortable: false
        }
      ],
      items: [],
      //misc
      loading: false,
      breadcrumbs: [
        { text: this.$t("reports") },
        { text: this.$t("calendarReport") }
      ],
      dateTypeItems: [
        { value: "Day", label: this.$t("day") },
        { value: "Week", label: this.$t("week") }
      ],
      ticketStateItems: [
        { value: "Schedule", label: this.$t("schedule") },
        { value: "UnderProcess", label: this.$t("underProcess") },
        { value: "Finish", label: this.$t("finish") },
        { value: "Closed", label: this.$t("close") }
      ],
      requiredActionItems: [],
      finishTypeItems: [],
      employeesItems: [],
      dateM: false,
      //filters
      date: null,
      dateType: "Week",
      ticketState: [],
      requiredAction: [],
      finishType: [],
      employee: []
    };
  },

  computed: {
    filters() {
      return `${this.date} |
                ${this.ticketState} |
                ${this.requiredAction} |
                ${this.finishType} |
                ${this.employee}`;
    }
  },

  watch: {
    filters() {
      this.load();
    }
  },

  methods: {
    viewItem(item) {
      this.$router.push({ name: "ticket-show", params: { id: item.id } });
    },
    loadTheInitial() {
      finishApi.getAll().then(res => (this.finishTypeItems = res));
      actionApi.getAll().then(res => (this.requiredActionItems = res));
      userApi.getAll().then(res => (this.employeesItems = res));
    },
    load() {
      this.loading = true;

      let url = "";
      let params = [];
      if (this.date) params.push({ param: "date", val: this.date });
      if (this.dateType)
        params.push({ param: "date_filter", val: this.dateType });

      this.ticketState.forEach((element, i) => {
        params.push({ param: `ticket_states[${i}]`, val: element });
      });
      this.requiredAction.forEach((element, i) => {
        params.push({ param: `required_actions[${i}]`, val: element });
      });
      this.finishType.forEach((element, i) => {
        params.push({ param: `finish_types[${i}]`, val: element });
      });
      this.employee.forEach((element, i) => {
        params.push({ param: `employees[${i}]`, val: element });
      });

      params.forEach((element, i) => {
        if (i == 0) url += `?${element.param}=${element.val}`;
        else url += `&${element.param}=${element.val}`;
      });
      this.items = [];
      api
        .getAll(url)
        .then(res => {
          this.items = res;
        })
        .catch(() => {
          //
        })
        .finally(() => (this.loading = false));
    }
  },

  created() {
    this.loadTheInitial();
    this.load();
  }
};
</script>

<style></style>
