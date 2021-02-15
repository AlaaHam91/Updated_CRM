<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('redirect')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('department')"
          ></div>
          <search
            v-model="department"
            :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
            item-value="id"
            api="company-data/department.api.js"
            :headers="searchHeaders"
            :error-messages="departmentErrors"
          ></search>
        </v-col>

        <v-col cols="12" md="6">
          <div class="text-body-1 black--text mb-2" v-text="$t('branch')"></div>
          <search
            v-model="branch"
            :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
            item-value="id"
            api="company-data/branch.api.js"
            :headers="searchHeaders"
            :error-messages="branchErrors"
          ></search>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="12">
          <div class="text-body-1 black--text mb-2" v-text="$t('note')"></div>
          <v-textarea rows="2" v-model="note" dense outlined></v-textarea>
        </v-col>
      </v-row>

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
import search from "@/components/search";
import ticketApi from "@/services/api/ticket/ticket.api.js";
import { required } from "vuelidate/lib/validators";

export default {
  name: "redirect",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    uploader,
    search
  },

  data() {
    return {
      department: null,
      branch: null,
      note: null,
      filesNames: [],
      searchHeaders: [
        { name: this.$t("name"), value: "name" },
        { name: this.$t("nameL"), value: "nameL" }
      ],
      messages: []
    };
  },

  validations: {
    department: { required },
    branch: { required }
  },

  computed: {
    departmentErrors() {
      const errors = [];
      if (!this.$v.department.$dirty) return errors;
      !this.$v.department.required && errors.push(this.$t("required"));
      return errors;
    },
    branchErrors() {
      const errors = [];
      if (!this.$v.branch.$dirty) return errors;
      !this.$v.branch.required && errors.push(this.$t("required"));
      return errors;
    }
  },

  methods: {
    async save() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
      } else {
        let data = {
          department: this.department,
          branch: this.branch,
          note: this.note
        };
        this.filesNames.forEach((item, i) => {
          data[`files[${i}]`] = item;
        });

        ticketApi
          .redirect(this.ticketId, data)
          .then(() => this.$emit("close"))
          .catch(err => (this.messages = err));
      }
    }
  }
};
</script>

<style></style>
