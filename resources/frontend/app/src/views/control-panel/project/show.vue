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
    :new-btn="$can('Cp_other_project', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Cp_other_project', 'Delete') : false"
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
      <v-col cols="8" md="4">
        <v-tabs v-model="tab" background-color="grey lighten-3" vertical>
          <v-tab>
            {{ $t("projectDetails") }}
            <span style="width: 1rem;" class="mx-1">
              <v-icon color="warning" v-if="$v.$anyError">
                mdi-information
              </v-icon>
            </span>
          </v-tab>

          <v-tab>{{ $t("projectLocation") }}</v-tab>
        </v-tabs>
      </v-col>
      <v-col cols="8" md="8">
        <v-tabs-items v-model="tab">
          <!-- Details -->
          <v-tab-item>
            <v-card flat>
              <v-row>
                <v-col cols="12" md="3">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('indexType')"
                  ></div>
                  <v-select
                    :items="typeItems"
                    v-model="type"
                    item-value="value"
                    item-text="label"
                    dense
                    outlined
                  ></v-select>
                </v-col>
                <v-col cols="12" md="5" :order="type == 'company' ? 1 : 2">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('company')"
                  ></div>
                  <search
                    v-model="company"
                    :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                    item-value="id"
                    api="people-index/company.api.js"
                    :headers="companySearchHeaders"
                    :error-messages="companyErrors"
                    :readonly="isCompanyReadonly"
                  ></search>
                </v-col>
                <v-col cols="12" md="4" :order="type == 'company' ? 2 : 1">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('requestedBy')"
                  ></div>
                  <div v-show="type === 'company'" key="1">
                    <search
                      v-model="person"
                      :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                      item-value="id"
                      :api="personApi[0].api"
                      :funcName="personApi[0].funcName"
                      :funcPayLoad="personApi[0].payLoad"
                      :headers="searchHeaders"
                      :error-messages="personErrors"
                      returnObject
                      :readonly="isPersonReadonly"
                    ></search>
                  </div>
                  <div v-show="type === 'contact'" key="2">
                    <search
                      v-model="person"
                      :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                      item-value="id"
                      :api="personApi[1].api"
                      :headers="searchHeaders"
                      :error-messages="personErrors"
                      returnObject
                    ></search>
                  </div>
                  <div v-show="type === 'pBook'" key="3">
                    <search
                      v-model="person"
                      :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                      item-value="id"
                      :api="personApi[2].api"
                      :headers="searchHeaders"
                      :error-messages="personErrors"
                      returnObject
                    ></search>
                  </div>
                  <div v-show="type === 'pAgent'" key="4">
                    <search
                      v-model="person"
                      :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                      item-value="id"
                      :api="personApi[3].api"
                      :headers="searchHeaders"
                      :error-messages="personErrors"
                      returnObject
                    ></search>
                  </div>
                  <div v-show="type === 'agent'" key="5">
                    <search
                      v-model="person"
                      :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
                      item-value="id"
                      api="people-index/agent.api.js"
                      :headers="searchHeaders"
                      :error-messages="personErrors"
                      returnObject
                    ></search>
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('code')"
                  ></div>
                  <v-text-field
                    :placeholder="$t('code')"
                    v-model="code"
                    outlined
                    autocomplete="off"
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
                    :placeholder="$t('name')"
                    v-model="name"
                    outlined
                    autocomplete="off"
                    :error-messages="nameErrors"
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
                    autocomplete="off"
                    :error-messages="nameLErrors"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('employee')"
                  ></div>
                  <v-select
                    :items="employees"
                    v-model="employee"
                    :placeholder="$t('employee')"
                    :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
                    item-value="id"
                    outlined
                    dense
                    clearable
                    :error-messages="employeeErrors"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <div
                    class="text-body-1 black--text mb-2"
                    v-text="$t('country')"
                  ></div>
                  <v-select
                    :items="countries"
                    v-model="country"
                    :placeholder="$t('country')"
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
                  ></v-select>
                </v-col>
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
                  ></v-select>
                </v-col>
              </v-row>
            </v-card>
          </v-tab-item>
          <!-- location -->
          <v-tab-item><g-map v-model="location"/></v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import search from "./../../../components/search";
