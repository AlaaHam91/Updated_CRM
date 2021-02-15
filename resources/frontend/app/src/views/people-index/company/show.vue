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
    :new-btn="$can('Pg_company', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Pg_company', 'Delete') : false"
    :breadcrumbs="breadcrumbs"
    :no="id"
    :loading="loading"
  >
    <v-row v-for="(item, i) in messages" :key="i" no-gutters>
      <v-col cols="12" md="4">
        <v-alert icon="mdi-information" color="red" type="error">
          {{ item }}
        </v-alert>
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
                invalidNotes
            "
            color="warning"
          >
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab v-text="$t('additional')"> </v-tab>
      <v-tab v-text="$t('persons')" v-if="id > 0"> </v-tab>
      <v-tab v-text="$t('documents')"> </v-tab>
      <v-tab v-text="$t('log')" v-if="id > 0"> </v-tab>
      <v-tab v-text="$t('archives')" v-if="id > 0"> </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <!-- general -->
      <v-tab-item>
        <v-card flat>
          <v-row>
            <v-col cols="12" md="3" class="d-flex flex-column align-center">
              <image-select
                v-model="image"
                class="mb-8"
                size="10rem"
                @input="setImage"
                v-if="id > 0"
              ></image-select>
              <v-switch
                :label="isActive ? $t('active') : $t('inactive')"
                v-model="isActive"
                :error-messages="isActiveErrors"
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
                  <v-text-field v-model="user" outlined readonly></v-text-field>
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
                    :item-text="$i18n.locale == 'en' ? 'latin_name' : 'name'"
                    item-value="id"
                    :items="contactSourceItems"
                    :headers="searchHeaders"
                    :error-messages="contactSourceErrors"
                  ></search>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <v-radio-group v-model="type" :error-messages="typeErrors">
                    <v-radio
                      v-for="(n, i) in types"
                      :key="i"
                      :label="n['label']"
                      :value="n['value']"
                    ></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('parent')"
                  ></div>
                  <search
                    :placeholder="$t('parent')"
                    v-model="parent"
                    :error-messages="parentErrors"
                    :item-text="$i18n.locale == 'en' ? 'latin_name' : 'name'"
                    item-value="id"
                    api="people-index/company.api.js"
                    :headers="searchHeaders"
                    :disabled="type == 'Main'"
                  ></search>
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
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :items="acquaintanceMethodItems"
                    :headers="langHeaders"
                    :error-messages="acquaintanceMethodErrors"
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
                    :item-text="$i18n.locale == 'en' ? 'latin_name' : 'name'"
                    item-value="id"
                    api="people-index/acquaintance.api"
                    :headers="searchHeaders"
                    :error-messages="acquaintanceErrors"
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
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    :items="langItems"
                    :headers="langHeaders"
                    :error-messages="languageErrors"
                  ></search>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('numberOfEmps')"
                  ></div>
                  <v-select
                    :items="numberOfEmpsItems"
                    v-model="numberOfEmps"
                    :error-messages="numberOfEmpsErrors"
                    item-text="label"
                    item-value="value"
                    dense
                    outlined
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="12">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('shareWith')"
                  ></div>
                  <v-autocomplete
                    v-model="shareWith"
                    :items="shareWithItems"
                    :error-messages="shareWithErrors"
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
              </v-row>

              <!--  -->
            </v-col>
          </v-row>
        </v-card>
      </v-tab-item>
      <!-- contact info -->
      <v-tab-item>
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
              <mobile @module="mobileModule = $event" :items="mobileItems" />
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
      </v-tab-item>
      <!-- additional info -->
      <v-tab-item>
        <additional-fields />
      </v-tab-item>
      <!-- persons -->
      <v-tab-item v-if="id > 0">
        <person @module="personModule = $event" :items="personItems" />
      </v-tab-item>
      <!-- documensts -->
      <v-tab-item eager>
        <doc
          @module="docModule = $event"
          v-if="docItems.length > 0"
          :items="docItems"
        />
      </v-tab-item>
      <!-- tickets log -->
      <v-tab-item v-if="id > 0">
        <log
          @module="logModule = $event"
          :items="logItems"
          v-if="logItems.length > 0"
        />
      </v-tab-item>
      <!-- archives -->
      <v-tab-item v-if="id > 0">
        <archives
          api="people-index/company.api.js"
          :recordId="id"
          :can="$can('Pg_company', 'Update')"
        />
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "@/components/card";
import { required, requiredIf } from "vuelidate/lib/validators";
import imageSelect from "@/components/image-selector";
import search from "@/components/search";
import { mapFields } from "vuex-map-fields";
import module from "./company.store";
import promise from "promise";
// data-tables
import addressInfo from "./tabs/contact/address.vue";
import email from "./tabs/contact/email";
import fax from "./tabs/contact/fax";
import mobile from "./tabs/contact/mobile";
import note from "./tabs/contact/note";
import phone from "./tabs/contact/phone";
import person from "./tabs/persons";
import doc from "./tabs/docs";
import log from "./tabs/log";
// others
import archives from "./tabs/archive/archives";
import additionalFields from "./tabs/additionalFields";

