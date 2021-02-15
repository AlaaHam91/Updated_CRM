<template>
  <v-card>
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('sendSms')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('recipient')"
          ></div>
          <v-text-field
            v-model="recipient"
            outlined
            dense
            :error-messages="recipientErrors"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('subject')"
          ></div>
          <v-text-field
            v-model="subject"
            outlined
            dense
            :error-messages="subjectErrors"
          ></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="12">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('message')"
          ></div>
          <v-textarea
            rows="2"
            v-model="message"
            :error-messages="messageErrors"
            dense
            outlined
            no-resize
          ></v-textarea>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <div class="text-body-1 black--text mb-2" v-text="$t('sendTo')"></div>
          <v-autocomplete
            :items="smsToItems"
            v-model="smsTo"
            :error-messages="smsToErrors"
            item-value="value"
            item-text="label"
            chips
            dense
            multiple
            outlined
          ></v-autocomplete>
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
import { required, email } from "vuelidate/lib/validators";

export default {
  name: "sendSms",

  props: {
    ticketId: { type: Number, required: true }
  },

  data() {
    return {
      messages: [],
      smsToItems: [
        { value: "Employee", label: this.$t("employee") },
        { value: "Manager", label: this.$t("manager") },
        { value: "Client", label: this.$t("client") }
      ],
      recipient: null,
      subject: null,
      message: null,
      smsTo: []
    };
  },

  validations: {
    recipient: { required, email },
    subject: { required },
    message: { required },
    smsTo: { required }
  },

  computed: {
    recipientErrors() {
      const errors = [];
      if (!this.$v.recipient.$dirty) return errors;
      !this.$v.recipient.required && errors.push(this.$t("required"));
      !this.$v.recipient.email && errors.push(this.$t("emailNotValid"));
      return errors;
    },
    subjectErrors() {
      const errors = [];
      if (!this.$v.subject.$dirty) return errors;
      !this.$v.subject.required && errors.push(this.$t("required"));
      return errors;
    },
    messageErrors() {
      const errors = [];
      if (!this.$v.message.$dirty) return errors;
      !this.$v.message.required && errors.push(this.$t("required"));
      return errors;
    },
    smsToErrors() {
      const errors = [];
      if (!this.$v.smsTo.$dirty) return errors;
      !this.$v.smsTo.required && errors.push(this.$t("required"));
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
          recipient: this.recipient,
          subject: this.subject,
          message: this.message
        };
        this.smsTo.forEach((item, i) => {
          data[`s_m_s[${i}]`] = item;
        });

        ticketApi
          .sms(this.ticketId, data)
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
