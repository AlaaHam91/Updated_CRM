<template>
  <div>
    <list-card
      :title="$t('dailyReport')"
      :headers="headers"
      :items="items"
      @create-item="createItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :filters="false"
      :new-btn="$can('Ticket_daily_report', 'Create')"
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
                    class="text-body-1 black--text mb-2"
                    v-text="$t('code')"
                  ></div>
                  <v-text-field
                    v-model="code"
                    outlined
                    dense
                    clearable
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('company')"
                  ></div>
                  <v-text-field
                    v-model="company"
                    outlined
                    dense
                    clearable
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('employee')"
                  ></div>
                  <v-text-field
                    v-model="employee"
                    outlined
                    dense
                    clearable
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('requestBy')"
                  ></div>
                  <v-text-field
                    v-model="requestBy"
                    outlined
                    dense
                    clearable
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
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
                    class="text-body-1 black--text mb-2"
                    v-text="$t('executionStatus')"
                  ></div>
                  <v-select
                    :items="executionStatusitems"
                    v-model="executionStatus"
                    item-value="id"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    outlined
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('fromTime')"
                  ></div>
                  <v-menu
                    ref="fromTimeMenu"
                    v-model="fromTimeMenu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="fromTime"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="fromTime"
                        outlined
                        dense
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        hide-details
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="fromTimeMenu"
                      v-model="fromTime"
                      full-width
                      format="24hr"
                      @click:minute="$refs.fromTimeMenu.save(fromTime)"
                    ></v-time-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('toTime')"
                  ></div>
                  <v-menu
                    ref="toTimeMenu"
                    v-model="toTimeMenu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="toTime"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="toTime"
                        outlined
                        dense
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        hide-details
                      ></v-text-field>
                    </template>
                    <v-time-picker
                      v-if="toTimeMenu"
                      v-model="toTime"
                      full-width
                      format="24hr"
                      @click:minute="$refs.toTimeMenu.save(toTime)"
                    ></v-time-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('executionCase')"
                  ></div>
                  <v-select
                    :items="executionCaseitems"
                    item-value="id"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    v-model="executionCase"
                    outlined
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('actualValue')"
                  ></div>
                  <v-text-field
                    dense
                    outlined
                    v-model="actualValue"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('actionReport')"
                  ></div>
                  <v-textarea
                    v-model="actionReport"
                    dense
                    outlined
                    rows="1"
                  ></v-textarea>
                </v-col>
                <v-col cols="12" md="3">
                  <v-checkbox
                    class="mt-6"
                    v-model="ownTickets"
                    :label="$t('onlyOwnTickets')"
                  ></v-checkbox>
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
import api from "@/services/api/ticket/write-report.api";
import finishApi from "@/services/api/control-panel/finish-type.api.js";
import actionApi from "@/services/api/control-panel/required-action.api.js";

export default {
  name: "daily-report-index",

  components: {
    "list-card": listCard
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("ticket"),
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
          text: this.$t("employee"),
          value: "employee",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("executionStatus"),
          value: "executionStatus",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("executionCase"),
          value: "executionCase",
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
          text: this.$t("progressValue"),
          value: "progress_value",
          align: "center",
          sortable: true,
          width: "200px"
        }
      ],
      items: [],
      //misc
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("tickets")
        },
        {
          text: this.$t("dailyReport")
        }
      ],
      executionCaseitems: [],
      executionStatusitems: [],
      toTimeMenu: false,
      fromTimeMenu: false,
      dateM: false,
      //filters
      code: null,
      company: null,
      employee: null,
      requestBy: null,
      date: null,
      executionStatus: null,
      fromTime: null,
      toTime: null,
      executionCase: null,
      actualValue: null,
      actionReport: null,
      ownTickets: null
    };
  },

  computed: {
    filters() {
      return `${this.code} |
                ${this.company} |
                ${this.employee} |
                ${this.requestBy} |
                ${this.date} |
                ${this.executionStatus} |
                ${this.fromTime} |
                ${this.toTime} |
                ${this.executionCase} |
                ${this.actualValue} |
                ${this.actionReport} |
                ${this.ownTickets} `;
    }
  },

  watch: {
    filters() {
      this.load();
    }
  },

  methods: {
    createItem() {
      this.$router.push({ name: "daily-report-show" });
    },
    loadTheInitial() {
      //execuation case
      finishApi.getAll().then(res => (this.executionCaseitems = res));

      //executionStatusitems
      actionApi.getAll().then(res => (this.executionStatusitems = res));
    },
    load() {
      this.loading = true;

      let url = "";
      let params = [];
      if (this.code) params.push({ param: "code", val: this.code });
      if (this.company) params.push({ param: "company", val: this.company });
      if (this.employee) params.push({ param: "employee", val: this.employee });
      if (this.requestBy)
        params.push({ param: "request_by", val: this.requestBy });
      if (this.date) params.push({ param: "date", val: this.date });
      if (this.executionStatus)
        params.push({ param: "execution_status", val: this.executionStatus });
      if (this.fromTime)
        params.push({ param: "time_from", val: this.fromTime });
      if (this.toTime) params.push({ param: "time_to", val: this.toTime });
      if (this.executionCase)
        params.push({ param: "execution_case", val: this.executionCase });
      if (this.actualValue)
        params.push({ param: "actual_value", val: this.actualValue });
      if (this.actionReport)
        params.push({ param: "action_report", val: this.actionReport });
      if (this.ownTickets)
        params.push({ param: "is_own_tickets", val: this.ownTickets });
      params.forEach((element, i) => {
        if (i == 0) url += `?${element.param}=${element.val}`;
        else url += `&${element.param}=${element.val}`;
      });

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
