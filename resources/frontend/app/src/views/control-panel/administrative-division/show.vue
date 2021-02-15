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
    :new-btn="$can('Cp_coding_ad', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="form.id ? $can('Cp_coding_ad', 'Delete') : false"
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
    <v-row>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('country')"
        ></div>
        <v-select
          :items="countries"
          outlined
          dense
          :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
          item-value="id"
          v-model="form.country"
          clearable
          :error-messages="countryErrors"
          @change="loadLevels"
        ></v-select>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('level')"
        ></div>
        <v-select
          :items="levels"
          outlined
          dense
          item-text="type_name"
          item-value="id"
          v-model="form.level"
          :error-messages="levelErrors"
          clearable
          @change="loadParents"
        ></v-select>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6" v-show="form.level && form.level !== 1">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('parent')"
        ></div>
        <v-select
          :items="parents"
          outlined
          dense
          :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
          item-value="id"
          v-model="form.parent"
          :error-messages="parentErrors"
          clearable
        ></v-select>
      </v-col>
      <v-col cols="12" md="6" v-show="isCode">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('code')"
        ></div>
        <v-text-field
          outlined
          v-model="form.code"
          autocomplete="off"
          :error-messages="codeErrors"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('isActive')"
        ></div>
        <v-checkbox v-model="form.isActive"> </v-checkbox>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required, requiredIf } from "vuelidate/lib/validators";
import api from "./../../../services/api/control-panel/administrative-division.api";
import countryApi from "./../../../services/api/company-data/country.api";
import promise from "promise";

export default {
  name: "administrative-division-show",
  components: { card },
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("controlPanel"),
          disabled: false
        },
        {
          text: this.$t("administrativeDivision"),
          disabled: false
        }
      ],
      form: {
        id: null,
        name: null,
        nameL: null,
        code: null,
        country: null,
        level: null,
        parent: null,
        isActive: false
      },
      countries: [],
      parents: [],
      levels: [],
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
      country: { required },
      level: {
        required
      },
      parent: {
        required: requiredIf(function() {
          return this.form.level && this.form.level !== 1;
        })
      },
      code: {
        required: requiredIf(function() {
          return this.isCode;
        })
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_coding_ad", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_coding_ad", "Update"))
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
          ad_level_id: this.form.level,
          parent_id: this.form.parent,
          is_active: this.form.isActive == false ? 0 : 1
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
          this.$router.push({ name: "administrative-division-index" });
        })
        .catch(() => {});
    },
    saveAndNew() {
      this.doSave()
        .then(() => {
          this.resetForm();
        })
        .catch(() => {});
    },
    resetForm() {
      this.form.id = 0;
      this.form.name = null;
      this.form.nameL = null;
      this.form.code = null;
      this.form.country = null;
      this.form.level = null;
      this.form.parent = null;
      this.form.isActive = false;
    },
    async load() {
      await api
        .get(this.form.id)
        .then(res => this.loadForm(res))
        .catch(() => {});
    },
    loadForm(res) {
      if (res === undefined) return;
      this.form.id = res.id;
      this.form.name = res.name ? res.name.ar : null;
      this.form.nameL = res.name ? res.name.en : null;
      this.form.country = res.country_id;
      this.form.parent = res.parent_id;
      this.form.level = res.ad_level_id;
      this.form.code = res.code;
      this.form.isActive = res.is_active == 1 ? true : false;
      this.loadParents();
      this.loadLevels();
    },

    loadInitial() {
      //load countries
      countryApi
        .getAll()
        .then(res => {
          this.countries = res;
        })
        .catch(() => {});
    },
    loadLevels() {
      //load levels by selected country
      if (this.form.country)
        api
          .getLevelsByCountry(this.form.country)
          .then(res => (this.levels = res))
          .catch(() => {});
      else this.form.level = null;
    },
    loadParents() {
      //load parents by selected level

      if (this.form.level)
        api
          .getParents(this.form.level)
          .then(res => (this.parents = res))
          .catch(() => {});
      else this.form.parent = null;
    },
    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "administrative-division-index" });
        })
        .catch(() => {});
    },
    first() {
      api
        .first(this.form.id)
        .then(res => this.loadForm(res))
        .catch(() => {});
    },
    previous() {
      api
        .previous(this.form.id)
        .then(res => this.loadForm(res))
        .catch(() => {});
    },
    next() {
      api
        .next(this.form.id)
        .then(res => this.loadForm(res))
        .catch(() => {});
    },
    last() {
      api
        .last(this.form.id)
        .then(res => this.loadForm(res))
        .catch(() => {});
    }
  },

  computed: {
    isCode() {
      if (this.levels.length > 0) {
        let level = this.levels.find(l => l.id == this.form.level);
        // console.log(level);
        if (level !== undefined) return level.isCoding;
      }
      return false;
    },

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
    countryErrors() {
      const errors = [];
      if (!this.$v.form.country.$dirty) return errors;
      !this.$v.form.country.required && errors.push(this.$t("required"));

      return errors;
    },
    levelErrors() {
      const errors = [];
      if (!this.$v.form.level.$dirty) return errors;
      !this.$v.form.level.required && errors.push(this.$t("required"));

      return errors;
    },
    parentErrors() {
      const errors = [];
      if (!this.$v.form.parent.$dirty) return errors;
      !this.$v.form.parent.required && errors.push(this.$t("required"));

      return errors;
    },
    codeErrors() {
      const errors = [];
      if (!this.$v.form.code.$dirty) return errors;
      !this.$v.form.code.required && errors.push(this.$t("required"));

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
