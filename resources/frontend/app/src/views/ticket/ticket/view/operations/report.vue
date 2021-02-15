<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('writeReport')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="3">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('endType')"
          ></div>
          <v-select
            v-model="endType"
            :items="endItems"
            item-value="value"
            item-text="label"
            outlined
            dense
          ></v-select>
        </v-col>
        <v-col cols="12" md="3">
          <div class="text-body-1 black--text mb-2" v-text="$t('date')"></div>
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
                outlined
                dense
                readonly
                v-bind="attrs"
                v-on="on"
                :error-messages="dateErrors"
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
            v-text="$t('requiredAction')"
          ></div>
          <v-select
            v-model="requiredAction"
            :items="requiredActionsItems"
            item-value="id"
            :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
            dense
            outlined
            :error-messages="actionErrors"
          ></v-select>
        </v-col>
      </v-row>
      <v-row>
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
                :error-messages="fromErrors"
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
          <div class="text-body-1 black--text mb-2" v-text="$t('toTime')"></div>
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
                :error-messages="toErrors"
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
        <v-col cols="12" md="3">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('actualValue')"
          ></div>
          <v-text-field
            append-icon="mdi-timer-outline"
            v-model="actualValue"
            readonly
            outlined
            dense
          ></v-text-field>
        </v-col>
        <!--  -->
      </v-row>
      <v-row>
        <v-col cols="12" md="3">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('finishType')"
          ></div>
          <v-select
            v-model="finishType"
            :items="finishTypeItems"
            item-value="id"
            :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
            dense
            outlined
            :error-messages="finishTypeErrors"
          ></v-select>
        </v-col>

        <v-col cols="12" md="3">
          <div class="text-body-1 black--text mb-2" v-text="$t('note')"></div>
          <v-textarea rows="2" v-model="note" dense outlined></v-textarea>
        </v-col>
        <v-col cols="12" md="6">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('actionReport')"
          ></div>
          <v-textarea
            rows="2"
            v-model="actionReport"
            :error-messages="reportErrors"
            dense
            outlined
          ></v-textarea>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <div class="text-body-1 black--text mb-2" v-text="$t('files')"></div>
          <uploader v-model="filesNames" />
        </v-col>
      </v-row>
      <div v-if="endType == 'Schedule'">
        <div
          v-if="loading"
          class="d-flex justify-center align-center"
          style="height: 5rem;"
        >
          <v-progress-circular indeterminate></v-progress-circular>
        </div>
        <datatable
          v-if="headers.length > 0"
          :headers="headers"
          @storeModule="module = $event"
          height="10rem"
          module-prefix="report-schedule"
        />
        <div
          class="red--text text-body-2"
          v-for="e in itemsErrors"
          :key="e"
          v-text="e"
        ></div>
        <v-row>
          <v-col>
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('scheduleFiles')"
            ></div>
            <uploader v-model="scheduleFiles" />
          </v-col>
        </v-row>
      </div>
      <div v-if="endType == 'Finish'">
        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('internalVisit')"
            ></div>
            <v-text-field
              v-model="internalVisit"
              dense
              outlined
              type="number"
              min="0"
              :error-messages="internalVisitErrors"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('externalVisit')"
            ></div>
            <v-text-field
              v-model="externalVisit"
              :error-messages="externalVisitErrors"
              dense
              outlined
              type="number"
              min="0"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('supportHour')"
            ></div>
            <v-text-field
              v-model="supportHour"
              :error-messages="supportHourErrors"
              dense
              outlined
              type="number"
              min="0"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('trainingHour')"
            ></div>
            <v-text-field
              v-model="trainingHour"
              :error-messages="trainingHourErrors"
              dense
              outlined
              type="number"
              min="0"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="12">
            <div class="text-body-1 black--text mb-2" v-text="$t('note')"></div>
            <v-textarea rows="2" v-model="note" dense outlined></v-textarea>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('clientRate')"
            ></div>
            <v-select
              :items="rateItems"
              v-model="clientRate"
              :error-messages="clientRateErrors"
              dense
              outlined
              item-value="value"
              item-text="label"
            ></v-select>
          </v-col>
          <v-col cols="12" md="9">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('clientNote')"
            ></div>
            <v-textarea
              rows="2"
              v-model="clientNote"
              dense
              outlined
            ></v-textarea>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="12">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('finishNote')"
            ></div>
            <v-textarea
              rows="2"
              v-model="finishNote"
              dense
              outlined
            ></v-textarea>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('finishFiles')"
            ></div>
            <uploader v-model="finishFiles" />
          </v-col>
        </v-row>
      </div>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn v-text="$t('cancel')" @click="$emit('close')"> </v-btn>
      <v-btn v-text="$t('save')" color="success" @click="save"> </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import uploader from "@/components/uploader.vue";
