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
    :new-btn="$can('Cp_other_reqAction', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="form.id ? $can('Cp_other_reqAction', 'Delete') : false"
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
          class="text-body-1 black--text mb-2"
          v-text="$t('department')"
        ></div>
        <v-autocomplete
          v-model="form.departments"
          :items="departments"
          :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
          item-value="id"
          clearable
          hide-selected
          multiple
          chips
          small-chips
          outlined
          dense
        ></v-autocomplete>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('defaultTime')"
        ></div>
        <v-menu
          ref="menu"
          v-model="menu"
          :close-on-content-click="false"
          :nudge-right="40"
          :return-value.sync="form.defaultTime"
          transition="scale-transition"
          offset-y
          max-width="290px"
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="form.defaultTime"
              readonly
              v-bind="attrs"
              v-on="on"
              outlined
            ></v-text-field>
          </template>
          <v-time-picker
            v-if="menu"
            v-model="form.defaultTime"
            full-width
            @click:minute="$refs.menu.save(form.defaultTime)"
          ></v-time-picker>
        </v-menu>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('closeType')"
        ></div>
        <v-select
          :items="closeTypes"
          outlined
          dense
          item-text="label"
          item-value="value"
          v-model="form.closeType"
          :error-messages="closeTypeErrors"
          clearable
        ></v-select>
      </v-col>
      <v-col cols="12" md="6">
        <v-switch
          inset
          v-model="form.requiredDeliveryDate"
          :label="$t('requiredDeliveryDate')"
        >
        </v-switch>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <v-switch inset v-model="form.progressRate" :label="$t('progressRate')">
        </v-switch>
      </v-col>
      <v-col cols="12" md="6">
        <v-switch
          inset
          v-model="form.implementationNo"
          :label="$t('implementationNo')"
        >
        </v-switch>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('finishType')"
        ></div>
        <v-select
          :items="finishTypes"
          outlined
          dense
          item-text="label"
          item-value="value"
          v-model="form.finishType"
          clearable
        ></v-select>
      </v-col>
      <v-col cols="12" md="6">
        <v-switch
          inset
          v-model="form.confirmProject"
          :label="$t('confirmProject')"
        >
        </v-switch>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6" v-if="this.form.finishType">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="this.variableFieldName"
        ></div>
        <v-text-field
          outlined
          v-model="form.variableField"
          type="number"
          min="0"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6" v-if="this.form.finishType == 'direct_support'">
        <v-switch inset v-model="form.correctValue" :label="$t('correctValue')">
        </v-switch>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import api from "@/services/api/control-panel/required-action.api.js";
import departmentApi from "@/services/api/company-data/department.api.js";

import promise from "promise";
export default {
  name: "required-action-show",
  components: {
    card
  },
  data() {
    return {
      form: {
        id: null,
        name: null,
        nameL: null,
        departments: [],
        defaultTime: null,
        closeType: null,
        requiredDeliveryDate: false,
        implementationNo: false,
        confirmProject: false,
        progressRate: false,
        finishType: null,
        variableField: null,
        correctValue: false
      },
      menu: false,
      breadcrumbs: [
        {
          text: this.$t("controlPanel"),
          disabled: false
        },
        {
          text: this.$t("requiredAction"),
          disabled: false
        }
      ],
      finishTypes: [
        { label: this.$t("internalVisit"), value: "internal_visit" },
        { label: this.$t("externalVisit"), value: "external_visit" },
        { label: this.$t("directSupport"), value: "direct_support" },
        { label: this.$t("trianingHours"), value: "training_hours" }
      ],

      closeTypes: [
        { label: this.$t("byEmployee"), value: "E" },
        { label: this.$t("byClient"), value: "C" },
        { label: this.$t("byEmployeeOrClient"), value: "A" },
        { label: this.$t("auto"), value: "O" }
      ],
      departments: [],
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
      closeType: {
        required
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_other_reqAction", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_other_reqAction", "Update"))
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
      this.doSave()
        .then(() => {
          this.$router.push({ name: "required-action-index" });
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
    initForm() {
      let data = {
        name: this.form.name,
        latin_name: this.form.nameL,
        close_type: this.form.closeType,
        correct_value: this.form.correctValue == true ? 1 : 0,
        confirm_project: this.form.confirmProject == true ? 1 : 0,
        implementation_no: this.form.implementationNo == true ? 1 : 0,
        progress_rate: this.form.progressRate == true ? 1 : 0,
        required_delivery_date: this.form.requiredDeliveryDate == true ? 1 : 0
      };
      if (this.form.defaultTime) data.default_time = this.form.defaultTime;
      if (this.form.finishType) data.finish_type = this.form.finishType;
      if (this.form.variableField)
        data.variable_field = this.form.variableField;
      for (let i = 0; i < this.form.departments.length; i++) {
        data[`departments[${i}]`] = this.form.departments[i];
      }

      return data;
    },
    resetForm() {
      this.form.id = 0;
      this.form.name = null;
      this.form.nameL = null;
      this.form.departments = [];
      this.form.defaultTime = null;
      this.form.closeType = null;
      this.form.requiredDeliveryDate = false;
      this.form.implementationNo = false;
      this.form.confirmProject = false;
      this.form.progressRate = false;
      this.form.finishType = null;
      this.form.variableField = null;
      this.form.correctValue = false;
    },
    load() {
      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },

    loadInitial() {
      departmentApi
        .getAll()
        .then(res => (this.departments = res))
        .catch(() => {});
    },

    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "required-action-index" });
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
    closeTypeErrors() {
      const errors = [];
      if (!this.$v.form.closeType.$dirty) return errors;
      !this.$v.form.closeType.required && errors.push(this.$t("required"));

      return errors;
    },
    variableFieldName() {
      switch (this.form.finishType) {
        case "internal_visit":
          return this.$t("internalVisit");
        case "external_visit":
          return this.$t("externalVisit");

        case "direct_support":
          return this.$t("directSupport");
        default:
          return this.$t("trianingHours");
      }
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
