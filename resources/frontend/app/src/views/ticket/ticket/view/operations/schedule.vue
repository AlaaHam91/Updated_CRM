<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('schedule')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>

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
        module-prefix="schedule"
      />
      <div
        class="red--text text-body-2"
        v-for="e in itemsErrors"
        :key="e"
        v-text="e"
      ></div>
      <v-row>
        <v-col>
          <div class="text-body-1 black--text mb-2" v-text="$t('files')"></div>
          <uploader v-model="filesNames" />
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn v-text="$t('cancel')" @click="$emit('close')"> </v-btn>
      <v-btn v-text="$t('save')" color="success" @click="save"> </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import uploader from "@/components/uploader.vue";
import datatable from "@/components/datatable/datatable";
import actionsApi from "@/services/api/control-panel/required-action.api.js";
import ticketApi from "@/services/api/ticket/ticket.api.js";
import _ from "lodash";
import { required } from "vuelidate/lib/validators";

export default {
  name: "schedule",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    datatable,
    uploader
  },

  data() {
    return {
      module: null,
      headers: [],
      loading: false,
      filesNames: [],
      items: [],
      messages: []
    };
  },

  validations: {
    items: { required }
  },

  computed: {
    invalidItems() {
      return this.$store.getters[`${this.module}/hasErrors`];
    },
    itemsErrors() {
      const errors = [];
      if (!this.$v.items.$dirty) return errors;
      !this.$v.items.required && errors.push(this.$t("required"));
      this.invalidItems && errors.push(this.$t("checkInputs"));
      return errors;
    }
  },

  methods: {
    async load() {
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
          type: "check",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("transport"),
          value: "transport",
          width: "200px",
          type: "number",
          readonly: false,
          visible: true
        }
      ];
      this.loading = true;

      await actionsApi.getAll().then(res => (headers[0].options = res));
      this.headers = await _.cloneDeep(headers);
      this.loading = false;
    },
    async save() {
      await this.$store.commit(`${this.module}/touchValidation`);
      this.items = await this.$store.getters[`${this.module}/items`];
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
      let data = {};
      let items = await this.$store.getters[`${this.module}/items`];
      items.forEach((item, i) => {
        data[`schedule_details[${i}][required_action]`] = item.required_action;

        data[`schedule_details[${i}][date_time]`] =
          item.date + " " + item.time + ":00";

        data[`schedule_details[${i}][estimated_time]`] = item.estimated_time;
        if (item.note) data[`schedule_details[${i}][note]`] = item.note;
        data[`schedule_details[${i}][need_schedule]`] = item.need_schedule
          ? 1
          : 0;
        if (item.transport)
          data[`schedule_details[${i}][transport]`] = item.transport;
      });
      this.filesNames.forEach((item, i) => {
        data[`files[${i}]`] = item;
      });

      ticketApi
        .assign(this.ticketId, data)
        .then(() => this.$emit("close"))
        .catch(err => (this.messages = err));
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
