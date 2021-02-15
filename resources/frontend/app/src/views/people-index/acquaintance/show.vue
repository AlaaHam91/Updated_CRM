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
    :new-btn="$can('Pg_acquaintance', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Pg_acquaintance', 'Delete') : false"
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
                invalidLocations ||
                invalidWebsites
            "
            color="warning"
          >
            mdi-information
          </v-icon>
        </span>
      </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <!-- general -->
      <v-tab-item>
        <v-card flat>
          <v-row>
            <v-col cols="12" md="3">
              <image-select
                v-if="id > 0"
                v-model="image"
                class="mb-4"
                size="10rem"
                @input="setImage"
              ></image-select>
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
                    v-text="$t('name')"
                  ></div>
                  <v-text-field
                    v-model="name"
                    :error-messages="nameErrors"
                    :placeholder="$t('name')"
                    outlined
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('nameL')"
                  ></div>
                  <v-text-field
                    v-model="nameL"
                    :error-messages="nameLErrors"
                    :placeholder="$t('nameL')"
                    outlined
                  ></v-text-field>
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
                    item-value="id"
                    hide-selected
                    multiple
                    chips
                    small-chips
                    outlined
                    dense
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('nationality')"
                  ></div>
                  <v-autocomplete
                    v-model="nationality"
                    :items="nationalityItems"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :error-messages="nationalityErrors"
                    chips
                    small-chips
                    outlined
                    dense
                    clearable
                  ></v-autocomplete>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('placeOfWork')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('placeOfWork')"
                    v-model="placeOfWork"
                    :error-messages="placeOfWorkErrors"
                    outlined
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('job')"
                  ></div>
                  <v-select
                    :items="jobItems"
                    v-model="job"
                    :placeholder="$t('job')"
                    :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                    item-value="id"
                    clearable
                    outlined
                    dense
                    :error-messages="jobErrors"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col>
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('bankAccount')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('bankAccount')"
                    v-model="bankAccount"
                    :error-messages="bankAccountErrors"
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col>
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('bankNo')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('bankNo')"
                    v-model="bankNo"
                    :error-messages="bankNoErrors"
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('haveCommission')"
                  ></div>
                  <v-switch v-model="haveCommission"></v-switch>
                </v-col>
                <v-col>
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('commissionVal')"
                  ></div>
                  <v-text-field
                    :disabled="!haveCommission"
                    :placeholder="$t('commissionVal')"
                    v-model="commissionVal"
                    type="number"
                    min="0"
                    outlined
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('delegate')"
                  ></div>
                  <v-select
                    :items="delegateItems"
                    v-model="delegate"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :placeholder="$t('delegate')"
                    :error-messages="delegateErrors"
                    outlined
                    dense
                  ></v-select>
                </v-col>
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
                    v-text="$t('language')"
                  ></div>
                  <search
                    :placeholder="$t('language')"
                    v-model="language"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :items="langItems"
                    :headers="searchHeaders"
                    :error-messages="langErrors"
                  ></search>
                </v-col>
              </v-row>

              <!--  -->
            </v-col>
          </v-row>
        </v-card>
      </v-tab-item>
      <!-- contact info -->
      <v-tab-item>
        <v-expansion-panels
          accordion
          focusable
          multiple
          class="my-4"
          v-model="panel"
        >
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
          <v-expansion-panel>
            <v-expansion-panel-header>
              {{ $t("website") }}
              <template v-slot:actions>
                <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <website @module="websiteModule = $event" :items="websiteItems" />
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "@/components/card";
import { required } from "vuelidate/lib/validators";
import imageSelect from "@/components/image-selector";
import search from "@/components/search";
import { mapFields } from "vuex-map-fields";
import module from "./acquaintance.store";
import promise from "promise";
// data-tables
import addressInfo from "./../company/tabs/contact/address";
import email from "./../company/tabs/contact/email";
import fax from "./../company/tabs/contact/fax";
import mobile from "./../company/tabs/contact/mobile";
import note from "./../company/tabs/contact/note";
import phone from "./../company/tabs/contact/phone";
import location from "./../company/tabs/contact/location";
import website from "./tabs/website";

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
    website
  },
  name: "acquaintance-show",

  data() {
    return {};
  },

  validations: {
    name: { required },
    nameL: { required },
    placeOfWork: { required },
    bankAccount: { required },
    bankNo: { required },
    nationality: { required },
    birthDate: { required },
    language: { required },
    job: { required },
    delegate: { required }
  },

  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Pg_acquaintance", "Create"))
          reject();
        if (this.id > 0 && !this.$canMsg("Pg_acquaintance", "Update")) reject();
        this.$v.$touch();
        await this.$store.dispatch(`acquaintance/touchDatatables`);

        if (
          this.$v.$invalid ||
          (await this.invalidPhones) ||
          (await this.invalidFaxes) ||
          (await this.invalidMobiles) ||
          (await this.invalidEmails) ||
          (await this.invalidAddresses) ||
          (await this.invalidNotes) ||
          (await this.invalidLocations) ||
          (await this.invalidWebsites)
        ) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("acquaintance/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "acquaintance-index" }))
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
      this.$store
        .dispatch(`acquaintance/delete`)
        .then(() => this.$router.push({ name: "acquaintance-index" }))
        .catch(() => {
          //
        });
    },
    load() {
      this.$store.dispatch(`acquaintance/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`acquaintance/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`acquaintance/loadTheInitial`);
    },
    first() {
      this.$store.dispatch(`acquaintance/first`);
    },
    previous() {
      this.$store.dispatch(`acquaintance/previous`);
    },
    next() {
      this.$store.dispatch(`acquaintance/next`);
    },
    last() {
      this.$store.dispatch(`acquaintance/last`);
    },
    setImage() {
      if (this.image) this.$store.dispatch(`acquaintance/storeImage`);
    }
  },

  computed: {
    ...mapFields("acquaintance", ["breadcrumbs"]),
    ...mapFields("acquaintance", ["birthDateMenu"]),
    ...mapFields("acquaintance", ["searchHeaders"]),
    ...mapFields("acquaintance", ["messages"]),
    ...mapFields("acquaintance", ["tab"]),
    ...mapFields("acquaintance", ["panel"]),
    //lang items
    ...mapFields("acquaintance", ["langItems"]),
    ...mapFields("acquaintance", ["nationalityItems"]),
    ...mapFields("acquaintance", ["jobItems"]),
    ...mapFields("acquaintance", ["delegateItems"]),
    ...mapFields("acquaintance", ["shareWithItems"]),
    //form
    ...mapFields("acquaintance", ["id"]),
    ...mapFields("acquaintance", ["image"]),
    ...mapFields("acquaintance", ["name"]),
    ...mapFields("acquaintance", ["nameL"]),
    ...mapFields("acquaintance", ["date"]),
    ...mapFields("acquaintance", ["user"]),
    ...mapFields("acquaintance", ["shareWith"]),
    ...mapFields("acquaintance", ["nationality"]),
    ...mapFields("acquaintance", ["placeOfWork"]),
    ...mapFields("acquaintance", ["job"]),
    ...mapFields("acquaintance", ["bankAccount"]),
    ...mapFields("acquaintance", ["bankNo"]),
    ...mapFields("acquaintance", ["haveCommission"]),
    ...mapFields("acquaintance", ["commissionVal"]),
    ...mapFields("acquaintance", ["delegate"]),
    ...mapFields("acquaintance", ["birthDate"]),
    ...mapFields("acquaintance", ["language"]),
    //modules
    ...mapFields("acquaintance", ["phoneModule"]),
    ...mapFields("acquaintance", ["faxModule"]),
    ...mapFields("acquaintance", ["mobileModule"]),
    ...mapFields("acquaintance", ["emailModule"]),
    ...mapFields("acquaintance", ["addressModule"]),
    ...mapFields("acquaintance", ["noteModule"]),
    ...mapFields("acquaintance", ["locationModule"]),
    ...mapFields("acquaintance", ["websiteModule"]),
    //modules items
    ...mapFields("acquaintance", ["phoneItems"]),
    ...mapFields("acquaintance", ["faxItems"]),
    ...mapFields("acquaintance", ["mobileItems"]),
    ...mapFields("acquaintance", ["emailItems"]),
    ...mapFields("acquaintance", ["addressItems"]),
    ...mapFields("acquaintance", ["noteItems"]),
    ...mapFields("acquaintance", ["locationItems"]),
    ...mapFields("acquaintance", ["websiteItems"]),

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
    nationalityErrors() {
      const errors = [];
      if (!this.$v.nationality.$dirty) return errors;
      !this.$v.nationality.required && errors.push(this.$t("required"));
      return errors;
    },
    birthDateErrors() {
      const errors = [];
      if (!this.$v.birthDate.$dirty) return errors;
      !this.$v.birthDate.required && errors.push(this.$t("required"));
      return errors;
    },
    langErrors() {
      const errors = [];
      if (!this.$v.language.$dirty) return errors;
      !this.$v.language.required && errors.push(this.$t("required"));
      return errors;
    },
    jobErrors() {
      const errors = [];
      if (!this.$v.job.$dirty) return errors;
      !this.$v.job.required && errors.push(this.$t("required"));
      return errors;
    },
    delegateErrors() {
      const errors = [];
      if (!this.$v.delegate.$dirty) return errors;
      !this.$v.delegate.required && errors.push(this.$t("required"));
      return errors;
    },
    placeOfWorkErrors() {
      const errors = [];
      if (!this.$v.placeOfWork.$dirty) return errors;
      !this.$v.placeOfWork.required && errors.push(this.$t("required"));
      return errors;
    },
    bankAccountErrors() {
      const errors = [];
      if (!this.$v.bankAccount.$dirty) return errors;
      !this.$v.bankAccount.required && errors.push(this.$t("required"));
      return errors;
    },
    bankNoErrors() {
      const errors = [];
      if (!this.$v.bankNo.$dirty) return errors;
      !this.$v.bankNo.required && errors.push(this.$t("required"));
      return errors;
    },
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
    },
    invalidWebsites() {
      return this.websiteModule
        ? this.$store.getters[`${this.websiteModule}/hasErrors`]
        : false;
    }
  },

  created() {
    this.$store.registerModule("acquaintance", module);
    this.id = parseInt(this.$route.params.id);
    this.loadTheInitial();
    if (this.id > 0) this.load();
    else this.resetForm();
  },

  beforeDestroy() {
    this.$store.unregisterModule("acquaintance");
  }
};
</script>

<style></style>
