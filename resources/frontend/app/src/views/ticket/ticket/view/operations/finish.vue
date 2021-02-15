<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('finish')"
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
          <v-textarea rows="2" v-model="clientNote" dense outlined></v-textarea>
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
// import search from "./../../../components/search";
import ticketApi from "@/services/api/ticket/ticket.api.js";
import { required } from "vuelidate/lib/validators";

export default {
  name: "finish",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    uploader
  },

  data() {
    return {
      internalVisit: 0,
      externalVisit: 0,
      supportHour: 0,
      trainingHour: 0,
      note: null,
      clientRate: null,
      clientNote: null,
      filesNames: [],
      rateItems: [
        { value: "Bad", label: this.$t("bad") },
        { value: "Annoying", label: this.$t("annoying") },
        { value: "Accepted", label: this.$t("accepted") },
        { value: "Good", label: this.$t("good") },
        { value: "Excellent", label: this.$t("excellent") }
      ],
      messages: []
    };
  },

  validations: {
    internalVisit: { required },
    externalVisit: { required },
    supportHour: { required },
    trainingHour: { required },
    clientRate: { required }
  },

  computed: {
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
    async save() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
      } else {
        let data = {
          internal_visit: this.internalVisit,
          external_visit: this.externalVisit,
          support_hour: this.supportHour,
          training_hour: this.trainingHour,
          note: this.note ? this.note : undefined,
          client_rate: this.clientRate,
          client_note: this.clientNote
        };
        this.filesNames.forEach((item, i) => {
          data[`files[${i}]`] = item;
        });

        ticketApi
          .finish(this.ticketId, data)
          .then(() => this.$emit("close"))
          .catch(err => (this.messages = err));
      }
    }
  },

  created() {}
};
</script>

<style></style>
