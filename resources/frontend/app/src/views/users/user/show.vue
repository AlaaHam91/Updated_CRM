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
    :new-btn="$can('Cp_user_user', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Cp_user_user', 'Delete') : false"
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
    <v-row>
      <v-col cols="12" md="3" class="d-flex flex-column align-center">
        <image-select
          v-if="id > 0"
          v-model="image"
          class="mb-4"
          size="10rem"
          @input="setImage"
        ></image-select>

        <v-switch
          :label="isActive ? $t('active') : $t('inactive')"
          v-model="isActive"
        ></v-switch>
      </v-col>
      <v-col cols="12" md="8">
        <v-row>
          <v-col cols="12" md="2">
            <div
              class="text-body-1 black--text mb-2 text-no-wrap"
              v-text="$t('employeeNo')"
            ></div>
            <v-text-field v-model="userId" outlined readonly></v-text-field>
          </v-col>
          <v-col cols="12" md="4">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('accountNumber')"
            ></div>
            <v-text-field
              :placeholder="$t('accountNumber')"
              v-model="accountNumber"
              outlined
              autocomplete="off"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <div class="text-body-1 black--text mb-2" v-text="$t('idNo')"></div>
            <v-text-field
              :placeholder="$t('idNo')"
              v-model="idNumber"
              outlined
              type="number"
              min="0"
              autocomplete="off"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <div class="text-body-1 black--text mb-2" v-text="$t('name')"></div>
            <v-text-field
              :placeholder="$t('name')"
              v-model="name"
              outlined
              :error-messages="nameErrors"
              autocomplete="off"
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
              outlined
              :error-messages="nameLErrors"
              autocomplete="off"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('specialization')"
            ></div>
            <v-autocomplete
              v-model="specializations"
              :items="specializationItems"
              :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
              item-value="id"
              clearable
              hide-selected
              multiple
              chips
              small-chips
              outlined
              dense
              autocomplete="off"
            ></v-autocomplete>
            <!-- <search
              :placeholder="$t('specialization')"
              v-model="specialization"
              :item-text="$i18n.locale == 'en' ? 'latin_name' : 'name'"
              item-value="id"
              api="control-panel/specialization.api.js"
              :headers="searchHeaders"
            ></search> -->
          </v-col>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('managerialLevel')"
            ></div>
            <v-select
              :items="managerialLevels"
              v-model="managerialLevel"
              :placeholder="$t('managerialLevel')"
              :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
              item-value="id"
              outlined
              dense
              clearable
              autocomplete="off"
            ></v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('userName')"
            ></div>
            <v-text-field
              :placeholder="$t('userName')"
              v-model="userName"
              outlined
              :error-messages="userNameErrors"
              autocomplete="off"
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
              type="password"
              outlined
              :error-messages="passwordErrors"
              autocomplete="off"
            ></v-text-field>
          </v-col>
        </v-row>
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
                invalidfaxes ||
                invalidmobiles ||
                invalidemails ||
                invalidaddresses ||
                invalidnotes ||
                invalidWebsites
            "
            color="warning"
          >
            mdi-information
          </v-icon>
        </span>
      </v-tab>
      <v-tab>{{ $t("hierarchy") }}</v-tab>
      <v-tab v-text="$t('permissionBranchToDepartment')"></v-tab>
      <v-tab v-text="$t('permissionDepartmentToBranch')"></v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <!-- general -->
      <v-tab-item>
        <v-card flat>
          <v-row>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('branch')"
              ></div>
              <v-select
                :items="branches"
                v-model="branch"
                :placeholder="$t('branch')"
                :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                item-value="id"
                outlined
                dense
                clearable
                :error-messages="branchErrors"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('management')"
              ></div>
              <v-select
                :items="managements"
                v-model="management"
                :placeholder="$t('management')"
                :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                item-value="id"
                outlined
                dense
                clearable
                :error-messages="managementErrors"
              ></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('department')"
              ></div>
              <v-select
                :items="departments"
                v-model="department"
                :placeholder="$t('department')"
                :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                item-value="id"
                outlined
                dense
                clearable
                :error-messages="departmentErrors"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('directManager')"
              ></div>
              <v-select
                :items="managers"
                v-model="manager"
                :placeholder="$t('directManager')"
                :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                item-value="id"
                outlined
                dense
                clearable
              ></v-select>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('job')"
              ></div>
              <v-select
                :items="jobs"
                v-model="job"
                :placeholder="$t('job')"
                :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                item-value="id"
                outlined
                dense
                clearable
                :error-messages="jobErrors"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('jobTitle')"
              ></div>
              <v-text-field
                :placeholder="$t('jobTitle')"
                v-model="jobTitle"
                outlined
                autocomplete="off"
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
                outlined
                :error-messages="emailErrors"
                autocomplete="off"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('ticketsEmail')"
              ></div>
              <v-text-field
                :placeholder="$t('ticketsEmail')"
                v-model="ticketsEmail"
                outlined
                autocomplete="off"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" md="12">
              <div
                class="text-body-1 black--text mb-2"
                v-text="$t('notes')"
              ></div>
              <v-textarea
                :placeholder="$t('notes')"
                v-model="notes"
                outlined
                no-resize
                autocomplete="off"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-card>
      </v-tab-item>
      <!-- contact info -->
      <v-tab-item>
        <v-card flat>
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
                <emailInfo @module="emailModule = $event" :items="emailItems" />
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
                <website
                  @module="websiteModule = $event"
                  :items="websiteItems"
                />
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card>
      </v-tab-item>
      <!--hierarchy-->
      <v-tab-item>
        <v-card max-width="500">
          <v-sheet class="pa-4 transparent lighten-2">
            <v-text-field
              v-model="search"
              :label="$t('search')"
              hide-details
              clearable
              clear-icon="mdi-close-circle-outline"
              color="primary"
              background-color="inputBack"
              dense
              outlined
            ></v-text-field>
          </v-sheet>
          <v-card-text>
            <v-treeview
              v-model="hierarchy"
              :items="hierarchies"
              color="primary"
              background-color="inputBack"
              dense
              selection-type="leaf"
              activatable
              :search="search"
              :search-input.sync="search"
              active-class="grey lighten-4 indigo--text"
              open-all
              selectable
            >
            </v-treeview>
          </v-card-text>
        </v-card>
      </v-tab-item>
      <!--Branch permissions-->
      <v-tab-item>
        <v-card flat>
          <v-expansion-panels
            accordion
            focusable
            multiple
            class="my-4"
            v-model="permissionsPanel"
          >
            <v-expansion-panel v-for="(b, i) in branchRoles" :key="i">
              <v-expansion-panel-header>
                {{ $i18n.locale == "en" ? b.nameL : b.name }}
                <template v-slot:actions>
                  <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content class="ma-0 pa-0">
                <v-data-table
                  :headers="headers"
                  :items="b.departments"
                  hide-default-footer
                  fixed-header
                ></v-data-table>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card>
      </v-tab-item>
      <!--department permissions-->
      <v-tab-item>
        <v-card flat>
          <v-expansion-panels
            accordion
            focusable
            multiple
            class="my-4"
            v-model="permissionsPanel"
          >
            <v-expansion-panel v-for="(d, i) in departmentRoles" :key="i">
              <v-expansion-panel-header>
                {{ $i18n.locale == "en" ? d.nameL : d.name }}
                <template v-slot:actions>
                  <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content class="ma-0 pa-0">
                <v-data-table
                  :headers="headers"
                  :items="d.branches"
                  hide-default-footer
                  fixed-header
                ></v-data-table>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required, email } from "vuelidate/lib/validators";
