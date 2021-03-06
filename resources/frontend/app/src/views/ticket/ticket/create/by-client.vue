<template>
  <card
    :title="$t('newTicketByClient')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    new-btn
    :save-btn="$can('Ticket_by_client', 'Create')"
    :save-new-btn="$can('Ticket_by_client', 'Create')"
    :navigate="false"
    :breadcrumbs="breadcrumbs"
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
      </v-tab>
      <v-tab v-if="personInfo.person">
        {{ $t("info") }}
      </v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <!-- create ticket -->
      <v-tab-item>
        <v-row>
          <v-col cols="12">
            <person-selector
              v-model="personInfo"
              :touch="touchPersonInfo"
              @invalid="invalidPerson = $event"
            />
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="3">
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
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('branch')"
            ></div>
            <search
              v-model="branch"
              :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
              item-value="id"
              api="company-data/branch.api.js"
              :headers="searchHeaders"
              :error-messages="branchErrors"
            ></search>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('orderType')"
            ></div>
            <v-text-field
              v-model="orderType"
              disabled
              dense
              outlined
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('status')"
            ></div>
            <v-select
              :items="statusItems"
              v-model="status"
              dense
              outlined
              item-value="value"
              item-text="label"
            ></v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('country')"
            ></div>
            <search
              v-model="country"
              :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
              item-value="id"
              api="company-data/country.api.js"
              :headers="searchHeaders"
              :error-messages="countryErrors"
              returnObject
            ></search>
          </v-col>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('files')"
            ></div>
            <v-file-input
              small-chips
              chips
              multiple
              outlined
              dense
              prepend-icon="mdi-paperclip"
              v-model="files"
              @change="uploadFiles"
              :loading="uploadProgress > 0 ? true : false"
              :hint="uploadProgress > 0 ? `Upload: ${uploadProgress}%` : ``"
            >
              <template v-slot:progress>
                <v-progress-linear
                  color="primary"
                  :value="uploadProgress"
                ></v-progress-linear>
              </template>
            </v-file-input>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('title')"
            ></div>
            <v-text-field
              v-model="title"
              dense
              outlined
              :error-messages="titleErrors"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="9">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('details')"
            ></div>
            <v-textarea
              v-model="details"
              dense
              outlined
              :error-messages="detailsErrors"
            ></v-textarea>
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- info -->
      <v-tab-item v-if="personInfo.person">
        <v-expansion-panels accordion focusable multiple class="my-4">
          <v-expansion-panel>
            <v-expansion-panel-header>
              {{ `${$t("phone")}  ` }}
              <template v-slot:actions>
                <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content class="ma-0 pa-0">
              <phone
                @module="phoneModule = $event"
                :items="phoneItems"
                :editable="false"
              />
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
              <fax
                @module="faxModule = $event"
                :items="faxItems"
                :editable="false"
              />
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
                :editable="false"
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
              <email
                @module="emailModule = $event"
                :items="emailItems"
                :editable="false"
              />
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
                :editable="false"
              />
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
                :editable="false"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "@/components/card";
import personSelector from "@/components/person-selector";
import search from "@/components/search";
import { required } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import promise from "promise";
import module from "./create.store";
import addressInfo from "@/views/people-index/company/tabs/contact/address.vue";
import email from "@/views/people-index/company/tabs/contact/email.vue";
import fax from "@/views/people-index/company/tabs/contact/fax.vue";
import mobile from "@/views/people-index/company/tabs/contact/mobile.vue";
import phone from "@/views/people-index/company/tabs/contact/phone.vue";
import fileApi from "@/services/api/files/file.api";

