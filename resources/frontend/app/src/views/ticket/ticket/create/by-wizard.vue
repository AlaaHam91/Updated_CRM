<template>
  <card
    :title="$t('newTicketByWizard')"
    :hideToolBar="true"
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

    <v-stepper v-model="step">
      <v-stepper-header>
        <v-stepper-step :complete="step > 1" :step="1">
          {{ $t("requestedBy") }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="step > 2" :step="2">
          {{ $t("details") }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :step="3">
          {{ $t("confirm") }}
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card flat>
            <v-row>
              <v-col cols="12">
                <person-selector
                  v-model="personInfo"
                  :touch="touchPersonInfo"
                  @invalid="invalidPerson = $event"
                />
              </v-col>
            </v-row>

            <v-card-actions class="mt-10 d-flex justify-end">
              <v-btn color="primary" @click="nextStep" v-text="$t('next')">
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-card flat>
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
            <v-card-actions class="mt-10 d-flex justify-end">
              <v-btn text v-text="$t('previous')" @click="previousStep">
              </v-btn>
              <v-btn
                color="primary"
                :loading="createSpinner"
                @click="nextStep"
                v-text="$t('next')"
              >
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-stepper-content>

        <v-stepper-content step="3">
          <v-card flat>
            <v-row>
              <v-col>
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('smsActivate')"
                ></div>
                <v-text-field
                  v-model="activateCode"
                  :error-messages="activateCodeErrors"
                  dense
                  outlined
                ></v-text-field>
              </v-col>
            </v-row>
            <v-card-actions class="mt-10 d-flex justify-end">
              <v-btn text v-text="$t('previous')" @click="previousStep">
              </v-btn>
              <v-btn color="primary" @click="nextStep" v-text="$t('next')">
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </card>
</template>

<script>
import card from "@/components/card";
import personSelector from "@/components/person-selector";
import { required } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import promise from "promise";
import search from "@/components/search";
import module from "./create.store";
import fileApi from "@/services/api/files/file.api";

export default {
  name: "new-ticket-by-wizard",

  components: {
    card,
    search,
    personSelector
  },

  data() {
    return {
      step: 1,
      files: [],
      uploadProgress: 0,
      createSpinner: false
    };
  },

  validations: {
    department: { required },
    branch: { required },
    orderType: { required },
    status: { required },
    title: { required },
    details: { required },
    country: { required },
    activateCode: { required }
  },

  computed: {
    ...mapFields("newTicketByWizard", ["breadcrumbs"]),
    ...mapFields("newTicketByWizard", ["tab"]),
    ...mapFields("newTicketByWizard", ["typeItems"]),
    ...mapFields("newTicketByWizard", ["searchHeaders"]),
    ...mapFields("newTicketByWizard", ["personApi"]),
    ...mapFields("newTicketByWizard", ["statusItems"]),
    ...mapFields("newTicketByWizard", ["messages"]),
    ...mapFields("newTicketByWizard", ["touchPersonInfo"]),
    ...mapFields("newTicketByWizard", ["invalidPerson"]),
    //form
    ...mapFields("newTicketByWizard", ["personInfo"]),
    ...mapFields("newTicketByWizard", ["department"]),
    ...mapFields("newTicketByWizard", ["branch"]),
    ...mapFields("newTicketByWizard", ["orderType"]),
    ...mapFields("newTicketByWizard", ["status"]),
    ...mapFields("newTicketByWizard", ["title"]),
    ...mapFields("newTicketByWizard", ["details"]),
    ...mapFields("newTicketByWizard", ["country"]),
    ...mapFields("newTicketByWizard", ["remoteFiles"]),
    ...mapFields("newTicketByWizard", ["activateCode"]),

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
    },
    activateCodeErrors() {
      const errors = [];
      if (!this.$v.activateCode.$dirty) return errors;
      !this.$v.activateCode.required && errors.push(this.$t("required"));
      return errors;
    }
  },

  methods: {
    async nextStep() {
      switch (this.step) {
        case 1:
          this.touchPersonInfo = await true;

          if (await !this.invalidPerson) this.step++;
          break;
        case 2:
          this.$v.department.$touch();
          this.$v.branch.$touch();
          this.$v.status.$touch();
          this.$v.orderType.$touch();
          this.$v.title.$touch();
          this.$v.details.$touch();
          this.$v.country.$touch();
          if (
            !this.$v.department.$invalid &&
            !this.$v.branch.$invalid &&
            !this.$v.status.$invalid &&
            !this.$v.orderType.$invalid &&
            !this.$v.title.$invalid &&
            !this.$v.details.$invalid &&
            !this.$v.country.$invalid
          ) {
            this.createSpinner = true;
            this.createTicketByWizard()
              .then(() => {
                this.step++;
              })
              .catch(() => {
                //
              })
              .finally(() => (this.createSpinner = false));
          }
          break;
        case 3:
          this.$v.activateCode.$touch();
          if (!this.$v.activateCode.$invalid)
            this.activateTicket()
              .then(() => {
                this.$notify({
                  text: this.$t("success"),
                  type: "success"
                });
              })
              .catch(() => {
                //
              });
          break;

        default:
          break;
      }
    },
    previousStep() {
      this.step--;
    },
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
    async createTicketByWizard() {
      if (this.$can("Ticket_by_wizard", "Create"))
        return new promise(async (resolve, reject) => {
          this.$store
            .dispatch("newTicketByWizard/createTicketByWizard")
            .then(() => resolve())
            .catch(() => reject());
        });
    },
    activateTicket() {
      this.$store
        .dispatch("newTicketByWizard/activateTicket")
        .then(() => {
          //
        })
        .catch(() => {
          //
        });
    }
  },

  created() {
    this.$store.registerModule("newTicketByWizard", module);
  },

  beforeDestroy() {
    this.$store.dispatch("newTicketByWizard/reset");
    this.$store.unregisterModule("newTicketByWizard");
  }
};
</script>

<style></style>
