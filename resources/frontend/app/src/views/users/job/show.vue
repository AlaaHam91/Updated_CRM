<template>
  <card
    :title="form.id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    @first="first"
    @previous="previous"
    @next="next"
    @last="last"
    :new-btn="$can('Cp_user_job', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="form.id ? $can('Cp_user_job', 'Delete') : false"
    :breadcrumbs="breadcrumbs"
    :no="form.id"
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
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('code')"
        ></div>
        <v-text-field
          outlined
          v-model="form.code"
          autocomplete="off"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('name')"
        ></div>
        <v-text-field
          outlined
          v-model="form.name"
          autocomplete="off"
          :error-messages="nameErrors"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('nameL')"
        ></div>
        <v-text-field
          outlined
          v-model="form.nameL"
          autocomplete="off"
          :error-messages="nameLErrors"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobType')"
        ></div>
        <v-select
          :items="jobs"
          outlined
          dense
          :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
          item-value="id"
          v-model="form.type"
          :error-messages="typeErrors"
          clearable
        ></v-select>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('salary')"
        ></div>
        <v-text-field
          outlined
          v-model="form.salary"
          type="number"
          min="0"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobDescr')"
        ></div>
        <v-text-field
          outlined
          v-model="form.description"
          autocomplete="off"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobDescrL')"
        ></div>
        <v-text-field
          outlined
          v-model="form.descriptionL"
          autocomplete="off"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobLevel')"
        ></div>
        <v-text-field
          outlined
          v-model="form.level"
          autocomplete="off"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobLevelL')"
        ></div>
        <v-text-field
          outlined
          v-model="form.levelL"
          autocomplete="off"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('employmentType')"
        ></div>
        <v-text-field
          outlined
          v-model="form.employmentType"
          autocomplete="off"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('employmentTypeL')"
        ></div>
        <v-text-field
          outlined
          v-model="form.employmentTypeL"
          autocomplete="off"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('education')"
        ></div>
        <v-select
          :items="educations"
          outlined
          dense
          :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
          item-value="id"
          v-model="form.requiredEducation"
          :error-messages="requiredEducationErrors"
          clearable
        ></v-select>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('nationality')"
        ></div>
        <v-select
          :items="countries"
          outlined
          dense
          :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
          item-value="id"
          v-model="form.nationality"
          clearable
        ></v-select>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobskills')"
        ></div>
        <v-text-field
          outlined
          v-model="form.skills"
          autocomplete="off"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('jobskillsL')"
        ></div>
        <v-text-field
          outlined
          v-model="form.skillsL"
          autocomplete="off"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('phoneBookAvailable')"
        ></div>
        <v-checkbox v-model="form.phoneBookAvailable"> </v-checkbox>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('requiredJobTitle')"
        ></div>
        <v-checkbox v-model="form.requiredJobTitle"> </v-checkbox>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import api from "./../../../services/api/users/job.api.js";
import educationApi from "./../../../services/api/control-panel/required-education.api";
import countryApi from "./../../../services/api/company-data/country.api";
import jobTypeApi from "./../../../services/api/users/job-type.api.js";

import promise from "promise";

