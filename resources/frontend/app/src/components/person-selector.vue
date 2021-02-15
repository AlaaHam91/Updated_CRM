<template>
  <div>
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
      <v-col cols="12" md="6" :order="type == 'company' ? 1 : 2">
        <div class="text-body-1 black--text mb-2" v-text="$t('company')"></div>
        <search
          v-model="company"
          :item-text="$i18n.locale == 'en' ? 'nameL' : 'name'"
          item-value="id"
          api="people-index/company.api.js"
          :headers="searchHeaders"
          :error-messages="companyErrors"
          :readonly="isCompanyReadonly"
        ></search>
      </v-col>
      <v-col cols="12" md="3" :order="type == 'company' ? 2 : 1">
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
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import search from "@/components/search";

export default {
  name: "person-selector",
  props: {
    touch: { type: Boolean, required: true, default: false },
    value: undefined
  },

  components: {
    search
  },

  data() {
    return {
      typeItems: [
        { label: this.$t("company"), value: "company" },
        { label: this.$t("contact"), value: "contact" },
        { label: this.$t("phonebook"), value: "pBook" },
        { label: this.$t("potentialAgent"), value: "pAgent" },
        { label: this.$t("agent"), value: "agent" }
      ],
      personApi: [
        {
          api: "people-index/company.api.js",
          funcName: "getPerson",
          payLoad: null
        },
        { api: "people-index/contact.api.js" },
        { api: "people-index/phonebook.api.js" },
        { api: "people-index/potential-agent.api.js" },
        { api: "people-index/agent.api.js" }
      ],
      searchHeaders: [
        { name: this.$t("name"), value: "name" },
        { name: this.$t("nameL"), value: "nameL" }
      ],
      type: "company",
      company: null,
      person: null
    };
  },

  validations: {
    type: { required },
    company: { required },
    person: { required }
  },

  computed: {
    isCompanyReadonly() {
      return this.type === "company" ? false : true;
    },
    isPersonReadonly() {
      return this.type === "company" && !this.company ? true : false;
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
    type: {
      handler: function(newVal, oldVal) {
        if (newVal !== oldVal) {
          this.person = null;
          this.company = null;
        }
      },
      immediate: true
    },
    company: {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.personApi[0].payLoad = newVal;
        this.emit();
      }
    },
    person: {
      handler: function(newVal, oldVal) {
        if (!newVal && !oldVal) return;
        if (newVal === oldVal) return;
        if (newVal && this.type !== "company") {
          this.company = newVal.company;
        }
        this.emit();
      }
    },
    touch: {
      handler: function(val) {
        if (val) {
          this.$v.$touch();
        } else this.$v.$reset();
      },
      immediate: true
    },
    value: {
      handler: function(val) {
        if (val) {
          // this.type = val.type;
          this.company = val.company;
          this.person = val.person;
        }
      },
      deep: true
    },
    "$v.$invalid": {
      handler: function(val) {
        this.$emit("invalid", val);
      },
      deep: true,
      immediate: true
    }
  },

  methods: {
    emit() {
      this.$emit("input", {
        type: this.type,
        company: this.company,
        person: this.person
      });
    }
  }
};
</script>

<style></style>
