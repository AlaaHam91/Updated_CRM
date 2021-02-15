<template>
  <card
    :title="id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    :new-btn="$can('Pg_comm_methods', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="id ? $can('Pg_comm_methods', 'Delete') : false"
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
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('contactBy')"
        ></div>
        <v-select
          :items="methods"
          outlined
          dense
          item-text="label"
          item-value="value"
          v-model="section"
          :error-messages="sectionError"
        ></v-select>
      </v-col>
      <v-col cols="12" md="3">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('name')"
        ></div>
        <v-text-field
          outlined
          v-model="name"
          autocomplete="off"
          :error-messages="nameErrors"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="3">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('nameL')"
        ></div>
        <v-text-field
          outlined
          v-model="nameL"
          autocomplete="off"
          :error-messages="nameLErrors"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="6" md="6">
        <v-switch v-model="isRequired" :label="$t('isRequired')"></v-switch>
      </v-col>
      <v-col cols="6" md="6">
        <v-switch v-model="isDefault" :label="$t('isDefault')"></v-switch>
      </v-col>
    </v-row>
    <mobile :items="mobileItems" v-show="section === 'Mobile'" />
    <phone :items="phoneItems" v-show="section === 'Phone'" />
    <fax :items="faxItems" v-show="section === 'Fax'" />
    <addressInfo
      :validateTable="validateTable"
      v-show="section === 'Address'"
      @isValid="isValid = $event"
    />
  </card>
</template>

<script>
import card from "./../../../components/card";
import mobile from "./mobile";
import fax from "./fax";
import phone from "./phone";
import addressInfo from "./address";
import { required } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import module from "./communication-method.store";
import promise from "promise";
export default {
  name: "communication-method-show",
  components: {
    card,
    mobile,
    phone,
    fax,
    addressInfo
  },
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("controlPanel"),
          disabled: false
        },
        {
          text: this.$t("communicationMethod"),
          disabled: false
        }
      ],
      validateTable: false,
      isValid: false,
      methods: [
        { label: this.$t("mobile"), value: "Mobile" },
        { label: this.$t("phone"), value: "Phone" },
        { label: this.$t("fax"), value: "Fax" },
        { label: this.$t("address"), value: "Address" },
        { label: this.$t("email"), value: "Email" },
        { label: this.$t("notes"), value: "Note" },
        { label: this.$t("location"), value: "Position" },
        { label: this.$t("website"), value: "Website" }
      ]
    };
  },
  validations: {
    section: {
      required
    },
    name: {
      required
    },
    nameL: {
      required
    }
  },
  methods: {
    async doSave() {
      return new promise(async (resolve, reject) => {
        if (this.id == 0 && !this.$canMsg("Pg_comm_methods", "Create"))
          reject();
        if (this.id > 0 && !this.$canMsg("Pg_comm_methods", "Update")) reject();
        this.$v.$touch();
        this.validateTable = true;
        await this.$store.dispatch(`communicationMethods/touchDatatables`);

        if (
          this.$v.$invalid ||
          (this.section == "Address" && !this.isValid) ||
          (await this.invalidPhones) ||
          (await this.invalidFaxes) ||
          (await this.invalidMobiles) ||
          (await this.invalidAddresses)
        ) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
        } else
          this.$store
            .dispatch("communicationMethods/save")
            .then(() => resolve())
            .catch(() => reject());
      });
    },
    save() {
      this.doSave()
        .then(() => this.$router.push({ name: "communication-method-index" }))
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
        .dispatch(`communicationMethods/delete`)
        .then(() => {
          this.$router.push({ name: "communication-method-index" });
        })
        .catch(() => {
          //
        });
    },
    load() {
      this.$store.dispatch(`communicationMethods/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`communicationMethods/reset`);
    }
  },
  computed: {
    ...mapFields("communicationMethods", ["id"]),
    ...mapFields("communicationMethods", ["section"]),
    ...mapFields("communicationMethods", ["name"]),
    ...mapFields("communicationMethods", ["nameL"]),
    ...mapFields("communicationMethods", ["isRequired"]),
    ...mapFields("communicationMethods", ["isDefault"]),
    ...mapFields("communicationMethods", ["mobileItems"]),
    ...mapFields("communicationMethods", ["phoneItems"]),
    ...mapFields("communicationMethods", ["faxItems"]),
    ...mapFields("communicationMethods", ["mobileModule"]),
    ...mapFields("communicationMethods", ["phoneModule"]),
    ...mapFields("communicationMethods", ["faxModule"]),
    ...mapFields("communicationMethods", ["messages"]),

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
    invalidAddresses() {
      return this.addressModule
        ? this.$store.getters[`${this.addressModule}/hasErrors`]
        : false;
    },
    sectionError() {
      const errors = [];
      if (!this.$v.section.$dirty) return errors;
      !this.$v.section.required && errors.push(this.$t("required"));

      return errors;
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
    }
  },
  created() {
    this.$store.registerModule("communicationMethods", module);
    this.id = parseInt(this.$route.params.id);
    if (this.id > 0) this.load();
    else this.resetForm();
  },
  beforeDestroy() {
    this.$store.unregisterModule("communicationMethods");
  }
};
</script>
