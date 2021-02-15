<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('assign')"
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
        module-prefix="assign"
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
import userApi from "@/services/api/users/user.api.js";
import actionsApi from "@/services/api/control-panel/required-action.api.js";
import ticketApi from "@/services/api/ticket/ticket.api.js";
import _ from "lodash";
import { required } from "vuelidate/lib/validators";

export default {
  name: "assign",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    uploader,
    datatable
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
          text: this.$t("employee"),
          value: "employee",
          width: "150px",
          type: "search",
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
          text: this.$t("estimatedTime"),
          value: "estimated_time",
          width: "120px",
          type: "duration",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("expectedValue"),
          value: "expected_value",
          width: "150px",
          type: "string",
          readonly: false,
          visible: true
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
          text: this.$t("requiredAction"),
          value: "required_action",
          width: "150px",
          type: "select",
          readonly: false,
          visible: true,
          headers: [
            { text: this.$t("name"), value: "name" },
            { text: this.$t("nameL"), value: "nameL" }
          ],
          options: [],
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id"
        },
        {
          text: this.$t("date"),
          value: "date",
          width: "150px",
          type: "date",
          readonly: false,
          visible: true
        },
        {
          text: this.$t("time"),
          value: "time",
          width: "100px",
          type: "time",
          readonly: false,
          visible: true
        },
        {
          text: this.$t("transport"),
          value: "transport",
          width: "100px",
          type: "duration",
          readonly: false,
          visible: true
        }
      ];
      this.loading = true;

      await userApi.getAll().then(res => (headers[0].options = res));
      await actionsApi.getAll().then(res => (headers[4].options = res));
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

      this.items.forEach((item, i) => {
        data[`assign_details[${i}][employee]`] = item.employee;
        data[`assign_details[${i}][estimated_time]`] = item.estimated_time;
        if (item.expected_value)
          data[`assign_details[${i}][expected_value]`] = item.expected_value;
        if (item.note) data[`assign_details[${i}][note]`] = item.note;
        if (item.required_action)
          data[`assign_details[${i}][required_action]`] = item.required_action;
        if (item.date && item.time)
          data[`assign_details[${i}][date_time]`] =
            item.date + " " + item.time + ":00";
        if (item.transport)
          data[`assign_details[${i}][transport]`] = item.transport;
      });
      this.filesNames.forEach((item, i) => {
        data[`files[${i}]`] = item;
      });

      ticketApi
        .assign(this.ticketId, data)
        .then(() => {
          this.$emit("close");
        })
        .catch(err => (this.messages = err));
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