export default {
  name: "activity-show",
  components: { card },
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("users"),
          disabled: false
        },
        {
          text: this.$t("job"),
          disabled: false
        }
      ],
      form: {
        id: null,
        code: null,
        name: null,
        nameL: null,
        type: [],
        salary: null,
        description: null,
        descriptionL: null,
        level: null,
        levelL: null,
        employmentType: null,
        employmentTypeL: null,
        requiredEducation: null,
        nationality: null,
        skills: null,
        skillsL: null,
        phoneBookAvailable: false,
        requiredJobTitle: false
      },
      jobs: [],
      educations: [],
      countries: [],
      messages: []
    };
  },
  validations: {
    form: {
      name: {
        required
      },
      nameL: {
        required
      },
      type: {
        required
      },
      requiredEducation: {
        required
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_user_job", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_user_job", "Update"))
          reject();
        this.$v.$touch();
        if (this.$v.$invalid) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
          return;
        }

        let data = {
          name: this.form.name,
          latin_name: this.form.nameL,
          code: this.form.code,
          job_type_id: this.form.type,
          salary: this.form.salary,
          job_description: this.form.description,
          latin_job_description: this.form.descriptionL,
          job_level: this.form.level,
          latin_job_level: this.form.levelL,
          employment_type: this.form.employmentType,
          latin_employment_type: this.form.employmentTypeL,
          preferred_nationality_id: this.form.nationality,
          job_skills: this.form.skills,
          latin_job_skills: this.form.skillsL,
          is_phone_book_available: this.form.phoneBookAvailable == true ? 1 : 0,
          is_required_job_title: this.form.requiredJobTitle == true ? 1 : 0,
          required_education_id: this.form.requiredEducation
        };

        if (this.form.id == 0)
          api
            .create(data)
            .then(() => resolve())
            .catch(res => {
              this.messages = Array.isArray(res.data.message)
                ? res.data.message
                : [res.data.message];
              reject();
            });
        else if (this.form.id > 0)
          api
            .update(this.form.id, data)
            .then(() => resolve())
            .catch(res => {
              this.messages = Array.isArray(res.data.message)
                ? res.data.message
                : [res.data.message];
              reject();
            });
      });
    },
    save() {
      this.doSave()
        .then(() => {
          this.$router.push({ name: "job-index" });
        })
        .catch(() => {});
    },
    saveAndNew() {
      this.doSave()
        .then(() => this.resetForm())
        .catch(() => {});
    },
    resetForm() {
      this.form.id = 0;
      this.form.name = null;
      this.form.nameL = null;
      this.form.description = null;
      this.form.descriptionL = null;
      this.form.code = null;
      this.form.type = null;
      this.form.salary = null;
      this.form.level = null;
      this.form.levelL = null;
      this.form.employmentType = null;
      this.form.employmentTypeL = null;
      this.form.requiredEducation = null;
      this.form.nationality = null;
      this.form.skills = null;
      this.form.skillsL = null;
      this.form.phoneBookAvailable = false;
      this.form.requiredJobTitle = false;
    },
    load() {
      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    loadInitial() {
      //load jobs
      jobTypeApi
        .getAll()
        .then(res => (this.jobs = res))
        .catch(() => {});
      //load educations
      educationApi
        .getAll()
        .then(res => (this.educations = res))
        .catch(() => {});
      //load countries
      countryApi
        .getAll()
        .then(res => (this.countries = res))
        .catch(() => {});
    },
    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "job-index" });
        })
        .catch(() => {});
    },
    first() {
      api
        .first(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    previous() {
      api
        .previous(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    next() {
      api
        .next(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    last() {
      api
        .last(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    }
  },

  computed: {
    nameErrors() {
      const errors = [];
      if (!this.$v.form.name.$dirty) return errors;
      !this.$v.form.name.required && errors.push(this.$t("required"));

      return errors;
    },
    nameLErrors() {
      const errors = [];
      if (!this.$v.form.nameL.$dirty) return errors;
      !this.$v.form.nameL.required && errors.push(this.$t("required"));

      return errors;
    },
    typeErrors() {
      const errors = [];
      if (!this.$v.form.type.$dirty) return errors;
      !this.$v.form.type.required && errors.push(this.$t("required"));

      return errors;
    },
    requiredEducationErrors() {
      const errors = [];
      if (!this.$v.form.requiredEducation.$dirty) return errors;
      !this.$v.form.requiredEducation.required &&
        errors.push(this.$t("required"));

      return errors;
    }
  },

  created() {
    this.form.id = parseInt(this.$route.params.id);
    this.loadInitial();
    if (this.form.id > 0) this.load();
    else this.resetForm();
  }
};
</script>
