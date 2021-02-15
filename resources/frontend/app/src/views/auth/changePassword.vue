<template>
  <v-container class="">
    <v-row v-for="(item, i) in messages" :key="i" no-gutters>
      <v-col cols="12" md="4">
        <v-alert
          icon="mdi-information"
          color="red"
          type="error"
          v-text="item"
          dense
        ></v-alert>
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-col cols="12" md="4">
        <v-card elevation="2">
          <v-card-title
            class="headline grey lighten-2"
            v-text="$t('changePass')"
          >
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12">
                <div
                  class="text-body-2 black--text mb-2"
                  v-text="$t('oldPass')"
                ></div>
                <v-text-field
                  :placeholder="$t('oldPass')"
                  v-model="oldPass"
                  type="password"
                  outlined
                  dense
                  :error-messages="oldPassErrors"
                  autocomplete="off"
                  spellcheck="off"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <div
                  class="text-body-2 black--text mb-2"
                  v-text="$t('newPass')"
                ></div>
                <v-text-field
                  :placeholder="$t('newPass')"
                  v-model="newPass"
                  type="password"
                  outlined
                  dense
                  :error-messages="newPassErrors"
                  autocomplete="off"
                  spellcheck="off"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <div
                  class="text-body-2 black--text mb-2"
                  v-text="$t('confirm')"
                ></div>
                <v-text-field
                  :placeholder="$t('confirmPass')"
                  v-model="confirm"
                  type="password"
                  outlined
                  dense
                  :error-messages="confirmErrors"
                  autocomplete="off"
                  spellcheck="off"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions class="d-flex justify-center">
            <v-btn
              color="primary"
              @click="save"
              v-text="$t('changePass')"
            ></v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { required, sameAs } from "vuelidate/lib/validators";
import api from "@/services/api/auth/auth.api";

export default {
  name: "change-password",

  data() {
    return {
      messages: [],
      //
      oldPass: null,
      newPass: null,
      confirm: null
    };
  },

  validations: {
    oldPass: { required },
    newPass: { required },
    confirm: { required, sameAsPassword: sameAs("newPass") }
  },

  computed: {
    oldPassErrors() {
      const errors = [];
      if (!this.$v.oldPass.$dirty) return errors;
      !this.$v.oldPass.required && errors.push(this.$t("required"));
      return errors;
    },
    newPassErrors() {
      const errors = [];
      if (!this.$v.newPass.$dirty) return errors;
      !this.$v.newPass.required && errors.push(this.$t("required"));
      return errors;
    },
    confirmErrors() {
      const errors = [];
      if (!this.$v.confirm.$dirty) return errors;
      !this.$v.confirm.required && errors.push(this.$t("required"));
      !this.$v.confirm.sameAsPassword &&
        errors.push(this.$t("passwordConfirmRule"));
      return errors;
    }
  },

  methods: {
    reset() {
      this.newPass = null;
      this.oldPass = null;
      this.confirm = null;
      this.$v.$reset();
    },
    save() {
      this.$v.$touch();
      if (this.$v.$invalid) return;

      let data = {
        old_password: this.oldPass,
        new_password: this.newPass
      };
      api
        .changePassword(data)
        .then(() => {
          this.$notify({
            text: this.$t("success"),
            type: "success"
          });
          this.reset();
          this.$store.dispatch("auth/signout");
          this.$router.push({ name: "signin" });
        })
        .catch(res => {
          this.messages = Array.isArray(res.data.message)
            ? res.data.message
            : [res.data.message];
        });
    }
  },

  created() {
    this.reset();
  }
};
</script>

<style></style>
