<template>
  <card
    :title="form.id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    list-btn
    :refresh-btn="form.id ? true : false"
    :new-btn="$can('Cp_cpData_hierarchy', 'Create')"
    :delete-btn="form.id ? $can('Cp_cpData_hierarchy', 'Delete') : false"
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
      <v-col cols="12" md="4">
        <div
          v-if="loading"
          class="d-flex justify-center align-center"
          style="height: 5rem;"
        >
          <v-progress-circular indeterminate></v-progress-circular>
        </div>
        <v-card v-else max-width="500" class="mt-2">
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
              v-model="form.hierarchy"
              :items="hierarchies"
              color="primary"
              background-color="inputBack"
              dense
              activatable
              :search="search"
              :search-input.sync="search"
              active-class="grey lighten-4 indigo--text"
              open-all
              selectable
              selection-type="independent"
            >
            </v-treeview>
          </v-card-text>
          <div style="min-height: 2rem; width: 100%;">
            <div
              class="red--text text-body-2"
              v-for="e in ParentMessages"
              :key="e"
            >
              {{ e }}
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" md="8">
        <v-row>
          <v-col cols="12" md="12">
            <div class="text-body-1 black--text mb-2" v-text="$t('name')"></div>
            <v-text-field
              :placeholder="$t('name')"
              v-model="form.name"
              outlined
              :error-messages="nameErrors"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="12">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('nameL')"
            ></div>
            <v-text-field
              :placeholder="$t('nameL')"
              v-model="form.nameL"
              outlined
              :error-messages="nameLErrors"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="12">
            <div
              class="text-body-1 text-no-wrap black--text mb-2"
              v-text="$t('type')"
            ></div>
            <v-select
              :items="types"
              v-model="form.type"
              :placeholder="$t('type')"
              item-text="label"
              item-value="value"
              outlined
              dense
              clearable
              :error-messages="typeErrors"
            ></v-select>
          </v-col>
          <!-- <v-col cols="12" md="12">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('parentPoint')"
            ></div>
            <v-text-field
              :placeholder="$t('parentPoint')"
              :value="hierarchyLeaf"
              outlined
              readonly
            ></v-text-field>
          </v-col> -->
        </v-row>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required, maxLength, requiredIf } from "vuelidate/lib/validators";
import promise from "promise";
import api from "@/services/api/company-data/hierarchy.api.js";
import { flatten } from "lodash";

export default {
  components: {
    card
  },
  name: "hierarchy-show",
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("companyData"),
          disabled: false
        },
        {
          text: this.$t("hierarchy"),
          disabled: false
        }
      ],
      search: null,
      form: {
        id: null,
        name: null,
        nameL: null,
        type: null,
        hierarchy: []
      },
      types: [
        { label: this.$t("main"), value: "main" },
        { label: this.$t("sub"), value: "sub" }
      ],
      hierarchies: [],
      loading: false,
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
      hierarchy: {
        maxLength: maxLength(1),
        required: requiredIf(function() {
          return this.form.type === "sub";
        })
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_cpData_hierarchy", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_cpData_hierarchy", "Update"))
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
          latin_name: this.form.nameL,
          name: this.form.name,
          type: this.form.type
        };
        if (this.form.hierarchy.length > 0)
          data.parent_id = this.form.hierarchy[0];
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
        this.$router.push({ name: "hierarchy-index" });
      });
    },
    saveAndNew() {
      this.doSave().then(() => {
        this.resetForm();
        this.loadInitial();
      });
    },
    resetForm() {
      this.form.id = 0;
      this.form.name = null;
      this.form.nameL = null;
      this.form.type = null;
      this.form.hierarchy = [];
    },

    load() {
      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    loadInitial() {
      this.loading = true;
      api
        .getTree()
        .then(res => {
          this.hierarchies = flatten(res).filter(
            item => item.id !== this.form.id
          );
        })
        .finally((this.loading = false));
    },
    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "hierarchy-index" });
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
    id() {
      return parseInt(this.$route.params.id);
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
    typeErrors() {
      const errors = [];
      if (!this.$v.form.type.$dirty) return errors;
      !this.$v.form.type.required && errors.push(this.$t("required"));

      return errors;
    },
    ParentMessages() {
      const errors = [];
      if (!this.$v.form.hierarchy.$dirty) return errors;
      !this.$v.form.hierarchy.maxLength && errors.push(this.$t("unique"));
      !this.$v.form.hierarchy.required && errors.push(this.$t("required"));

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

<style></style>
