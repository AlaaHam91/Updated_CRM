<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('share')"
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
        module-prefix="share"
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
import branchApi from "@/services/api/company-data/branch.api.js";
import departmentApi from "@/services/api/company-data/department.api.js";
import ticketApi from "@/services/api/ticket/ticket.api.js";
import _ from "lodash";
import { required } from "vuelidate/lib/validators";

export default {
  name: "share",

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
          text: this.$t("department"),
          value: "department",
          width: "200px",
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
          text: this.$t("branch"),
          value: "branch",
          width: "200px",
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
          text: this.$t("note"),
          value: "note",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true
        }
      ];
      this.loading = true;

      await departmentApi.getAll().then(res => (headers[0].options = res));
      await branchApi.getAll().then(res => (headers[1].options = res));
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
        data[`share_details[${i}][department]`] = item.department;
        data[`share_details[${i}][branch]`] = item.branch;
        if (item.note) data[`share_details[${i}][note]`] = item.note;
      });
      this.filesNames.forEach((item, i) => {
        data[`files[${i}]`] = item;
      });

      ticketApi
        .share(this.ticketId, data)
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
