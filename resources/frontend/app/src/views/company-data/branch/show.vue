<template>
  <card
    :title="form.id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    list-btn
    :refresh-btn="form.id ? true : false"
    :new-btn="$can('Cp_cpData_branch', 'Create')"
    :delete-btn="form.id ? $can('Cp_cpData_branch', 'Delete') : false"
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
        <div class="text-body-1 black--text mb-2" v-text="$t('name')"></div>
        <v-text-field
          :placeholder="$t('name')"
          v-model="form.name"
          outlined
          :error-messages="nameErrors"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div class="text-body-1 black--text mb-2" v-text="$t('nameL')"></div>
        <v-text-field
          :placeholder="$t('nameL')"
          v-model="form.nameL"
          outlined
          :error-messages="nameLErrors"
        ></v-text-field>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import promise from "promise";
import api from "@/services/api/company-data/branch.api.js";

export default {
  components: {
    card
  },
  name: "branch-show",
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("companyData"),
          disabled: false
        },
        {
          text: this.$t("branch"),
          disabled: false
        }
      ],
      form: {
        name: null,
        nameL: null,
        id: null
      },
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
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_cpData_branch", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_cpData_branch", "Update"))
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
          latin_name: this.form.nameL
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
          this.$router.push({ name: "branch-index" });
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
      this.messages = [];
    },
    load() {
      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },

    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "branch-index" });
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
    }
  },

  created() {
    this.form.id = parseInt(this.$route.params.id);
    if (this.form.id > 0) this.load();
    else this.resetForm();
  }
};
</script>

<style></style>
