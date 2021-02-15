<template>
  <v-card>
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('addNote')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="12">
          <div class="text-body-1 black--text mb-2" v-text="$t('note')"></div>
          <v-textarea
            rows="2"
            v-model="note"
            :error-messages="noteErrors"
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
  name: "note",

  props: {
    ticketId: { type: Number, required: true }
  },

  data() {
    return {
      messages: [],
      emailItems: [
        { value: "Employee", label: this.$t("employee") },
        { value: "Manager", label: this.$t("manager") },
        { value: "Client", label: this.$t("client") }
      ],
      emailTo: [],
      customEmailTo: [],
      note: null
    };
  },

  validations: {
    note: { required },
    customEmailTo: {
      $each: {
        email
      }
    }
  },

  computed: {
    noteErrors() {
      const errors = [];
      if (!this.$v.note.$dirty) return errors;
      !this.$v.note.required && errors.push(this.$t("required"));
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
          note: this.note
        };
        this.emailTo.forEach((item, i) => {
          data[`email[${i}]`] = item;
        });
        this.customEmailTo.forEach((item, i) => {
          data[`custom_email[${i}]`] = item;
        });

        ticketApi
          .note(this.ticketId, data)
          .then(() => this.$emit("close"))
          .catch(err => (this.messages = err));
      }
    }
  }
};
</script>

<style></style>
