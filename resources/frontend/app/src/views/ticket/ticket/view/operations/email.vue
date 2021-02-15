<template>
  <v-card>
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('sendEmail')"
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
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('sendEmailTo')"
          ></div>
          <v-autocomplete
            :items="emailItems"
            v-model="emailTo"
            :error-messages="emailToErrors"
            item-value="value"
            item-text="label"
            chips
            dense
            multiple
            outlined
          ></v-autocomplete>
        </v-col>
        <v-col cols="12" md="6">
          <div
            class="text-body-1 black--text mb-2"
            v-text="$t('typeCustomEmail')"
          ></div>
          <v-combobox
            v-model="customEmailTo"
            :error-messages="customEmailErrors"
            placeholder="Email"
            append-icon=""
            multiple
            dense
            outlined
            chips
          ></v-combobox>
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
import ticketApi from "@/services/api/ticket/ticket.api.js";
import { required, email } from "vuelidate/lib/validators";

export default {
  name: "sendEmail",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    uploader
  },

  data() {
    return {
      messages: [],
      emailItems: [
        { value: "Employee", label: this.$t("employee") },
        { value: "Manager", label: this.$t("manager") },
        { value: "Client", label: this.$t("client") }
      ],
      subject: null,
      message: null,
      emailTo: [],
      customEmailTo: [],
      filesNames: []
    };
  },

  validations: {
    subject: { required },
    message: { required },
    emailTo: { required },
    customEmailTo: {
      $each: {
        email
      }
    }
  },

  computed: {
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
    emailToErrors() {
      const errors = [];
      if (!this.$v.emailTo.$dirty) return errors;
      !this.$v.emailTo.required && errors.push(this.$t("required"));
      return errors;
    },
    customEmailErrors() {
      const errors = [];
      if (!this.$v.customEmailTo.$dirty) return errors;
      this.$v.customEmailTo.$anyError && errors.push(this.$t("emailNotValid"));
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
          subject: this.subject,
          message: this.message
        };
        this.emailTo.forEach((item, i) => {
          data[`email[${i}]`] = item;
        });
        this.customEmailTo.forEach((item, i) => {
          data[`custom_email[${i}]`] = item;
        });
        this.filesNames.forEach((item, i) => {
          data[`files[${i}]`] = item;
        });

        ticketApi
          .email(this.ticketId, data)
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
