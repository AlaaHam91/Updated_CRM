<template>
  <card
    :title="id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    @first="first"
    @previous="previous"
    @next="next"
    @last="last"
    :new-btn="$can('Pg_poten_agent', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Pg_poten_agent', 'Delete') : false"
    :breadcrumbs="breadcrumbs"
    :no="id"
  >
    <v-row v-for="(item, i) in messages" :key="i" no-gutters>
      <v-col cols="12" md="4">
        <v-alert
          icon="mdi-information"
          color="red"
          type="error"
          v-text="item"
        ></v-alert>
      </v-col>
    </v-row>

    <v-tabs v-model="tab" background-color="grey lighten-3">
      <v-tab>
        {{ $t("general") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon color="warning" v-if="$v.$anyError">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab>
        {{ $t("contactInfo") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon
            v-if="
              invalidPhones ||
                invalidFaxes ||
                invalidMobiles ||
                invalidEmails ||
                invalidAddresses ||
                invalidNotes ||
                invalidLocations
            "
            color="warning"
          >
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab v-text="$t('additionalInfo')"></v-tab>
      <v-tab v-text="$t('log')"></v-tab>
      <v-tab v-if="id > 0" v-text="$t('archives')"></v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <!-- general -->
      <v-tab-item>
        <v-card flat>
          <v-row>
            <v-col cols="12" md="3" class="d-flex flex-column align-center">
              <image-select
                v-model="image"
                size="10rem"
                class="mb-4"
                @input="setImage"
                v-if="id > 0"
              ></image-select>
              <v-switch
                :label="isActive ? $t('active') : $t('inactive')"
                v-model="isActive"
                :error-messages="isActiveErrors"
              ></v-switch>
              <v-switch
                :label="$t('isManager')"
                :error-messages="isManagerErrors"
                v-model="isManager"
              ></v-switch>
            </v-col>
            <v-col cols="12" md="9">
              <v-row v-if="id > 0">
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('date')"
                  ></div>
                  <v-text-field v-model="date" outlined readonly></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('user')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('user')"
                    v-model="user"
                    outlined
                    readonly
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('contactSource')"
                  ></div>
                  <search
                    :placeholder="$t('contactSource')"
                    v-model="contactSource"
                    :error-messages="contactSourceErrors"
                    :item-text="$i18n.locale == 'en' ? 'latin_name' : 'name'"
                    item-value="id"
                    :items="contactSourceItems"
                    :headers="searchHeaders"
                  ></search>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('company')"
                  ></div>
                  <search
                    :placeholder="$t('company')"
                    v-model="company"
                    :error-messages="companyErrors"
                    :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                    item-value="id"
                    api="people-index/company.api.js"
                    :headers="searchHeaders"
                  ></search>
                  <!-- to-do add button -->
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('name')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('name')"
                    :error-messages="nameErrors"
                    v-model="name"
                    outlined
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('nameL')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('nameL')"
                    v-model="nameL"
                    :error-messages="nameLErrors"
                    outlined
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('job')"
                  ></div>
                  <v-select
                    :items="jobItems"
                    v-model="job"
                    :placeholder="$t('job')"
                    :error-messages="jobErrors"
                    :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                    item-value="id"
                    clearable
                    outlined
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('jobTitle')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('jobTitle')"
                    :error-messages="jobTitleErrors"
                    v-model="jobTitle"
                    outlined
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="12">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('activity')"
                  ></div>
                  <v-autocomplete
                    v-model="activity"
                    :items="activityItems"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    :error-messages="activityErrors"
                    hide-selected
                    clearable
                    item-value="id"
                    multiple
                    chips
                    small-chips
                    outlined
                    dense
                  ></v-autocomplete>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="12">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('interest')"
                  ></div>
                  <v-autocomplete
                    v-model="interest"
                    :items="interestItems"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    :error-messages="interestErrors"
                    hide-selected
                    clearable
                    item-value="id"
                    multiple
                    chips
                    small-chips
                    outlined
                    dense
                  ></v-autocomplete>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('acquaintanceMethod')"
                  ></div>
                  <search
                    :placeholder="$t('acquaintanceMethod')"
                    v-model="acquaintanceMethod"
                    :error-messages="acquaintanceMethodErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :items="acquaintanceMethodItems"
                    :headers="searchHeaders"
                  ></search>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('acquaintance')"
                  ></div>
                  <search
                    :placeholder="$t('acquaintance')"
                    v-model="acquaintance"
                    :error-messages="acquaintanceErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    api="people-index/acquaintance.api.js"
                    :headers="searchHeaders"
                  ></search>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('language')"
                  ></div>
                  <search
                    :placeholder="$t('language')"
                    v-model="language"
                    :error-messages="languageErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :items="langItems"
                    :headers="searchHeaders"
                  ></search>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('birthDate')"
                  ></div>
                  <v-menu
                    v-model="birthDateMenu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="birthDate"
                        :error-messages="birthDateErrors"
                        outlined
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        clearable
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="birthDate"
                      @input="birthDateMenu = false"
                      :locale="$i18n.locale"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>

              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('shareWith')"
                  ></div>
                  <v-autocomplete
                    v-model="shareWith"
                    :items="shareWithItems"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    :error-messages="shareWithErrors"
                    item-value="id"
                    multiple
                    chips
                    small-chips
                    outlined
                    dense
                  ></v-autocomplete>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-card>
      </v-tab-item>
      <!-- contact info -->
      <v-tab-item>
        <v-card flat>
          <v-expansion-panels accordion focusable multiple>
            <v-expansion-panel>
              <v-expansion-panel-header>
                {{ `${$t("mobile")}  ` }}
                <template v-slot:actions>
                  <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <mobile @module="mobileModule = $event" :items="mobileItems" />
              </v-expansion-panel-content>
            </v-expansion-panel>

            <v-expansion-panel>
              <v-expansion-panel-header>
                {{ `${$t("email")}  ` }}
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
                {{ `${$t("phone")}` }}
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
                {{ `${$t("fax")} ` }}
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
                {{ `${$t("address")}  ` }}
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

            <v-expansion-panel>
              <v-expansion-panel-header>
                {{ $t("location") }}
                <template v-slot:actions>
                  <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <location
                  @module="locationModule = $event"
                  :items="locationItems"
                />
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card>
      </v-tab-item>
      <!-- additionalInfo -->
      <v-tab-item>
        <v-row justify="center">
          <v-col cols="12" md="4">
            <user-fields
              :types="additionalFieldsTypes"
              v-model="additionalFieldsValues"
              :touch="touchAdditional"
            />
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- log -->
      <v-tab-item>
        <log
          @module="logModule = $event"
          :items="logItems"
          v-if="logItems.length > 0"
        />
      </v-tab-item>
      <!-- archives -->
      <v-tab-item v-if="id > 0">
        <archives
          api="people-index/potential-agent.api.js"
          :recordId="id"
          :can="$can('Pg_poten_agent', 'Update')"
        />
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "@/components/card";
import userFields from "@/components/user-fields-viewer.vue";
import { required } from "vuelidate/lib/validators";
import imageSelect from "@/components/image-selector";
import search from "@/components/search";
import { mapFields } from "vuex-map-fields";
import module from "./potential-agent.store";
import promise from "promise";
// data-tables
import addressInfo from "./../company/tabs/contact/address";
import email from "./../company/tabs/contact/email";
import fax from "./../company/tabs/contact/fax";
import mobile from "./../company/tabs/contact/mobile";
import note from "./../company/tabs/contact/note";
import phone from "./../company/tabs/contact/phone";
import location from "./../company/tabs/contact/location";
import log from "./../company/tabs/log";
// others
import archives from "./../company/tabs/archive/archives";

export default {
  components: {
    card,
    search,
    imageSelect,
    addressInfo,
    email,
    fax,
    mobile,
    note,
    phone,
    location,
    log,
    archives,
    userFields
  },
  name: "potential-agent-show",

  data() {
    return {};
  },

  validations: {
    name: { required },
    nameL: { required },
    company: { required },
    isActive: { required },
    isManager: { required },
    contactSource: { required },
    acquaintanceMethod: { required },
    acquaintance: { required },
    language: { required },
    shareWith: { required },
    job: { required },
    activity: { required },
    interest: { required },
    birthDate: { required }
  },

  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Pg_poten_agent", "Create")) reject();
        if (this.id > 0 && !this.$canMsg("Pg_poten_agent", "Update")) reject();

        this.$v.$touch();
        await this.$store.dispatch(`potentialAgent/touchDatatables`);

        if (
          this.$v.$invalid ||
          (await this.invalidPhones) ||
          (await this.invalidFaxes) ||
          (await this.invalidMobiles) ||
          (await this.invalidEmails) ||
          (await this.invalidAddresses) ||
          (await this.invalidNotes) ||
          (await this.invalidLocations)
        ) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("potentialAgent/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "potential-agent-index" }))
        .catch(() => {
          //
        });
    },
    saveAndNew() {
      this.doSave()
        .then(() => this.resetForm())
        .catch(() => {
          //
        });
    },
    doDelete() {
      if (this.$canMsg("Pg_poten_agent", "Delete"))
        this.$store
          .dispatch(`agent/delete`)
          .then(() => this.$router.push({ name: "potential-agent-index" }))
          .catch(() => {
            //
          });
    },
    load() {
      this.$store.dispatch(`potentialAgent/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`potentialAgent/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`potentialAgent/loadTheInitial`);
    },
    first() {
      this.$store.dispatch(`potentialAgent/first`);
    },
    previous() {
      this.$store.dispatch(`potentialAgent/previous`);
    },
    next() {
      this.$store.dispatch(`potentialAgent/next`);
    },
    last() {
      this.$store.dispatch(`potentialAgent/last`);
    },
    setImage() {
      if (this.image) this.$store.dispatch(`potentialAgent/storeImage`);
    }
  },

  computed: {
    ...mapFields("potentialAgent", ["breadcrumbs"]),
    ...mapFields("potentialAgent", ["searchHeaders"]),
    ...mapFields("potentialAgent", ["tab"]),
    ...mapFields("potentialAgent", ["panel"]),
    ...mapFields("potentialAgent", ["messages"]),
    ...mapFields("potentialAgent", ["additionalFieldsTypes"]),
    ...mapFields("potentialAgent", ["touchAdditional"]),
    ...mapFields("potentialAgent", ["birthDateMenu"]),
    //items
    ...mapFields("potentialAgent", ["contactSourceItems"]),
    ...mapFields("potentialAgent", ["activityItems"]),
    ...mapFields("potentialAgent", ["shareWithItems"]),
    ...mapFields("potentialAgent", ["interestItems"]),
    ...mapFields("potentialAgent", ["jobItems"]),
    ...mapFields("potentialAgent", ["acquaintanceMethodItems"]),
    ...mapFields("potentialAgent", ["langItems"]),
    // form
    ...mapFields("potentialAgent", ["id"]),
    ...mapFields("potentialAgent", ["image"]),
    ...mapFields("potentialAgent", ["isActive"]),
    ...mapFields("potentialAgent", ["isManager"]),
    ...mapFields("potentialAgent", ["date"]),
    ...mapFields("potentialAgent", ["user"]),
    ...mapFields("potentialAgent", ["contactSource"]),
    ...mapFields("potentialAgent", ["company"]),
    ...mapFields("potentialAgent", ["name"]),
    ...mapFields("potentialAgent", ["nameL"]),
    ...mapFields("potentialAgent", ["job"]),
    ...mapFields("potentialAgent", ["jobTitle"]),
    ...mapFields("potentialAgent", ["activity"]),
    ...mapFields("potentialAgent", ["interest"]),
    ...mapFields("potentialAgent", ["acquaintanceMethod"]),
    ...mapFields("potentialAgent", ["acquaintance"]),
    ...mapFields("potentialAgent", ["language"]),
    ...mapFields("potentialAgent", ["birthDate"]),
    ...mapFields("potentialAgent", ["shareWith"]),
    //
    ...mapFields("potentialAgent", ["additionalFieldsValues"]),

    // module
    ...mapFields("potentialAgent", ["mobileModule"]),
    ...mapFields("potentialAgent", ["emailModule"]),
    ...mapFields("potentialAgent", ["phoneModule"]),
    ...mapFields("potentialAgent", ["faxModule"]),
    ...mapFields("potentialAgent", ["addressModule"]),
    ...mapFields("potentialAgent", ["noteModule"]),
    ...mapFields("potentialAgent", ["locationModule"]),
    ...mapFields("potentialAgent", ["logModule"]),
    // modules items
    ...mapFields("potentialAgent", ["phoneItems"]),
    ...mapFields("potentialAgent", ["faxItems"]),
    ...mapFields("potentialAgent", ["mobileItems"]),
    ...mapFields("potentialAgent", ["emailItems"]),
    ...mapFields("potentialAgent", ["addressItems"]),
    ...mapFields("potentialAgent", ["noteItems"]),
    ...mapFields("potentialAgent", ["locationItems"]),
    ...mapFields("potentialAgent", ["logItems"]),

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
    birthDateErrors() {
      const errors = [];
      if (!this.$v.birthDate.$dirty) return errors;
      !this.$v.birthDate.required && errors.push(this.$t("required"));
      return errors;
    },
    companyErrors() {
      const errors = [];
      if (!this.$v.company.$dirty) return errors;
      !this.$v.company.required && errors.push(this.$t("required"));
      return errors;
    },
    isActiveErrors() {
      const errors = [];
      if (!this.$v.isActive.$dirty) return errors;
      !this.$v.isActive.required && errors.push(this.$t("required"));
      return errors;
    },
    isManagerErrors() {
      const errors = [];
      if (!this.$v.isManager.$dirty) return errors;
      !this.$v.isManager.required && errors.push(this.$t("required"));
      return errors;
    },
    contactSourceErrors() {
      const errors = [];
      if (!this.$v.contactSource.$dirty) return errors;
      !this.$v.contactSource.required && errors.push(this.$t("required"));
      return errors;
    },
    acquaintanceMethodErrors() {
      const errors = [];
      if (!this.$v.acquaintanceMethod.$dirty) return errors;
      !this.$v.acquaintanceMethod.required && errors.push(this.$t("required"));
      return errors;
    },
    acquaintanceErrors() {
      const errors = [];
      if (!this.$v.acquaintance.$dirty) return errors;
      !this.$v.acquaintance.required && errors.push(this.$t("required"));
      return errors;
    },
    languageErrors() {
      const errors = [];
      if (!this.$v.language.$dirty) return errors;
      !this.$v.language.required && errors.push(this.$t("required"));
      return errors;
    },
    shareWithErrors() {
      const errors = [];
      if (!this.$v.shareWith.$dirty) return errors;
      !this.$v.shareWith.required && errors.push(this.$t("required"));
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
      return errors;
    },
    activityErrors() {
      const errors = [];
      if (!this.$v.activity.$dirty) return errors;
      !this.$v.activity.required && errors.push(this.$t("required"));
      return errors;
    },
    interestErrors() {
      const errors = [];
      if (!this.$v.interest.$dirty) return errors;
      !this.$v.interest.required && errors.push(this.$t("required"));
      return errors;
    },
    //
    invalidPhones() {
      return this.phoneModule
        ? this.$store.getters[`${this.phoneModule}/hasErrors`]
        : false;
    },
    invalidFaxes() {
      return this.faxModule
        ? this.$store.getters[`${this.faxModule}/hasErrors`]
        : false;
    },
    invalidMobiles() {
      return this.mobileModule
        ? this.$store.getters[`${this.mobileModule}/hasErrors`]
        : false;
    },
    invalidEmails() {
      return this.emailModule
        ? this.$store.getters[`${this.emailModule}/hasErrors`]
        : false;
    },
    invalidAddresses() {
      return this.addressModule
        ? this.$store.getters[`${this.addressModule}/hasErrors`]
        : false;
    },
    invalidNotes() {
      return this.noteModule
        ? this.$store.getters[`${this.noteModule}/hasErrors`]
        : false;
    },
    invalidLocations() {
      return this.locationModule
        ? this.$store.getters[`${this.locationModule}/hasErrors`]
        : false;
    }
  },

  created() {
    this.$store.registerModule("potentialAgent", module);
    this.id = parseInt(this.$route.params.id);
    this.loadTheInitial();
    if (this.id > 0) this.load();
    else this.resetForm();
  },

  beforeDestroy() {
    this.$store.unregisterModule("potentialAgent");
  }
};
</script>

<style></style>