import map from "./../../../components/map";
import { mapFields } from "vuex-map-fields";
import module from "./project.store";
import promise from "promise";

export default {
  components: {
    card,
    "g-map": map,
    search
  },
  name: "project-show",

  data() {
    return {};
  },

  validations: {
    name: {
      required
    },
    nameL: {
      required
    },
    employee: { required },
    person: { required },
    company: {
      required
    }
  },

  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Cp_other_project", "Create"))
          reject();
        if (this.id > 0 && !this.$canMsg("Cp_other_project", "Update"))
          reject();
        this.$v.$touch();
        if (this.$v.$invalid) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("project/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "project-index" }))
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
        .dispatch(`project/delete`)
        .then(() => this.$router.push({ name: "project-index" }))
        .catch(() => {
          //
        });
    },
    load() {
      this.$store.dispatch(`project/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`project/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`project/loadTheInitial`);
    },
    first() {
      this.userId = this.$store.dispatch(`project/first`);
    },
    previous() {
      this.$store.dispatch(`project/previous`);
    },
    next() {
      this.$store.dispatch(`project/next`);
    },
    last() {
      this.$store.dispatch(`project/last`);
    }
  },

  computed: {
    ...mapFields("project", ["breadcrumbs"]),
    ...mapFields("project", ["tab"]),
    ...mapFields("project", ["type"]),
    ...mapFields("project", ["id"]),
    ...mapFields("project", ["company"]),
    ...mapFields("project", ["person"]),
    ...mapFields("project", ["code"]),
    ...mapFields("project", ["name"]),
    ...mapFields("project", ["nameL"]),
    ...mapFields("project", ["employee"]),
    ...mapFields("project", ["country"]),
    ...mapFields("project", ["branch"]),
    ...mapFields("project", ["department"]),
    ...mapFields("project", ["location"]),
    ...mapFields("project", ["requestBy"]),
    ...mapFields("project", ["messages"]),
    ...mapFields("project", ["branches"]),
    ...mapFields("project", ["departments"]),
    ...mapFields("project", ["employees"]),
    ...mapFields("project", ["countries"]),
    ...mapFields("project", ["companySearchHeaders"]),
    ...mapFields("project", ["searchHeaders"]),
    ...mapFields("project", ["personApi"]),
    ...mapFields("project", ["typeItems"]),

    isCompanyReadonly() {
      return this.type === "company" ? false : true;
    },
    isPersonReadonly() {
      return this.type === "company" && !this.company ? true : false;
    },
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
    employeeErrors() {
      const errors = [];
      if (!this.$v.employee.$dirty) return errors;
      !this.$v.employee.required && errors.push(this.$t("required"));
      return errors;
    },
    companyErrors() {
      const errors = [];
      if (!this.$v.company.$dirty) return errors;
      !this.$v.company.required && errors.push(this.$t("required"));
      return errors;
    },
    personErrors() {
      const errors = [];
      if (!this.$v.person.$dirty) return errors;
      !this.$v.person.required && errors.push(this.$t("required"));
      return errors;
    }
  },
  watch: {
    "$store.state.project.type": {
      handler: function(newVal, oldVal) {
        if (newVal !== oldVal) {
          this.person = null;
          this.company = null;
        }
      },
      immediate: true
    },
    "$store.state.project.company": {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.personApi[0].payLoad = newVal;
      }
    },
    "$store.state.project.person": {
      handler: function(newVal, oldVal) {
        if (!newVal && !oldVal) return;
        if (newVal === oldVal) return;
        if (newVal && this.type !== "company") {
          this.company = newVal.company;
        }
      }
    }
  },
  created() {
    this.$store.registerModule("project", module);
    this.id = parseInt(this.$route.params.id);
    this.loadTheInitial();
    if (this.id > 0) this.load();
    else this.resetForm();
  },
  beforeDestroy() {
    this.$store.unregisterModule("project");
  }
};
</script>

<style></style>
