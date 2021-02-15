<template>
  <card
    :title="$t('meeting')"
    @reset-form="resetForm"
    @save="save"
    new-btn
    :save-btn="$can('Meeting', 'Create')"
    :breadcrumbs="breadcrumbs"
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

    <v-row>
      <v-col>
        <person-selector
          v-model="personInfo"
          :touch="touchPersonInfo"
          @invalid="invalidPerson = $event"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <div class="text-body-1 black--text mb-2" v-text="$t('employee')"></div>
        <v-autocomplete
          v-model="employee"
          :items="employeesItems"
          :error-messages="employeeErrors"
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
      <v-col cols="12" md="3">
        <div class="text-body-1 black--text mb-2" v-text="$t('subject')"></div>
        <v-text-field
          v-model="subject"
          :error-messages="subjectErrors"
          outlined
          dense
        ></v-text-field>
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
            :locale="$i18n.locale"
          ></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="3">
        <div class="text-body-1 black--text mb-2" v-text="$t('fromTime')"></div>
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
      <v-col cols="12" md="6">
        <datatable
          :headers="headers"
          @storeModule="module = $event"
          height="10rem"
          module-prefix="schedule-meeting"
        />
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "@/components/card";
import datatable from "@/components/datatable/datatable";
import personSelector from "@/components/person-selector";
import { required } from "vuelidate/lib/validators";
// import search from "@/components/search";
// import { mapFields } from "vuex-map-fields";
// data-tables
import moment from "moment/src/moment";
import userApi from "@/services/api/users/user.api";
import api from "@/services/api/ticket/meeting.api";

const isTimeOk = (value, vm) => {
  let format = "HH:mm";
  let start = moment.utc(vm.fromTime, format);
  let end = moment.utc(vm.toTime, format);
  return end.isAfter(start);
};

export default {
  components: {
    card,
    datatable,
    personSelector
    // search,
  },
  name: "meeting-show",

  data() {
    return {
      breadcrumbs: [
        { text: this.$t("tickets") },
        { text: this.$t("meetingCreate") }
      ],
      module: null, //datatable
      headers: [
        {
          text: this.$t("fromTime"),
          value: "from",
          width: "200px",
          type: "time",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("toTime"),
          value: "to",
          width: "200px",
          type: "time",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        }
      ],
      messages: [],
      employeesItems: [],
      dateMenu: false,
      fromTimeMenu: false,
      toTimeMenu: false,
      touchPersonInfo: false,
      invalidPerson: false,
      //form
      employee: [],
      date: null,
      subject: null,
      fromTime: null,
      toTime: null,
      personInfo: {
        company: null,
        person: null
      }
    };
  },

  validations: {
    employee: { required },
    date: { required },
    subject: { required },
    fromTime: { required },
    toTime: { required, isTimeOk }
  },

  methods: {
    async save() {
      this.$v.$touch();
      this.touchPersonInfo = true;
      await this.$store.commit(`${this.module}/touchValidation`);
      let datatableError = await this.$store.getters[
        `${this.storeModule}/hasErrors`
      ];

      if (this.$v.$invalid || this.invalidPerson || datatableError) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
      } else {
        let data = {
          "issued for company": this.personInfo.company,
          "request by c person": this.personInfo.person.c_person_id,
          subject: this.subject,
          date: this.date,
          start_time: this.$moment(this.fromTime, "HH:mm").format("HH:mm:ss"),
          end_time: this.$moment(this.toTime, "HH:mm").format("HH:mm:ss")
        };
        this.employee.forEach((item, i) => {
          data[`employees[${i}]`] = item;
        });
        if (this.module) {
          let items = this.$store.getters[`${this.module}/items`];
          items.forEach((item, i) => {
            data[`schedule_details[${i}][from_time]`] = this.$moment(
              item.from,
              "HH:mm"
            ).format("HH:mm:ss");
            data[`schedule_details[${i}][to_time]`] = this.$moment(
              item.to,
              "HH:mm"
            ).format("HH:mm:ss");
          });
        }

        api
          .create(data)
          .then(() => {
            this.$notify({
              text: this.$t("success"),
              type: "success"
            });
          })
          .catch(() => {
            //
          });
      }
    },
    load() {
      //
    },
    resetForm() {
      this.$v.$reset();
      this.touchPersonInfo = false;
      this.employee = null;
      this.date = null;
      this.subject = null;
      this.fromTime = null;
      this.toTime = null;
      this.personInfo = {
        company: null,
        person: null
      };
      if (this.module) this.$store.dispatch(`${this.module}/clearData`);
    },
    loadTheInitial() {
      userApi.getAll().then(res => (this.employeesItems = res));
    }
  },

  computed: {
    employeeErrors() {
      const errors = [];
      if (!this.$v.employee.$dirty) return errors;
      !this.$v.employee.required && errors.push(this.$t("required"));
      return errors;
    },
    dateErrors() {
      const errors = [];
      if (!this.$v.date.$dirty) return errors;
      !this.$v.date.required && errors.push(this.$t("required"));
      return errors;
    },
    subjectErrors() {
      const errors = [];
      if (!this.$v.subject.$dirty) return errors;
      !this.$v.subject.required && errors.push(this.$t("required"));
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
    }
  },

  created() {
    this.loadTheInitial();
  }
};
</script>

<style></style>
