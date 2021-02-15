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
      mobileModule-prefix="mobile"
    />
  </div>
</template>

<script>
import datatable from "./../../../components/datatable/datatable";
import { mapFields } from "vuex-map-fields";
import api from "@/services/api/company-data/country.api.js";
import { cloneDeep } from "lodash";

export default {
  name: "communication-method-mobiles",
  components: { datatable },
  props: {
    items: { type: Array, required: true }
  },
  data() {
    return { loading: false, prefixes: [], headers: [] };
  },

  methods: {
    setStoreModule(name) {
      this.mobileModule = name;
      this.$emit("mobileModule", name);
    },
    getNewItemIndex(arr1, arr2) {
      for (let i = 0; i < arr1.length; i++)
        if (arr1[i].val !== arr2[i].val) return i;
    },
    async setPattern(id, val) {
      await this.$store.dispatch(`${this.mobileModule}/editItem`, {
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
    async loadPrefixes(id) {
      await api.getPrefixes(id).then(res => (this.prefixes = res));
    },
    async load() {
      this.loading = true;
      let headers = [
        {
          text: this.$t("country"),
          type: "select",
          value: "country",
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          readonly: false,
          visible: true,
          width: "150px",
          options: [],
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
          width: "200px"
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
      await api
        .getAll()
        .then(res => (headers[0].options = res))
        .finally(() => (this.loading = false));
      this.headers = cloneDeep(headers);
    }
  },

  computed: {
    ...mapFields("communicationMethods", ["mobileModule"]),
    MobileLengthsCol() {
      if (!this.mobileModule) return [];
      let items = this.$store.getters[`${this.mobileModule}/items`];
      let lengthsItems = [];
      items.forEach(element => {
        lengthsItems.push({ id: element.id, val: element.length });
      });
      return lengthsItems;
    },
    countryCol() {
      if (!this.mobileModule) return [];
      let items = this.$store.getters[`${this.mobileModule}/items`];
      let countryItems = [];
      items.forEach(element => {
        countryItems.push({ id: element.id, val: element.country });
      });
      return countryItems;
    }
  },
  watch: {
    MobileLengthsCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length) return;
      //get item index
      let index = this.getNewItemIndex(newVal, oldVal);

      if (this.MobileLengthsCol[index])
        this.setPattern(
          newVal[index].id,
          this.convertToX(this.MobileLengthsCol[index].val)
        );
    },
    async countryCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length) return;

      //get item index
      let index = this.getNewItemIndex(newVal, oldVal);

      if (this.countryCol[index]) {
        //get all prefix by country id
        await this.loadPrefixes(this.countryCol[index].val);

        //set dedicated options to child
        this.$store.commit(`${this.mobileModule}/dedicatedOptions`, {
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
