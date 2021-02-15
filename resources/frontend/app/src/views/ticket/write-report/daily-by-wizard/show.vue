<template>
  <card
    :title="$t('dailyReport')"
    :navigate="false"
    :breadcrumbs="breadcrumbs"
    :hideToolBar="true"
  >
    <v-row v-for="(item, i) in messages" :key="i" no-gutters>
      <v-col cols="12" md="4">
        <v-alert
          icon="mdi-information"
          color="red"
          type="error"
          v-text="item"
        ></v-alert>
      </v-col>
    </v-row>

    <v-stepper v-model="step">
      <v-stepper-header>
        <v-stepper-step :complete="step > 1" :step="1">
          {{ $t("requestedBy") }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="step > 2" :step="2">
          {{ $t("details") }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :step="3">
          {{ $t("confirm") }}
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card flat>
            <v-row>
              <v-col cols="12" md="3">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('type')"
                ></div>
                <v-select
                  :items="typeItems"
                  v-model="type"
                  item-value="value"
                  item-text="label"
                  dense
                  outlined
                ></v-select>
              </v-col>

              <v-col cols="12" md="3" v-if="type == 'todo'">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('ticket')"
                ></div>
                <search
                  v-model="ticket"
                  item-text="code"
                  item-value="id"
                  api="ticket/write-report.api.js"
                  funcName="todoList"
                  :headers="todoHeaders"
                  :error-messages="ticketErrors"
                ></search>
              </v-col>
            </v-row>

            <v-row v-if="type == 'createTicket'">
              <v-col cols="12" md="3">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('indexType')"
                ></div>
                <v-select
                  :items="indexTypeItems"
                  v-model="indexType"
                  item-value="value"
                  item-text="label"
                  dense
                  outlined
                ></v-select>
              </v-col>
              <v-col cols="12" md="6" :order="indexType == 'company' ? 1 : 2">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('company')"
                ></div>
                <search
                  v-model="company"
                  :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                  item-value="id"
                  api="people-index/company.api.js"
                  :headers="searchHeaders"
                  :error-messages="companyErrors"
                  :readonly="isCompanyReadonly"
                ></search>
              </v-col>
              <v-col cols="12" md="3" :order="indexType == 'company' ? 2 : 1">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('requestedBy')"
                ></div>
                <div v-show="indexType === 'company'" key="1">
                  <search
                    v-model="person"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :api="personApi[0].api"
                    :funcName="personApi[0].funcName"
                    :funcPayLoad="personApi[0].payLoad"
                    :headers="searchHeaders"
                    :error-messages="personErrors"
                    returnObject
                    :readonly="isPersonReadonly"
                  ></search>
                </div>
                <div v-show="indexType === 'contact'" key="2">
                  <search
                    v-model="person"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :api="personApi[1].api"
                    :headers="searchHeaders"
                    :error-messages="personErrors"
                    returnObject
                  ></search>
                </div>
                <div v-show="indexType === 'pBook'" key="3">
                  <search
                    v-model="person"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :api="personApi[2].api"
                    :headers="searchHeaders"
                    :error-messages="personErrors"
                    returnObject
                  ></search>
                </div>
                <div v-show="indexType === 'pAgent'" key="4">
                  <search
                    v-model="person"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :api="personApi[3].api"
                    :headers="searchHeaders"
                    :error-messages="personErrors"
                    returnObject
                  ></search>
                </div>
                <div v-show="indexType === 'agent'" key="5">
                  <search
                    v-model="person"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    api="people-index/agent.api.js"
                    :headers="searchHeaders"
                    :error-messages="personErrors"
                    returnObject
                  ></search>
                </div>
              </v-col>
            </v-row>
            <v-card-actions class="mt-10 d-flex justify-end">
              <v-btn color="primary" @click="nextStep" v-text="$t('next')">
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="2">
          <v-card flat>
            <v-row>
              <v-col cols="12" md="3">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('date')"
                ></div>
                <v-menu
                  v-model="dateMenu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="date"
                      :error-messages="dateErrors"
                      outlined
                      dense
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="date"
                    @input="dateMenu = false"
                  ></v-date-picker>
                </v-menu>
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
                      :error-messages="fromTimeErrors"
                      outlined
                      dense
                      readonly
                      v-bind="attrs"
                      v-on="on"
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
                      :error-messages="toTimeErrors"
                      outlined
                      dense
                      readonly
                      v-bind="attrs"
                      v-on="on"
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
                  v-text="$t('executionStatus')"
                ></div>
                <v-select
                  :items="executionStatusitems"
                  v-model="executionStatus"
                  item-value="id"
                  :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                  outlined
                  dense
                  :error-messages="executionStatusErrors"
                ></v-select>
              </v-col>
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
                  :error-messages="executionCaseErrors"
                ></v-select>
              </v-col>
              <v-col cols="12" md="3">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('actualValue')"
                ></div>
                <v-text-field
                  v-model="actualValue"
                  :error-messages="actualValueErrors"
                  type="number"
                  min="0"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
            </v-row>
            <v-card-actions class="mt-10 d-flex justify-end">
              <v-btn text v-text="$t('previous')" @click="previousStep">
              </v-btn>
              <v-btn color="primary" @click="nextStep" v-text="$t('next')">
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="3">
          <v-card flat>
            <v-row>
              <v-col cols="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('actionReport')"
                ></div>
                <v-textarea
                  v-model="actionReport"
                  dense
                  outlined
                  :error-messages="actionReportErrors"
                  rows="2"
                ></v-textarea>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12" md="3">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('endType')"
                ></div>
                <v-select
                  :items="endTypeItems"
                  v-model="endType"
                  item-value="value"
                  item-text="label"
                  outlined
                  dense
                  :error-messages="endTypeErrors"
                ></v-select>
              </v-col>
              <v-col cols="12" md="6">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('files')"
                ></div>
                <uploader v-model="filesNames" />
              </v-col>
            </v-row>
            <v-card-actions class="mt-10 d-flex justify-end">
              <v-btn text v-text="$t('previous')" @click="previousStep">
              </v-btn>
              <v-btn color="primary" @click="nextStep" v-text="$t('next')">
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </card>
</template>

<script>
import card from "@/components/card";
import uploader from "@/components/uploader.vue";
import search from "@/components/search";
import { required, requiredIf } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import promise from "promise";
import module from "./../write-report.store";
import moment from "moment/src/moment";
const isTimeOk = (value, vm) => {
  let format = "HH:mm";
  let start = moment.utc(vm.fromTime, format);
  let end = moment.utc(vm.toTime, format);
  return end.isAfter(start);
};

export default {
  name: "daily-report",

  components: {
    card,
    search,
    uploader
  },

  data() {
    return {
      step: 1,
      dateMenu: false,
      fromTimeMenu: false,
      toTimeMenu: false
    };
  },

  validations: {
    ticket: {
      required: requiredIf(function() {
        return this.type == "todo";
      })
    },
    company: {
      required: requiredIf(function() {
        return this.type == "createTicket";
      })
    },
    person: {
      required: requiredIf(function() {
        return this.type == "createTicket";
      })
    },
    date: { required },
    fromTime: { required },
    toTime: { required, isTimeOk },
    executionStatus: { required },
    executionCase: { required },
    actionReport: { required },
    actualValue: { required },
    endType: { required }
  },

  computed: {
    ...mapFields("dailyReport", ["breadcrumbs"]),
    ...mapFields("dailyReport", ["searchHeaders"]),
    ...mapFields("dailyReport", ["messages"]),
    ...mapFields("dailyReport", ["typeItems"]),
    ...mapFields("dailyReport", ["indexTypeItems"]),
    ...mapFields("dailyReport", ["todoHeaders"]),
    ...mapFields("dailyReport", ["endTypeItems"]),
    ...mapFields("dailyReport", ["executionStatusitems"]),
    ...mapFields("dailyReport", ["executionCaseitems"]),
    ...mapFields("dailyReport", ["personApi"]),
    //form

    ...mapFields("dailyReport", ["id"]),
    ...mapFields("dailyReport", ["type"]),
    ...mapFields("dailyReport", ["ticket"]),
    ...mapFields("dailyReport", ["indexType"]),
    ...mapFields("dailyReport", ["company"]),
    ...mapFields("dailyReport", ["person"]),
    ...mapFields("dailyReport", ["date"]),
    ...mapFields("dailyReport", ["fromTime"]),
    ...mapFields("dailyReport", ["toTime"]),
    ...mapFields("dailyReport", ["actualValue"]),
    ...mapFields("dailyReport", ["executionStatus"]),
    ...mapFields("dailyReport", ["executionCase"]),
    ...mapFields("dailyReport", ["actionReport"]),
    ...mapFields("dailyReport", ["endType"]),
    ...mapFields("dailyReport", ["filesNames"]),

    ticketErrors() {
      const errors = [];
      if (!this.$v.ticket.$dirty) return errors;
      !this.$v.ticket.required && errors.push(this.$t("required"));
      return errors;
    },
    isCompanyReadonly() {
      return this.indexType === "company" ? false : true;
    },
    isPersonReadonly() {
      return this.indexType === "company" && !this.company ? true : false;
    },
    companyErrors() {
      const errors = [];
      if (!this.$v.company.$dirty) return errors;
      !this.$v.company.required && errors.push(this.$t("required"));
      return errors;
    },
    personErrors() {
      const errors = [];
      if (!this.$v.person.$dirty) return errors;
      !this.$v.person.required && errors.push(this.$t("required"));
      return errors;
    },
    dateErrors() {
      const errors = [];
      if (!this.$v.date.$dirty) return errors;
      !this.$v.date.required && errors.push(this.$t("required"));
      return errors;
    },
    fromTimeErrors() {
      const errors = [];
      if (!this.$v.fromTime.$dirty) return errors;
      !this.$v.fromTime.required && errors.push(this.$t("required"));
      return errors;
    },
    toTimeErrors() {
      const errors = [];
      if (!this.$v.toTime.$dirty) return errors;
      !this.$v.toTime.required && errors.push(this.$t("required"));
      !this.$v.toTime.isTimeOk && errors.push(this.$t("startTimeAfterEnd"));
      return errors;
    },
    endTypeErrors() {
      const errors = [];
      if (!this.$v.endType.$dirty) return errors;
      !this.$v.endType.required && errors.push(this.$t("required"));
      return errors;
    },
    actionReportErrors() {
      const errors = [];
      if (!this.$v.actionReport.$dirty) return errors;
      !this.$v.actionReport.required && errors.push(this.$t("required"));
      return errors;
    },
    actualValueErrors() {
      const errors = [];
      if (!this.$v.actualValue.$dirty) return errors;
      !this.$v.actualValue.required && errors.push(this.$t("required"));
      return errors;
    },
    executionCaseErrors() {
      const errors = [];
      if (!this.$v.executionCase.$dirty) return errors;
      !this.$v.executionCase.required && errors.push(this.$t("required"));
      return errors;
    },
    executionStatusErrors() {
      const errors = [];
      if (!this.$v.executionStatus.$dirty) return errors;
      !this.$v.executionStatus.required && errors.push(this.$t("required"));
      return errors;
    }
  },

  methods: {
    nextStep() {
      switch (this.step) {
        case 1:
          this.$v.ticket.$touch();
          this.$v.company.$touch();
          this.$v.person.$touch();
          if (
            !this.$v.ticket.$invalid &&
            !this.$v.company.$invalid &&
            !this.$v.person.$invalid
          )
            this.step++;
          break;
        case 2:
          this.$v.date.$touch();
          this.$v.fromTime.$touch();
          this.$v.toTime.$touch();
          this.$v.executionStatus.$touch();
          this.$v.executionCase.$touch();
          this.$v.actualValue.$touch();
          if (
            !this.$v.date.$invalid &&
            !this.$v.fromTime.$invalid &&
            !this.$v.toTime.$invalid &&
            !this.$v.executionStatus.$invalid &&
            !this.$v.executionCase.$invalid &&
            !this.$v.actualValue.$invalid
          )
            this.step++;
          break;
        case 3:
          this.$v.actionReport.$touch();
          this.$v.endType.$touch();
          if (!this.$v.actionReport.$invalid && !this.$v.endType.$invalid)
            this.doSave()
              .then(() => {
                //
              })
              .catch(() => {
                //
              });
          break;

        default:
          break;
      }
    },
    previousStep() {
      this.step--;
    },
    async doSave() {
      return new promise(async (resolve, reject) => {
        this.$v.$touch();

        if (this.$v.$invalid) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("dailyReport/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => {
          //
        })
        .catch(() => {
          //
        });
    },
    saveAndNew() {
      this.doSave()
        .then(() => this.resetForm())
        .catch(() => {
          //
        });
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`agent/reset`);
    }
  },

  watch: {
    "$store.state.dailyReport.type"(val) {
      if (val) this.ticket = null;
    },
    "$store.state.dailyReport.indexType": {
      handler: function(newVal, oldVal) {
        if (newVal !== oldVal) {
          this.person = null;
          this.company = null;
        }
      },
      immediate: true
    },
    "$store.state.dailyReport.company": {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.personApi[0].payLoad = newVal;
      }
    },
    "$store.state.dailyReport.person": {
      handler: function(newVal, oldVal) {
        if (!newVal && !oldVal) return;
        if (newVal === oldVal) return;
        if (newVal && this.indexType !== "company") {
          this.company = newVal.company;
        }
      }
    }
  },

  created() {
    this.$store.registerModule("dailyReport", module);
    this.$store.dispatch("dailyReport/loadTheInitial");
  },

  beforeDestroy() {
    this.$store.unregisterModule("dailyReport");
  }
};
</script>

<style></style>