export default {
  components: {
    card,
    imageSelect,
    search,
    addressInfo,
    email,
    fax,
    mobile,
    note,
    phone,
    person,
    doc,
    log,
    archives,
    additionalFields
  },
  name: "company-show",

  data() {
    return {
      loading: false
    };
  },

  validations: {
    name: { required },
    nameL: { required },
    type: { required },
    contactSource: { required },
    acquaintanceMethod: { required },
    language: { required },
    acquaintance: { required },
    numberOfEmps: { required },
    isActive: { required },
    activity: { required },
    interest: { required },
    shareWith: { required },
    parent: {
      required: requiredIf(function() {
        return this.type == "Branch";
      })
    }
  },

  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Pg_company", "Create")) reject();
        if (this.id > 0 && !this.$canMsg("Pg_company", "Update")) reject();

        this.$v.$touch();
        await this.$store.dispatch(`company/touchDatatables`);

        if (
          this.$v.$invalid ||
          (await this.invalidPhones) ||
          (await this.invalidFaxes) ||
          (await this.invalidMobiles) ||
          (await this.invalidEmails) ||
          (await this.invalidAddresses) ||
          (await this.invalidNotes)
        ) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("company/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "company-index" }))
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
      if (this.$canMsg("Pg_company", "Delete"))
        this.$store
          .dispatch(`company/delete`)
          .then(() => this.$router.push({ name: "company-index" }))
          .catch(() => {
            //
          });
    },
    load() {
      this.$store.dispatch(`company/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`company/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`company/loadTheInitial`);
    },
    first() {
      this.$store.dispatch(`company/first`);
    },
    previous() {
      this.$store.dispatch(`company/previous`);
    },
    next() {
      this.$store.dispatch(`company/next`);
    },
    last() {
      this.$store.dispatch(`company/last`);
    },
    setImage() {
      if (this.image) this.$store.dispatch(`company/storeImage`);
    }
  },

  computed: {
    ...mapFields("company", ["messages"]),
    ...mapFields("company", ["breadcrumbs"]),
    ...mapFields("company", ["tab"]),
    ...mapFields("company", ["panel"]),
    ...mapFields("company", ["searchHeaders"]),
    ...mapFields("company", ["langHeaders"]),
    // items
    ...mapFields("company", ["activityItems"]),
    ...mapFields("company", ["shareWithItems"]),
    ...mapFields("company", ["numberOfEmpsItems"]),
    ...mapFields("company", ["interestItems"]),
    ...mapFields("company", ["contactSourceItems"]),
    ...mapFields("company", ["acquaintanceMethodItems"]),
    ...mapFields("company", ["langItems"]),
    // form
    ...mapFields("company", ["id"]),
    ...mapFields("company", ["image"]),
    ...mapFields("company", ["type"]),
    ...mapFields("company", ["types"]),
    ...mapFields("company", ["isActive"]),
    ...mapFields("company", ["name"]),
    ...mapFields("company", ["nameL"]),
    ...mapFields("company", ["contactSource"]),
    ...mapFields("company", ["date"]),
    ...mapFields("company", ["language"]),
    ...mapFields("company", ["parent"]),
    ...mapFields("company", ["user"]),
    ...mapFields("company", ["activity"]),
    ...mapFields("company", ["interest"]),
    ...mapFields("company", ["acquaintanceMethod"]),
    ...mapFields("company", ["acquaintance"]),
    ...mapFields("company", ["shareWith"]),
    ...mapFields("company", ["numberOfEmps"]),
    ...mapFields("company", ["phoneItems"]),
    ...mapFields("company", ["faxItems"]),
    ...mapFields("company", ["mobileItems"]),
    ...mapFields("company", ["emailItems"]),
    ...mapFields("company", ["addressItems"]),
    ...mapFields("company", ["noteItems"]),
    ...mapFields("company", ["personItems"]),
    ...mapFields("company", ["docItems"]),
    ...mapFields("company", ["logItems"]),
    // modules
    ...mapFields("company", ["phoneModule"]),
    ...mapFields("company", ["faxModule"]),
    ...mapFields("company", ["mobileModule"]),
    ...mapFields("company", ["emailModule"]),
    ...mapFields("company", ["addressModule"]),
    ...mapFields("company", ["noteModule"]),
    ...mapFields("company", ["personModule"]),
    ...mapFields("company", ["docModule"]),
    ...mapFields("company", ["logModule"]),

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
    typeErrors() {
      const errors = [];
      if (!this.$v.type.$dirty) return errors;
      !this.$v.type.required && errors.push(this.$t("required"));
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
    numberOfEmpsErrors() {
      const errors = [];
      if (!this.$v.numberOfEmps.$dirty) return errors;
      !this.$v.numberOfEmps.required && errors.push(this.$t("required"));
      return errors;
    },
    isActiveErrors() {
      const errors = [];
      if (!this.$v.isActive.$dirty) return errors;
      !this.$v.isActive.required && errors.push(this.$t("required"));
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
    shareWithErrors() {
      const errors = [];
      if (!this.$v.shareWith.$dirty) return errors;
      !this.$v.shareWith.required && errors.push(this.$t("required"));
      return errors;
    },
    parentErrors() {
      const errors = [];
      if (!this.$v.parent.$dirty) return errors;
      !this.$v.parent.required && errors.push(this.$t("required"));
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
    }
  },

  watch: {
    "$store.state.company.type": {
      handler: function(val) {
        if (val == "Main") this.parent = null;
      },
      immediate: true
    }
  },

  async created() {
    this.$store.registerModule("company", module);
    this.id = parseInt(this.$route.params.id);
    await this.loadTheInitial();
    if (this.id) this.load();
    else this.resetForm();
  },
  beforeDestroy() {
    this.$store.unregisterModule("company");
  }
};
</script>

<style></style>
