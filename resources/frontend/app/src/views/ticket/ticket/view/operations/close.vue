<template>
  <v-card>
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('closeTicket')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('clientRate')"
          ></div>
          <v-select
            :items="rateItems"
            v-model="clientRate"
            dense
            outlined
            item-value="value"
            item-text="label"
          ></v-select>
        </v-col>
        <v-col cols="12" md="8">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('clientNote')"
          ></div>
          <v-textarea
            v-model="clientNote"
            dense
            outlined
            rows="2"
            no-resize
          ></v-textarea>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('employeeRate')"
          ></div>
          <v-select
            :items="rateItems"
            v-model="employeeRate"
            dense
            outlined
            item-value="value"
            item-text="label"
          ></v-select>
        </v-col>
        <v-col cols="12" md="8">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('employeeNote')"
          ></div>
          <v-textarea
            v-model="employeeNote"
            dense
            outlined
            rows="2"
            no-resize
          ></v-textarea>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="4">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('productRate')"
          ></div>
          <v-select
            :items="rateItems"
            v-model="productRate"
            dense
            outlined
            item-value="value"
            item-text="label"
          ></v-select>
        </v-col>
        <v-col cols="12" md="8">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('productNote')"
          ></div>
          <v-textarea
            v-model="productNote"
            dense
            outlined
            rows="2"
            no-resize
          ></v-textarea>
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
import ticketApi from "@/services/api/ticket/ticket.api.js";
// import { required } from "vuelidate/lib/validators";

export default {
  name: "closeTicket",

  props: {
    ticketId: { type: Number, required: true }
  },

  data() {
    return {
      messages: [],
      rateItems: [
        { value: "Bad", label: this.$t("bad") },
        { value: "Annoying", label: this.$t("annoying") },
        { value: "Accepted", label: this.$t("accepted") },
        { value: "Good", label: this.$t("good") },
        { value: "Excellent", label: this.$t("excellent") }
      ],
      clientRate: null,
      clientNote: null,
      employeeRate: null,
      employeeNote: null,
      productRate: null,
      productNote: null
    };
  },

  //   validations: {
  //     smsTo: { required }
  //   },

  //   computed: {
  //     smsToErrors() {
  //       const errors = [];
  //       if (!this.$v.smsTo.$dirty) return errors;
  //       !this.$v.smsTo.required && errors.push(this.$t("required"));
  //       return errors;
  //     }
  //   },

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
          client_rate: this.clientRate,
          client_note: this.clientNote,
          employee_rate: this.employeeRate,
          employee_note: this.employeeNote,
          product_rate: this.productRate,
          product_note: this.productNote
        };

        ticketApi
          .closeTicket(this.ticketId, data)
          .then(() => this.$emit("close"))
          .catch(err => {
            if (typeof err == "string") this.messages.push(err);
            else this.messages = err;
          });
      }
    }
  }
};
</script>

<style></style>
