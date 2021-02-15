<template>
  <card
    :title="form.id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    list-btn
    :refresh-btn="form.id ? true : false"
    :new-btn="$can('Cp_coding_country', 'Create')"
    :delete-btn="form.id ? $can('Cp_coding_country', 'Delete') : false"
    save-btn
    save-new-btn
    @first="first"
    @previous="previous"
    @next="next"
    @last="last"
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
      <v-col cols="6" md="6">
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
      <v-col cols="6" md="3">
        <v-checkbox
          class="mt-8"
          v-model="form.isActive"
          :label="$t('isActive')"
        >
        </v-checkbox>
      </v-col>
      <v-col cols="6" md="3">
        <v-checkbox
          class="mt-8"
          v-model="form.isDefaultCountry"
          :label="$t('defaultCountry')"
        >
        </v-checkbox>
      </v-col>
    </v-row>
    <divisions
      v-if="!isLoading"
      @module="module = $event"
      :items="form.divisionItems"
      :error-messages="levelErrors"
    />
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import api from "@/services/api/company-data/country.api.js";
import promise from "promise";
import divisions from "./divisions";
export default {
  name: "country-show",
  components: {
    card,
    divisions
  },
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("companyData"),
          disabled: false
        },
        {
          text: this.$t("country"),
          disabled: false
        }
      ],
      form: {
        id: null,
        name: null,
        nameL: null,
        code: null,
        isDefaultCountry: false,
        isActive: false,
        divisionItems: []
      },
      module: null,
      isLoading: false,
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
      code: {
        required
      },
      divisionItems: { required }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_coding_country", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_coding_country", "Update"))
          reject();
        if (this.module) {
          let divisionItems = this.$store.getters[`${this.module}/items`];
          this.form.divisionItems = divisionItems;
          this.$store.commit(`${this.module}/touchValidation`);
        }
        this.$v.$touch();

        if (this.$v.$invalid || this.tableError) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
          return;
        }

        let data = this.initForm();
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
      this.doSave().then(() => {
        this.$router.push({ name: "country-index" });
      });
    },
    saveAndNew() {
      this.doSave().then(() => {
        this.resetForm();
      });
    },
    initForm() {
      let data = {
        name: this.form.name,
        latin_name: this.form.nameL,
        code: this.form.code,
        default_country: this.form.isDefaultCountry == true ? 1 : 0,
        is_active: this.form.isActive == true ? 1 : 0
      };
      for (let i = 0; i < this.form.divisionItems.length; i++) {
        if (this.form.divisionItems[i].status == "none") continue;

        data[`ad_levels[${i}][level]`] = this.form.divisionItems[i].level;
        data[`ad_levels[${i}][is_coding]`] =
          this.form.divisionItems[i].isCoding == true ? 1 : 0;
        data[`ad_levels[${i}][coding_type]`] = this.form.divisionItems[
          i
        ].codingType;
        data[`ad_levels[${i}][type]`] = this.form.divisionItems[i].type;
        data[`ad_levels[${i}][status]`] = this.form.divisionItems[i].status;
        if (this.form.divisionItems[i].adId)
          data[`ad_levels[${i}][id]`] = this.form.divisionItems[i].adId;
      }

      return data;
    },
    resetForm() {
      this.form.id = 0;
      this.form.name = null;
      this.form.nameL = null;
      this.form.code = null;
      this.form.isDefaultCountry = false;
      this.form.isActive = false;
      if (this.module) {
        this.$store.dispatch(`${this.module}/resetValidation`);
        this.$store.dispatch(`${this.module}/clearData`);
      }
      this.form.divisionItems = [];
      this.$v.$reset();
    },
    load() {
      this.isLoading = true;

      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .finally(() => (this.isLoading = false));
    },
    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "country-index" });
        })
        .catch(() => {});
    },
    first() {
      api.first(this.form.id).then(res => (this.form = res));
    },
    previous() {
      api.previous(this.form.id).then(res => (this.form = res));
    },
    next() {
      api.next(this.form.id).then(res => (this.form = res));
    },
    last() {
      api.last(this.form.id).then(res => (this.form = res));
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
    codeErrors() {
      const errors = [];
      if (!this.$v.form.code.$dirty) return errors;
      !this.$v.form.code.required && errors.push(this.$t("required"));

      return errors;
    },
    levelErrors() {
      const errors = [];
      if (!this.$v.form.divisionItems.$dirty) return errors;
      !this.$v.form.divisionItems.required && errors.push(this.$t("required"));
      this.tableError && errors.push(this.$t("fillTable"));

      return errors;
    },
    tableError() {
      return this.module
        ? this.$store.getters[`${this.module}/hasErrors`]
        : false;
    }
  },

  created() {
    this.form.id = parseInt(this.$route.params.id);
    if (this.form.id > 0) this.load();
    else this.resetForm();
  }
};
</script>
