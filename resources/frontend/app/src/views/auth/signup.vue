<template>
  <v-img
    :src="background"
    gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
  >
    <v-container id="section-top">
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
          <v-card class="pa-1" elevation="2">
            <v-card-text>
              <div
                class="text--left text-body-1 black--text mb-16"
                v-text="$t('signupIns')"
              ></div>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('name')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('name')"
                    v-model="name"
                    outlined
                    dense
                    :error-messages="nameErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('name')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('nameL')"
                    v-model="nameL"
                    outlined
                    dense
                    :error-messages="nameLErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('company')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('company')"
                    v-model="company"
                    outlined
                    dense
                    :error-messages="companyErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('companyL')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('companyL')"
                    v-model="companyL"
                    outlined
                    dense
                    :error-messages="companyLErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('job')"
                  ></div>
                  <v-select
                    v-model="job"
                    :items="jobItems"
                    :error-messages="jobErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    dense
                    outlined
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('jobTitle')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('jobTitle')"
                    v-model="jobTitle"
                    outlined
                    dense
                    :error-messages="jobTitleErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('username')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('username')"
                    v-model="username"
                    outlined
                    dense
                    :error-messages="usernameErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('email')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('email')"
                    v-model="email"
                    outlined
                    dense
                    :error-messages="emailErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('password')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('password')"
                    v-model="password"
                    type="password"
                    outlined
                    dense
                    :error-messages="passwordErrors"
                    autocomplete="off"
                    spellcheck="off"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-2 black--text mb-2"
                    v-text="$t('passwordConfirm')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('passwordConfirm')"
                    v-model="confirm"
                    :type="showPassword ? 'text' : 'password'"
                    @click:append="showPassword = !showPassword"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @change="$v.confirm.$touch()"
                    @blur="$v.confirm.$touch()"
                    outlined
                    dense
                    :error-messages="confirmErrors"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
            <v-card-actions class="d-flex flex-column justify-center">
              <vue-recaptcha
                :sitekey="captchKey"
                :loadRecaptchaScript="true"
                @verify="verifyRecaptcha"
                @expired="expiredRecaptcha"
                class="mb-4"
              >
              </vue-recaptcha>
              <v-btn
                :disabled="!isCaptchaVerified"
                color="primary"
                v-text="$t('signup')"
                @click="signup"
              >
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-img>
</template>

<script>
import { required, sameAs, email } from "vuelidate/lib/validators";
import api from "@/services/api/auth/auth.api.js";
import jobApi from "@/services/api/users/job.api.js";
import VueRecaptcha from "vue-recaptcha";

export default {
  name: "signup",

  components: {
    VueRecaptcha
  },

  data() {
    return {
      messages: [],
      showPassword: false,
      background: require("@/assets/architecture.jpg"),
      captchKey: "6LcH7twZAAAAABRzvX-AizozMyULFXFVZ5n4T7kQ", //google captcha v2
      jobItems: [],
      //
      name: null,
      nameL: null,
      company: null,
      companyL: null,
      job: null,
      jobTitle: null,
      username: null,
      email: null,
      password: null,
      confirm: null,
      isCaptchaVerified: false
    };
  },

  validations: {
    name: { required },
    nameL: { required },
    company: { required },
    companyL: { required },
    job: { required },
    jobTitle: { required },
    username: { required },
    email: { required, email },
    password: { required },
    confirm: {
      sameAsPassword: sameAs("password"),
      required
    }
  },

  computed: {
    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.required && errors.push(this.$t("required"));
      return errors;
    },
    nameLErrors() {
      const errors = [];
      if (!this.$v.nameL.$dirty) return errors;
      !this.$v.nameL.required && errors.push(this.$t("required"));
      return errors;
    },
    companyErrors() {
      const errors = [];
      if (!this.$v.company.$dirty) return errors;
      !this.$v.company.required && errors.push(this.$t("required"));
      return errors;
    },
    companyLErrors() {
      const errors = [];
      if (!this.$v.companyL.$dirty) return errors;
      !this.$v.companyL.required && errors.push(this.$t("required"));
      return errors;
    },
    jobErrors() {
      const errors = [];
      if (!this.$v.job.$dirty) return errors;
      !this.$v.job.required && errors.push(this.$t("required"));
      return errors;
    },
    jobTitleErrors() {
      const errors = [];
      if (!this.$v.jobTitle.$dirty) return errors;
      !this.$v.jobTitle.required && errors.push(this.$t("required"));
      return errors;
    },
    usernameErrors() {
      const errors = [];
      if (!this.$v.username.$dirty) return errors;
      !this.$v.username.required && errors.push(this.$t("required"));
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.required && errors.push(this.$t("required"));
      !this.$v.email.email && errors.push(this.$t("emailNotValid"));
      return errors;
    },
    passwordErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.required && errors.push(this.$t("required"));
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
    verifyRecaptcha($event) {
      if ($event) this.isCaptchaVerified = true;
    },
    expiredRecaptcha() {
      this.isCaptchaVerified = false;
    },
    loadTheInitials() {
      jobApi.getAllForGuest().then(res => (this.jobItems = res));
    },
    signup() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
        return;
      }
      let data = {
        name: this.name,
        latin_name: this.nameL,
        company_name: this.company,
        company_latin_name: this.companyL,
        job: this.job,
        job_title: this.jobTitle,
        user_name: this.username,
        email: this.email,
        password: this.password,
        confirm_password: this.confirm
      };
      api
        .signup(data)
        .then(() => {
          this.$notify({
            text: this.$t("successRegister"),
            type: "success"
          });
          this.$router.push({ name: "signin" });
        })
        .catch(res => {
          this.messages = Array.isArray(res.data.message)
            ? res.data.message
            : [res.data.message];
          this.$vuetify.goTo("#section-top");
        });
    }
  },

  created() {
    this.loadTheInitials();
  }
};
</script>

<style></style>
