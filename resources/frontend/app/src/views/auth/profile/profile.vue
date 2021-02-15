<template>
  <v-container class="">
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card elevation="2">
          <v-card-title class="headline grey lighten-2" v-text="$t('profile')">
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="12" class="d-flex justify-start">
                <image-select
                  v-model="image"
                  size="8rem"
                  @input="setNewImage"
                ></image-select>
              </v-col>
            </v-row>
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
            <v-row v-if="type && type !== 'App\\crmEmployee'">
              <v-col cols="12" md="6">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('language')"
                ></div>
                <search
                  :placeholder="$t('language')"
                  v-model="lang"
                  :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                  item-value="id"
                  :items="langItems"
                  :headers="searchHeaders"
                  :error-messages="langErrors"
                ></search>
              </v-col>
            </v-row>

            <!-- contact info -->
            <v-expansion-panels accordion focusable multiple class="my-4">
              <v-expansion-panel>
                <v-expansion-panel-header>
                  {{ `${$t("phone")}  ` }}
                  <template v-slot:actions>
                    <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content class="ma-0 pa-0">
                  <phone @module="phoneModule = $event" :items="phoneItems" />
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  {{ `${$t("fax")}  ` }}
                  <template v-slot:actions>
                    <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <fax @module="faxModule = $event" :items="faxItems" />
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  {{ `${$t("mobile")} ` }}
                  <template v-slot:actions>
                    <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <mobile
                    @module="mobileModule = $event"
                    :items="mobileItems"
                  />
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  {{ `${$t("email")} ` }}
                  <template v-slot:actions>
                    <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <email @module="emailModule = $event" :items="emailItems" />
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  {{ `${$t("address")} ` }}
                  <template v-slot:actions>
                    <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <address-info
                    @module="addressModule = $event"
                    :items="addressItems"
                  />
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  {{ `${$t("note")}  ` }}
                  <template v-slot:actions>
                    <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                  </template>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <note @module="noteModule = $event" :items="noteItems" />
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
            <v-row v-for="(item, i) in messages" :key="i" no-gutters>
              <v-col cols="12" md="4">
                <v-alert icon="mdi-information" v-text="item" dense></v-alert>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions class="d-flex justify-center">
            <v-btn color="primary" @click="save" v-text="$t('save')"></v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import module from "./profile.store";
import imageSelect from "@/components/image-selector";
import addressInfo from "@/views/people-index/company/tabs/contact/address.vue";
import email from "@/views/people-index/company/tabs/contact/email";
import fax from "@/views/people-index/company/tabs/contact/fax";
import mobile from "@/views/people-index/company/tabs/contact/mobile";
import note from "@/views/people-index/company/tabs/contact/note";
import phone from "@/views/people-index/company/tabs/contact/phone";

export default {
  name: "profile",

  components: {
    imageSelect,
    addressInfo,
    email,
    fax,
    mobile,
    note,
    phone
  },

  data() {
    return {};
  },

  validations: {
    name: { required },
    nameL: { required },
    username: { required },
    email: { required }
  },

  computed: {
    ...mapFields("profile", ["messages"]),
    ...mapFields("profile", ["langItems"]),
    ...mapFields("profile", ["type"]),
    //
    ...mapFields("profile", ["image"]),
    ...mapFields("profile", ["newImage"]),
    ...mapFields("profile", ["name"]),
    ...mapFields("profile", ["nameL"]),
    ...mapFields("profile", ["username"]),
    ...mapFields("profile", ["email"]),
    ...mapFields("profile", ["lang"]),
    ...mapFields("profile", ["phoneItems"]),
    ...mapFields("profile", ["faxItems"]),
    ...mapFields("profile", ["mobileItems"]),
    ...mapFields("profile", ["emailItems"]),
    ...mapFields("profile", ["addressItems"]),
    ...mapFields("profile", ["noteItems"]),
    //
    ...mapFields("profile", ["phoneModule"]),
    ...mapFields("profile", ["faxModule"]),
    ...mapFields("profile", ["mobileModule"]),
    ...mapFields("profile", ["emailModule"]),
    ...mapFields("profile", ["addressModule"]),
    ...mapFields("profile", ["noteModule"]),

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
      return errors;
    }
  },

  methods: {
    load() {
      this.$store.dispatch("profile/load");
    },
    save() {
      this.$v.$touch();
      if (this.$v.$invalid) return;

      this.$store
        .dispatch("profile/save")
        .then(() => {
          //
        })
        .catch(() => {
          //
        });
    },
    setNewImage(image) {
      this.newImage = image;
    }
  },

  created() {
    this.$store.registerModule("profile", module);
    this.load();
  },

  beforeDestroy() {
    this.$store.unregisterModule("profile");
    this.resetForm();
  }
};
</script>

<style></style>
