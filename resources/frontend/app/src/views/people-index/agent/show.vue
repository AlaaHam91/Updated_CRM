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
    :new-btn="$can('Pg_agent', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Pg_agent', 'Delete') : false"
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
          <v-icon color="warning" v-if="invalidGeneralTab">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab>
        {{ $t("contactInfo") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon v-if="invalidContactInfo" color="warning">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab v-text="$t('additionalInfo')"></v-tab>
      <v-tab v-if="id > 0" v-text="$t('archives')"></v-tab>
      <v-tab>
        {{ $t("agentInfo") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon v-if="invalidAgentInfo == true" color="warning">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab>
        {{ $t("products") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon v-if="productErrors.length > 0" color="warning">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab>
        {{ $t("areas") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon v-if="areaErrors.length > 0" color="warning">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab>
        {{ $t("contracts") }}
        <span style="width: 1rem;" class="mx-1">
          <v-icon v-if="contractErrors.length > 0" color="warning">
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab v-text="$t('delegates')"></v-tab>
      <v-tab v-text="$t('log')"></v-tab>
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
                v-model="isManager"
                :error-messages="isManagerErrors"
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
                    :error-messages="nameLaErrors"
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
                    :error-messages="jobErrors"
                    :placeholder="$t('job')"
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
                    :error-messages="activityErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
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
                    :error-messages="interestErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
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
                        dense
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
                    :error-messages="shareWithErrors"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
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

      <!-- archives -->
      <v-tab-item v-if="id > 0">
        <archives
          api="people-index/agent.api.js"
          :recordId="id"
          :can="$can('Pg_agent', 'Update')"
        />
      </v-tab-item>
      <!-- agent info -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <div class="text-body-1 black--text mb-2" v-text="$t('code')"></div>
            <v-text-field
              :placeholder="$t('code')"
              :error-messages="codeErrors"
              v-model="code"
              outlined
              autocomplete="off"
              spellcheck="off"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('accountNo')"
            ></div>
            <v-text-field
              :placeholder="$t('accountNo')"
              v-model="accountNo"
              :error-messages="accountNoErrors"
              outlined
              autocomplete="off"
              spellcheck="off"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('username')"
            ></div>
            <v-text-field
              :placeholder="$t('username')"
              v-model="username"
              :error-messages="usernameErrors"
              outlined
              autocomplete="off"
              spellcheck="off"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('password')"
            ></div>
            <v-text-field
              :placeholder="$t('password')"
              v-model="password"
              :error-messages="passwordErrors"
              outlined
              autocomplete="off"
              spellcheck="off"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('email')"
            ></div>
            <v-text-field
              :placeholder="$t('email')"
              v-model="email"
              :error-messages="emailErrors"
              outlined
              autocomplete="off"
              spellcheck="off"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-btn v-text="$t('resetPass')" @click="resetPass"></v-btn>
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- products -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('products')"
            ></div>
            <v-autocomplete
              v-model="product"
              :items="productItems"
              :error-messages="productErrors"
              :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
              item-value="id"
              multiple
              chips
              small-chips
              outlined
              dense
            ></v-autocomplete>
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- areas -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('areas')"
            ></div>
            <v-autocomplete
              v-model="area"
              :items="areaItems"
              :error-messages="areaErrors"
              :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
              item-value="id"
              multiple
              chips
              small-chips
              outlined
              dense
            ></v-autocomplete>
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- contracts -->
      <v-tab-item>
        <contract @module="contractModule = $event" :items="contractItems" />
        <div
          class="red--text text-body-2"
          v-for="e in contractErrors"
          :key="e"
          v-text="e"
        ></div>
      </v-tab-item>
      <!-- delegates -->
      <v-tab-item>
        <v-row>
          <v-col cols="2">
            <v-btn
              disabled
              @click="addDelegate"
              v-text="$t('addDelegate')"
            ></v-btn>
          </v-col>
        </v-row>
        <delegate @moduel="delegateModule = $event" />
      </v-tab-item>
      <!-- log -->
      <v-tab-item>
        <log
          @module="logModule = $event"
          :items="logItems"
          v-if="logItems.length > 0"
        />
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "@/components/card";
import imageSelect from "@/components/image-selector";
import search from "@/components/search";
import userFields from "@/components/user-fields-viewer.vue";
import { required, alphaNum } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import module from "./agent.store";
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
import delegate from "./tabs/delegate";
import contract from "./tabs/contract";
// others
import archives from "./../company/tabs/archive/archives";

export default {
  components: {
    card,
    userFields,
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
    delegate,
    contract
  },
  name: "agent-show",

  data() {
    return {};
  },

  validations: {
    name: { required },
    nameL: { required },
    birthDate: { required },
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
    product: { required },
    area: { required },
    contractItems: { required },
    code: { required, alphaNum },
    accountNo: { required, alphaNum },
    username: { required, alphaNum },
    email: { required },
    password: { required }
  },

  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Pg_agent", "Create")) reject();
        if (this.id > 0 && !this.$canMsg("Pg_agent", "Update")) reject();

        this.$v.$touch();

        await this.$store.dispatch(`agent/loadFromModules`);
        await this.$store.dispatch(`agent/touchDatatables`);

        if (
          this.$v.$invalid ||
          (await this.invalidPhones) ||
          (await this.invalidFaxes) ||
          (await this.invalidMobiles) ||
          (await this.invalidEmails) ||
          (await this.invalidAddresses) ||
          (await this.invalidNotes) ||
          (await this.invalidLocations) ||
          (await this.invalidContracts)
        ) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("agent/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "agent-index" }))
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
      if (this.$canMsg("Pg_agent", "Delete"))
        this.$store
          .dispatch(`agent/delete`)
          .then(() => this.$router.push({ name: "agent-index" }))
          .catch(() => {
            //
          });
    },
    load() {
      this.$store.dispatch(`agent/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`agent/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`agent/loadTheInitial`);
    },
    addDelegate() {
      //
    },
    first() {
      this.$store.dispatch(`agent/first`);
    },
    previous() {
      this.$store.dispatch(`agent/previous`);
    },
    next() {
      this.$store.dispatch(`agent/next`);
    },
    last() {
      this.$store.dispatch(`agent/last`);
    },
    setImage() {
      if (this.image) this.$store.dispatch(`agent/storeImage`);
    },
    resetPass() {
      //
    }
  },

  computed: {
    ...mapFields("agent", ["breadcrumbs"]),
    ...mapFields("agent", ["searchHeaders"]),
    ...mapFields("agent", ["tab"]),
    ...mapFields("agent", ["birthDateMenu"]),
    ...mapFields("agent", ["panel"]),
    ...mapFields("agent", ["messages"]),
    ...mapFields("agent", ["additionalFieldsTypes"]),
    ...mapFields("agent", ["touchAdditional"]),
    // form
    ...mapFields("agent", ["id"]),
    ...mapFields("agent", ["image"]),
    ...mapFields("agent", ["isActive"]),
    ...mapFields("agent", ["isManager"]),
    ...mapFields("agent", ["date"]),
    ...mapFields("agent", ["user"]),
    ...mapFields("agent", ["contactSource"]),
    ...mapFields("agent", ["company"]),
    ...mapFields("agent", ["name"]),
    ...mapFields("agent", ["nameL"]),
    ...mapFields("agent", ["job"]),
    ...mapFields("agent", ["jobTitle"]),
    ...mapFields("agent", ["activity"]),
    ...mapFields("agent", ["interest"]),
    ...mapFields("agent", ["acquaintanceMethod"]),
    ...mapFields("agent", ["acquaintance"]),
    ...mapFields("agent", ["language"]),
    ...mapFields("agent", ["birthDate"]),
    ...mapFields("agent", ["shareWith"]),
    ...mapFields("agent", ["product"]),
    ...mapFields("agent", ["area"]),
    ...mapFields("agent", ["code"]),
    ...mapFields("agent", ["accountNo"]),
    ...mapFields("agent", ["username"]),
    ...mapFields("agent", ["email"]),
    ...mapFields("agent", ["password"]),
    ...mapFields("agent", ["agentInfoInvalid"]),
    // items
    ...mapFields("agent", ["jobItems"]),
    ...mapFields("agent", ["interestItems"]),
    ...mapFields("agent", ["activityItems"]),
    ...mapFields("agent", ["shareWithItems"]),
    ...mapFields("agent", ["productItems"]),
    ...mapFields("agent", ["areaItems"]),
    ...mapFields("agent", ["contactSourceItems"]),
    ...mapFields("agent", ["acquaintanceMethodItems"]),
    ...mapFields("agent", ["langItems"]),
    // modules
    ...mapFields("agent", ["mobileModule"]),
    ...mapFields("agent", ["emailModule"]),
    ...mapFields("agent", ["phoneModule"]),
    ...mapFields("agent", ["faxModule"]),
    ...mapFields("agent", ["addressModule"]),
    ...mapFields("agent", ["noteModule"]),
    ...mapFields("agent", ["locationModule"]),
    ...mapFields("agent", ["logModule"]),
    ...mapFields("agent", ["contractModule"]),
    ...mapFields("agent", ["delegateModule"]),
    // modules items
    ...mapFields("agent", ["phoneItems"]),
    ...mapFields("agent", ["faxItems"]),
    ...mapFields("agent", ["mobileItems"]),
    ...mapFields("agent", ["emailItems"]),
    ...mapFields("agent", ["addressItems"]),
    ...mapFields("agent", ["noteItems"]),
    ...mapFields("agent", ["locationItems"]),
    ...mapFields("agent", ["contractItems"]),
    ...mapFields("agent", ["additionalFieldsValues"]),
    ...mapFields("agent", ["logItems"]),

    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.required && errors.push(this.$t("required"));
      return errors;
    },
    nameLaErrors() {
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
    areaErrors() {
      const errors = [];
      if (!this.$v.area.$dirty) return errors;
      !this.$v.area.required && errors.push(this.$t("required"));
      return errors;
    },
    productErrors() {
      const errors = [];
      if (!this.$v.product.$dirty) return errors;
      !this.$v.product.required && errors.push(this.$t("required"));
      return errors;
    },
    codeErrors() {
      const errors = [];
      if (!this.$v.code.$dirty) return errors;
      !this.$v.code.required && errors.push(this.$t("required"));
      return errors;
    },
    accountNoErrors() {
      const errors = [];
      if (!this.$v.accountNo.$dirty) return errors;
      !this.$v.accountNo.required && errors.push(this.$t("required"));
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
    },
    passwordErrors() {
      const errors = [];
      if (!this.$v.accountNo.$dirty) return errors;
      !this.$v.accountNo.required && errors.push(this.$t("required"));
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
    },
    invalidContracts() {
      return this.contractModule
        ? this.$store.getters[`${this.contractModule}/hasErrors`]
        : false;
    },
    contractErrors() {
      const errors = [];
      if (!this.$v.contractItems.$dirty) return errors;
      !this.$v.contractItems.required && errors.push(this.$t("required"));
      this.invalidContracts && errors.push(this.$t("checkInputs"));
      return errors;
    },
    invalidGeneralTab() {
      return (
        this.nameErrors.length > 0 ||
        this.nameLaErrors.length > 0 ||
        this.birthDateErrors.length > 0 ||
        this.companyErrors.length > 0 ||
        this.isActiveErrors.length > 0 ||
        this.isManagerErrors.length > 0 ||
        this.contactSourceErrors.length > 0 ||
        this.acquaintanceMethodErrors.length > 0 ||
        this.acquaintanceErrors.length > 0 ||
        this.languageErrors.length > 0 ||
        this.shareWithErrors.length > 0 ||
        this.jobErrors.length > 0 ||
        this.jobTitleErrors.length > 0 ||
        this.activityErrors.length > 0 ||
        this.interestErrors.length > 0
      );
    },
    invalidContactInfo() {
      return (
        this.invalidPhones ||
        this.invalidFaxes ||
        this.invalidMobiles ||
        this.invalidEmails ||
        this.invalidAddresses ||
        this.invalidNotes ||
        this.invalidLocations ||
        this.invalidContracts
      );
    },
    invalidAgentInfo() {
      return (
        this.codeErrors.length > 0 ||
        this.accountNoErrors.length > 0 ||
        this.usernameErrors.length > 0 ||
        this.emailErrors.length > 0 ||
        this.passwordErrors.length > 0
      );
    }
  },

  created() {
    this.$store.registerModule("agent", module);
    this.id = parseInt(this.$route.params.id);
    this.loadTheInitial();
    if (this.id > 0) this.load();
    else this.resetForm();
  },

  beforeDestroy() {
    this.$store.unregisterModule("agent");
  }
};
</script>

<style></style>