export default {
  name: "new-ticket-by-client",

  components: {
    card,
    personSelector,
    search,
    addressInfo,
    email,
    fax,
    mobile,
    phone
  },

  data() {
    return {
      files: [],
      uploadProgress: 0
    };
  },

  validations: {
    department: { required },
    branch: { required },
    orderType: { required },
    status: { required },
    title: { required },
    details: { required },
    country: { required }
  },

  computed: {
    ...mapFields("newTicketByClient", ["breadcrumbs"]),
    ...mapFields("newTicketByClient", ["tab"]),
    ...mapFields("newTicketByClient", ["typeItems"]),
    ...mapFields("newTicketByClient", ["searchHeaders"]),
    ...mapFields("newTicketByClient", ["personApi"]),
    ...mapFields("newTicketByClient", ["statusItems"]),
    ...mapFields("newTicketByClient", ["messages"]),
    ...mapFields("newTicketByClient", ["touchPersonInfo"]),
    ...mapFields("newTicketByClient", ["invalidPerson"]),
    //form
    ...mapFields("newTicketByClient", ["personInfo"]),
    ...mapFields("newTicketByClient", ["department"]),
    ...mapFields("newTicketByClient", ["branch"]),
    ...mapFields("newTicketByClient", ["orderType"]),
    ...mapFields("newTicketByClient", ["status"]),
    ...mapFields("newTicketByClient", ["title"]),
    ...mapFields("newTicketByClient", ["details"]),
    ...mapFields("newTicketByClient", ["country"]),
    ...mapFields("newTicketByClient", ["remoteFiles"]),
    // contact info
    ...mapFields("newTicketByClient", ["phoneItems"]),
    ...mapFields("newTicketByClient", ["faxItems"]),
    ...mapFields("newTicketByClient", ["mobileItems"]),
    ...mapFields("newTicketByClient", ["emailItems"]),
    ...mapFields("newTicketByClient", ["addressItems"]),
    ...mapFields("newTicketByClient", ["locationItems"]),
    // modules
    ...mapFields("newTicketByClient", ["phoneModule"]),
    ...mapFields("newTicketByClient", ["faxModule"]),
    ...mapFields("newTicketByClient", ["mobileModule"]),
    ...mapFields("newTicketByClient", ["emailModule"]),
    ...mapFields("newTicketByClient", ["addressModule"]),
    ...mapFields("newTicketByClient", ["locationModule"]),

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
    },
    titleErrors() {
      const errors = [];
      if (!this.$v.title.$dirty) return errors;
      !this.$v.title.required && errors.push(this.$t("required"));
      return errors;
    },
    detailsErrors() {
      const errors = [];
      if (!this.$v.details.$dirty) return errors;
      !this.$v.details.required && errors.push(this.$t("required"));
      return errors;
    },
    countryErrors() {
      const errors = [];
      if (!this.$v.country.$dirty) return errors;
      !this.$v.country.required && errors.push(this.$t("required"));
      return errors;
    }
  },

  methods: {
    async uploadFiles() {
      if (this.files.length > 0)
        await this.files.forEach(file => {
          fileApi
            .storeFile(file)
            .progress(value => (this.uploadProgress = value))
            .then(res =>
              this.remoteFiles.push({
                localFile: file,
                remoteFileName: res
              })
            )
            .finally(() => (this.uploadProgress = 0));
        });
    },
    async doSave() {
      return new promise(async (resolve, reject) => {
        this.$v.$touch();
        this.touchPersonInfo = true;

        if (this.$v.$invalid || this.invalidPerson) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("newTicketByClient/createTicket")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => {
          this.$notify({
            text: this.$t("success"),
            type: "success"
          });
        })
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
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`newTicketByClient/reset`);
    }
  },

  async created() {
    await this.$store.registerModule("newTicketByClient", module);
    //to-do : fix getting person
    if (this.$route.query.person)
      this.personInfo.person = parseInt(this.$route.query.person);
    if (this.$route.query.company)
      this.personInfo.company = parseInt(this.$route.query.company);
  },

  beforeDestroy() {
    this.resetForm();
    this.$store.unregisterModule("newTicketByClient");
  }
};
</script>

<style></style>
