<template>
  <div>
    <div
      v-if="loading"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
    <datatable
      v-if="headers.length > 0"
      :headers="headers"
      :items="items"
      @storeModule="setStoreModule"
      height="10rem"
      mobileModule-prefix="phone"
    />
  </div>
</template>

<script>
import datatable from "./../../../components/datatable/datatable";
import { mapFields } from "vuex-map-fields";
import api from "./../../../services/api/control-panel/administrative-division.api";
import countryApi from "@/services/api/company-data/country.api.js";

import { cloneDeep } from "lodash";

export default {
  name: "communication-method-phones",
  components: { datatable },
  data() {
    return { loading: false, prefixes: [], headers: [], divisions: [] };
  },
  props: {
    items: { type: Array, required: true }
  },
  methods: {
    setStoreModule(name) {
      this.$emit("store-module", name);
      this.phoneModule = name;
    },

    async load() {
      this.loading = true;

      let headers = [
        {
          text: this.$t("country"),
          value: "country",
          type: "select",
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          readonly: false,
          visible: true,
          width: "150px",
          options: [],
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("administrativeDivision"),
          value: "administrativeDivision",
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          type: "select",
          readonly: false,
          visible: true,
          width: "150px",
          dedicatedOptions: "administrativeDivisionOptions",
          unique: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("prefix"),
          value: "prefix",
          type: "combobox",
          itemText: "prefix",
          itemValue: "id",
          readonly: false,
          visible: true,
          width: "150px",
          dedicatedOptions: "prefixOptions",
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("length"),
          value: "length",
          type: "number",
          min: "0",
          step: "1",
          readonly: false,
          visible: true,
          width: "150px",
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("displayPattern"),
          value: "pattern",
          type: "string",
          readonly: true,
          visible: true,
          width: "150px"
        },
        {
          text: "config",
          value: "config",
          width: "0px",
          type: "string",
          readonly: true,
          visible: false
        }
      ];
      await countryApi
        .getAll()
        .then(res => (headers[0].options = res))
        .finally(() => (this.loading = false));
      this.headers = cloneDeep(headers);

      setTimeout(() => {
        // wait for this.phoneModule
        this.items.forEach((item, i) => {
          this.$store.commit(`${this.phoneModule}/dedicatedOptions`, {
            index: i,
            optionsName: "administrativeDivisionOptions",
            options: item.adOptions
          });
        });
      }, 1000);
    },

    getNewItemIndex(arr1, arr2) {
      for (let i = 0; i < arr1.length; i++)
        if (arr1[i].val !== arr2[i].val) return i;
    },
    async setPattern(id, val) {
      await this.$store.dispatch(`${this.phoneModule}/editItem`, {
        id: id,
        pattern: val
      });
    },
    convertToX(val) {
      let pattern = null;
      if (val > 0) {
        pattern = "X";
        for (let i = 1; i < val; i++) pattern += "X";
      }
      return pattern;
    },

    async loadDivisions(id) {
      //get all divisions by countryId
      await api.getByCountry(id).then(res => (this.divisions = res));
    },

    async loadPrefixes(id) {
      //by ad id
      await api.getPrefixes(id).then(res => (this.prefixes = res));
    }
  },

  computed: {
    ...mapFields("communicationMethods", ["phoneModule"]),
    mobileLengthsCol() {
      if (!this.phoneModule) return [];
      let items = this.$store.getters[`${this.phoneModule}/items`];
      let lengthsItems = [];
      items.forEach(element => {
        lengthsItems.push({ id: element.id, val: element.length });
      });
      return lengthsItems;
    },
    countryCol() {
      if (!this.phoneModule) return [];
      let items = this.$store.getters[`${this.phoneModule}/items`];
      let countryItems = [];
      items.forEach(element => {
        countryItems.push({ id: element.id, val: element.country });
      });
      return countryItems;
    },
    administrativeDivisionCol() {
      if (!this.phoneModule) return [];
      let items = this.$store.getters[`${this.phoneModule}/items`];
      let administrativeDivisionItems = [];
      items.forEach(element => {
        administrativeDivisionItems.push({
          id: element.id,
          val: element.administrativeDivision
        });
      });
      return administrativeDivisionItems;
    }
  },
  watch: {
    mobileLengthsCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length) return;
      //get item index
      let index = this.getNewItemIndex(newVal, oldVal);

      if (this.mobileLengthsCol[index])
        this.setPattern(
          newVal[index].id,
          this.convertToX(this.mobileLengthsCol[index].val)
        );
    },
    async countryCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length) return;

      //get item index
      let index = this.getNewItemIndex(newVal, oldVal);

      if (this.countryCol[index]) {
        //get all prefix by country id
        await this.loadDivisions(this.countryCol[index].val);
        //set dedicated options to child
        await this.$store.commit(`${this.phoneModule}/dedicatedOptions`, {
          index: index,
          optionsName: "administrativeDivisionOptions",
          options: this.divisions
        });
      }
    },
    async administrativeDivisionCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length) return;

      //get item index
      let index = this.getNewItemIndex(newVal, oldVal);

      if (this.administrativeDivisionCol[index]) {
        //get all prefix by country id
        await this.loadPrefixes(this.administrativeDivisionCol[index].val);
        //set dedicated options to child
        await this.$store.commit(`${this.phoneModule}/dedicatedOptions`, {
          index: index,
          optionsName: "prefixOptions",
          options: this.prefixes
        });
      }
    }
  },
  created() {
    this.load();
  }
};
</script>
