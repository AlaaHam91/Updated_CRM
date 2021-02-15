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
    :new-btn="$can('Pg_contact', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="form.id ? $can('Pg_contact', 'Delete') : false"
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
          v-text="$t('type')"
        ></div>
        <v-select
          :items="types"
          outlined
          dense
          item-text="label"
          item-value="value"
          v-model="form.type"
          :error-messages="typeErrors"
          select
        ></v-select>
      </v-col>
      <v-col cols="12" md="6" v-if="form.type == 'Branch'">
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
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required, requiredIf } from "vuelidate/lib/validators";
import api from "@/services/api/people-settings/contact-source.api.js";
import promise from "promise";
export default {
  name: "phone-source-contact-show",
  components: { card },
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("indexSettings"),
          disabled: false
        },
        {
          text: this.$t("phoneSourceContact"),
          disabled: false
        }
      ],
      form: {
        id: null,
        name: null,
        nameL: null,
        parent: null,
        type: null
      },
      types: [
        { label: this.$t("main"), value: "Main" },
        { label: this.$t("branch"), value: "Branch" }
      ],
      parents: [],
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
      parent: {
        required: requiredIf(function() {
          return this.form.type == "Branch";
        })
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Pg_contact", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Pg_contact", "Update")) reject();
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
          type: this.form.type
        };
        if (this.form.parent) data.parent_id = this.form.parent;

        if (this.form.type == "Branch") data.parent_id = this.form.parent;
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
          this.$router.push({ name: "contact-source-index" });
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
      this.form.type = null;
      this.form.parent = null;
    },
    load() {
      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },

    loadInitial() {
      api
        .getMain()
        .then(
          res => (this.parents = res.filter(item => item.id !== this.form.id))
        )
        .catch(() => {})
        .finally(() => (this.loading = false));
    },

    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "contact-source-index" });
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
    parentErrors() {
      const errors = [];
      if (!this.$v.form.parent.$dirty) return errors;
      !this.$v.form.parent.required && errors.push(this.$t("required"));

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