import imageSelect from "./../../../components/image-selector";
import { mapFields } from "vuex-map-fields";
// data-tables
import addressInfo from "./../../people-index/company/tabs/contact/address";
import emailInfo from "./../../people-index/company/tabs/contact/email";
import fax from "./../../people-index/company/tabs/contact/fax";
import mobile from "./../../people-index/company/tabs/contact/mobile";
import phone from "./../../people-index/company/tabs/contact/phone";
import location from "./../../people-index/company/tabs/contact/location";
import website from "./../../people-index/acquaintance/tabs/website";
import module from "./user.store";
import promise from "promise";

export default {
  components: {
    card,
    imageSelect,
    addressInfo,
    emailInfo,
    fax,
    mobile,
    phone,
    location,
    website
  },
  name: "user-show",

  data() {
    return {
      search: null
    };
  },

  validations: {
    name: {
      required
    },
    nameL: {
      required
    },
    password: { required },
    userName: { required },
    branch: { required },
    management: { required },
    department: { required },
    job: { required },
    email: { required, email },
    ticketsEmail: { email }
  },

  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Cp_user_user", "Create")) reject();
        if (this.id > 0 && !this.$canMsg("Cp_user_user", "Update")) reject();
        this.$v.$touch();
        await this.$store.dispatch(`user/touchDatatables`);

        if (
          this.$v.$invalid ||
          (await this.invalidPhones) ||
          (await this.invalidfaxes) ||
          (await this.invalidmobiles) ||
          (await this.invalidemails) ||
          (await this.invalidaddresses) ||
          (await this.invalidnotes) ||
          (await this.invalidWebsites)
        ) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("user/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "user-index" }))
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
        .dispatch(`user/delete`)
        .then(() => this.$router.push({ name: "user-index" }))
        .catch(() => {
          //
        });
    },
    load() {
      this.$store.dispatch(`user/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`user/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`user/loadTheInitial`);
    },
    first() {
      this.userId = this.$store.dispatch(`user/first`);
    },
    previous() {
      this.$store.dispatch(`user/previous`);
    },
    next() {
      this.$store.dispatch(`user/next`);
    },
    last() {
      this.$store.dispatch(`user/last`);
    },
    setImage() {
      if (this.image) this.$store.dispatch(`user/storeImage`);
    }
  },

  computed: {
    ...mapFields("user", ["panel"]),
    ...mapFields("user", ["permissionsPanel"]),
    ...mapFields("user", ["headers"]),
    ...mapFields("user", ["messages"]),
    ...mapFields("user", ["breadcrumbs"]),
    ...mapFields("user", ["tab"]),
    ...mapFields("user", ["id"]),
    ...mapFields("user", ["userId"]),
    ...mapFields("user", ["image"]),
    ...mapFields("user", ["isActive"]),
    ...mapFields("user", ["accountNumber"]),
    ...mapFields("user", ["idNumber"]),
    ...mapFields("user", ["management"]),
    ...mapFields("user", ["branch"]),
    ...mapFields("user", ["department"]),
    ...mapFields("user", ["name"]),
    ...mapFields("user", ["nameL"]),
    ...mapFields("user", ["manager"]),
    ...mapFields("user", ["job"]),
    ...mapFields("user", ["jobTitle"]),
    ...mapFields("user", ["ticketsEmail"]),
    ...mapFields("user", ["notes"]),
    ...mapFields("user", ["hierarchy"]),
    ...mapFields("user", ["userName"]),
    ...mapFields("user", ["password"]),
    ...mapFields("user", ["email"]),
    ...mapFields("user", ["specializations"]),
    ...mapFields("user", ["managerialLevel"]),
    ...mapFields("user", ["managerialLevels"]),
    ...mapFields("user", ["branches"]),
    ...mapFields("user", ["departments"]),
    ...mapFields("user", ["jobs"]),
    ...mapFields("user", ["managers"]),
    ...mapFields("user", ["managements"]),
    ...mapFields("user", ["phoneModule"]),
    ...mapFields("user", ["faxModule"]),
    ...mapFields("user", ["addressModule"]),
    ...mapFields("user", ["noteModule"]),
    ...mapFields("user", ["locationModule"]),
    ...mapFields("user", ["departmentRoles"]),
    ...mapFields("user", ["branchRoles"]),
    ...mapFields("user", ["specializationItems"]),
    ...mapFields("user", ["phoneItems"]),
    ...mapFields("user", ["faxItems"]),
    ...mapFields("user", ["mobileItems"]),
    ...mapFields("user", ["emailItems"]),
    ...mapFields("user", ["addressItems"]),
    ...mapFields("user", ["noteItems"]),
    ...mapFields("user", ["locationItems"]),
    ...mapFields("user", ["websiteItems"]),
    ...mapFields("user", ["hierarchies"]),

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
    passwordErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.required && errors.push(this.$t("required"));
      return errors;
    },
    userNameErrors() {
      const errors = [];
      if (!this.$v.userName.$dirty) return errors;
      !this.$v.userName.required && errors.push(this.$t("required"));
      return errors;
    },
    branchErrors() {
      const errors = [];
      if (!this.$v.branch.$dirty) return errors;
      !this.$v.branch.required && errors.push(this.$t("required"));
      return errors;
    },
    managementErrors() {
      const errors = [];
      if (!this.$v.management.$dirty) return errors;
      !this.$v.management.required && errors.push(this.$t("required"));
      return errors;
    },
    departmentErrors() {
      const errors = [];
      if (!this.$v.department.$dirty) return errors;
      !this.$v.department.required && errors.push(this.$t("required"));
      return errors;
    },
    jobErrors() {
      const errors = [];
      if (!this.$v.job.$dirty) return errors;
      !this.$v.job.required && errors.push(this.$t("required"));
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.required && errors.push(this.$t("required"));
      !this.$v.email.email && errors.push(this.$t("emailNotValid"));

      return errors;
    },
    ticketsEmailErrors() {
      const errors = [];
      if (!this.$v.ticketsEmail.$dirty) return errors;
      !this.$v.ticketsEmail.email && errors.push(this.$t("emailNotValid"));

      return errors;
    },

    invalidPhones() {
      return this.phoneModule
        ? this.$store.getters[`${this.phoneModule}/hasErrors`]
        : false;
    },
    invalidfaxes() {
      return this.faxeModule
        ? this.$store.getters[`${this.faxeModule}/hasErrors`]
        : false;
    },
    invalidmobiles() {
      return this.mobileModule
        ? this.$store.getters[`${this.mobileModule}/hasErrors`]
        : false;
    },
    invalidemails() {
      return this.emailModule
        ? this.$store.getters[`${this.emailModule}/hasErrors`]
        : false;
    },
    invalidaddresses() {
      return this.addressModule
        ? this.$store.getters[`${this.addressModule}/hasErrors`]
        : false;
    },
    invalidnotes() {
      return this.noteModule
        ? this.$store.getters[`${this.noteModule}/hasErrors`]
        : false;
    },
    invalidWebsites() {
      return this.websiteModule
        ? this.$store.getters[`${this.websiteModule}/hasErrors`]
        : false;
    }
  },

  created() {
    this.$store.registerModule("user", module);
    this.id = parseInt(this.$route.params.id);
    this.loadTheInitial();
    if (this.id > 0) this.load();
    else this.resetForm();
  },
  beforeDestroy() {
    this.$store.unregisterModule("user");
  }
};
</script>

<style></style>