import ticketApi from "@/services/api/ticket/ticket.api.js";
import actionApi from "@/services/api/control-panel/required-action.api.js";
import finishApi from "@/services/api/control-panel/finish-type.api.js";
import datatable from "@/components/datatable/datatable";
import _ from "lodash";
import moment from "moment/src/moment";

import { required, requiredIf } from "vuelidate/lib/validators";
const isTimeOk = (value, vm) => {
  let format = "HH:mm";
  let start = moment.utc(vm.fromTime, format);
  let end = moment.utc(vm.toTime, format);
  return end.isAfter(start);
};

export default {
  name: "report",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    uploader,
    datatable
  },

  data() {
    return {
      dateMenu: false,
      fromTimeMenu: false,
      toTimeMenu: null,
      messages: [],
      requiredActionsItems: [],
      finishTypeItems: [],
      endItems: [
        { value: "Opened", label: this.$t("opened") },
        { value: "Schedule", label: this.$t("schedule") },
        { value: "Finish", label: this.$t("finish") }
      ],
      rateItems: [
        { value: "Bad", label: this.$t("bad") },
        { value: "Annoying", label: this.$t("annoying") },
        { value: "Accepted", label: this.$t("accepted") },
        { value: "Good", label: this.$t("good") },
        { value: "Excellent", label: this.$t("excellent") }
      ],
      module: null,
      headers: [],
      loading: false,
      //   form
      // opened
      endType: "Opened",
      date: null,
      requiredAction: null,
      fromTime: null,
      toTime: null,
      finishType: null,
      note: null,
      actionReport: null,
      filesNames: [],
      // scheduled
      items: [],
      scheduleFiles: [],
      // finished
      internalVisit: 0,
      externalVisit: 0,
      supportHour: 0,
      trainingHour: 0,
      clientRate: null,
      clientNote: null,
      finishNote: null,
      finishFiles: []
    };
  },

  validations: {
    date: { required },
    requiredAction: { required },
    fromTime: { required },
    toTime: { required, isTimeOk },
    finishType: { required },
    actualValue: { required },
    actionReport: { required },
    items: {
      required: requiredIf(function() {
        return this.endType == "Schedule";
      })
    },
    internalVisit: {
      required: requiredIf(function() {
        return this.endType == "Finish";
      })
    },
    externalVisit: {
      required: requiredIf(function() {
        return this.endType == "Finish";
      })
    },
    supportHour: {
      required: requiredIf(function() {
        return this.endType == "Finish";
      })
    },
    trainingHour: {
      required: requiredIf(function() {
        return this.endType == "Finish";
      })
    },
    clientRate: {
      required: requiredIf(function() {
        return this.endType == "Finish";
      })
    }
  },

  computed: {
    actualValue() {
      if (this.fromTime && this.toTime) {
        let start = this.$moment.utc(this.fromTime, "HH:mm");
        let end = this.$moment.utc(this.toTime, "HH:mm");
        let duration = this.$moment.duration(end.diff(start));
        return this.$moment.utc(+duration).format("H");
      }
      return 0;
    },
    dateErrors() {
      const errors = [];
      if (!this.$v.date.$dirty) return errors;
      !this.$v.date.required && errors.push(this.$t("required"));
      return errors;
    },
    actionErrors() {
      const errors = [];
      if (!this.$v.requiredAction.$dirty) return errors;
      !this.$v.requiredAction.required && errors.push(this.$t("required"));
      return errors;
    },
    fromErrors() {
      const errors = [];
      if (!this.$v.fromTime.$dirty) return errors;
      !this.$v.fromTime.required && errors.push(this.$t("required"));
      return errors;
    },
    toErrors() {
      const errors = [];
      if (!this.$v.toTime.$dirty) return errors;
      !this.$v.toTime.required && errors.push(this.$t("required"));
      !this.$v.toTime.isTimeOk && errors.push(this.$t("startTimeAfterEnd"));
      return errors;
    },
    finishTypeErrors() {
      const errors = [];
      if (!this.$v.finishType.$dirty) return errors;
      !this.$v.finishType.required && errors.push(this.$t("required"));
      return errors;
    },
    actualValueErrors() {
      const errors = [];
      if (!this.$v.actualValue.$dirty) return errors;
      !this.$v.actualValue.required && errors.push(this.$t("required"));
      return errors;
    },
    reportErrors() {
      const errors = [];
      if (!this.$v.actionReport.$dirty) return errors;
      !this.$v.actionReport.required && errors.push(this.$t("required"));
      return errors;
    },
    //scheduled
    invalidItems() {
      return this.$store.getters[`${this.module}/hasErrors`];
    },
    itemsErrors() {
      const errors = [];
      if (!this.$v.items.$dirty) return errors;
      !this.$v.items.required && errors.push(this.$t("required"));
      this.invalidItems && errors.push(this.$t("checkInputs"));
      return errors;
    },
    internalVisitErrors() {
      const errors = [];
      if (!this.$v.internalVisit.$dirty) return errors;
      !this.$v.internalVisit.required && errors.push(this.$t("required"));
      return errors;
    },
    externalVisitErrors() {
      const errors = [];
      if (!this.$v.externalVisit.$dirty) return errors;
      !this.$v.externalVisit.required && errors.push(this.$t("required"));
      return errors;
    },
    supportHourErrors() {
      const errors = [];
      if (!this.$v.supportHour.$dirty) return errors;
      !this.$v.supportHour.required && errors.push(this.$t("required"));
      return errors;
    },
    trainingHourErrors() {
      const errors = [];
      if (!this.$v.trainingHour.$dirty) return errors;
      !this.$v.trainingHour.required && errors.push(this.$t("required"));
      return errors;
    },
    clientRateErrors() {
      const errors = [];
      if (!this.$v.clientRate.$dirty) return errors;
      !this.$v.clientRate.required && errors.push(this.$t("required"));
      return errors;
    }
  },

  methods: {
    async loadTheInitial() {
      let headers = [
        {
          text: this.$t("requiredAction"),
          value: "required_action",
          width: "200px",
          type: "select",
          readonly: false,
          visible: true,
          headers: [
            { text: this.$t("name"), value: "name" },
            { text: this.$t("nameL"), value: "nameL" }
          ],
          options: [],
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("date"),
          value: "date",
          width: "200px",
          type: "date",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("time"),
          value: "time",
          width: "200px",
          type: "time",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("estimatedTime"),
          value: "estimated_time",
          width: "100px",
          type: "duration",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("note"),
          value: "note",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true
        },
        {
          text: this.$t("needSchedule"),
          value: "need_schedule",
          width: "200px",
          type: "checkbox",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        }
      ];
      this.loading = true;

      await actionApi.getAll().then(res => (headers[0].options = res));
      this.headers = await _.cloneDeep(headers);
      this.loading = false;

      actionApi.getAll().then(res => (this.requiredActionsItems = res));
      finishApi.getAll().then(res => (this.finishTypeItems = res));
    },
    async save() {
      if (this.endType == "Schedule") {
        await this.$store.commit(`${this.module}/touchValidation`);
        this.items = await this.$store.getters[`${this.module}/items`];
      }
      await this.$v.$touch();

      if (this.$v.$invalid || (await this.invalidItems)) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
        return;
      } else if (!this.$v.$invalid && (await !this.invalidItems)) this.doSave();
    },
    async doSave() {
      let data = {
        EndType: this.endType,
        date: this.date,
        execution_status: this.requiredAction,
        time_from: this.$moment(this.fromTime, "HH:mm").format("HH:mm:ss"),
        time_to: this.$moment(this.toTime, "HH:mm").format("HH:mm:ss"),
        execution_case: this.finishType,
        actual_value: this.actualValue,
        note: this.note ? this.note : undefined,
        action_report: this.actionReport
      };
      this.filesNames.forEach((item, i) => {
        data[`files[${i}]`] = item;
      });
      if (this.endType == "Schedule") {
        this.items.forEach((item, i) => {
          data[`schedule_details[${i}][required_action]`] =
            item.required_action;
          data[`schedule_details[${i}][date_time]`] =
            item.date + " " + item.time + ":00";
          data[`schedule_details[${i}][estimated_time]`] = item.estimated_time;
          if (item.note) data[`schedule_details[${i}][note]`] = item.note;
          data[`schedule_details[${i}][need_schedule]`] = item.need_schedule
            ? 1
            : 0;
        });
        this.scheduleFiles.forEach((item, i) => {
          data[`schedule_files[${i}]`] = item;
        });
      }
      if (this.endType == "Finish") {
        data["internal_visit"] = this.internalVisit;
        data["external_visit"] = this.externalVisit;
        data["support_hour"] = this.supportHour;
        data["training_hour"] = this.trainingHour;
        data["note"] = this.note ? this.note : undefined;
        data["client_rate"] = this.clientRate;
        data["client_note"] = this.clientNote ? this.clientNote : undefined;
        data["finish_note"] = this.finishNote ? this.finishNote : undefined;

        this.finishFiles.forEach((item, i) => {
          data[`finish_files[${i}]`] = item;
        });
      }

      ticketApi
        .report(this.ticketId, data)
        .then(() => this.$emit("close"))
        .catch(err => (this.messages = err));
    }
  },

  created() {
    this.loadTheInitial();
  }
};
</script>

<style></style>
