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
      module-prefix="fax"
      :add="editable"
      :delete="editable"
    />
  </div>
</template>

<script>
import datatable from "@/components/datatable/datatable";
import _ from "lodash";

export default {
  name: "faxes",

  props: {
    items: { type: Array, required: true },
    editable: { type: Boolean, default: true }
  },

  components: {
    datatable
  },

  data() {
    return {
      loading: false,
      headers: []
    };
  },

  methods: {
    setStoreModule(name) {
      this.$emit("module", name);
    },
    async load() {
      let headers = [
        {
          text: this.$t("type"),
          value: "cm_fax",
          width: "200px",
          type: "search",
          readonly: false,
          visible: true,
          options: [],
          headers: [
            { text: this.$t("name"), value: "name" },
            { text: this.$t("nameL"), value: "nameL" }
          ],

          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "section_id",
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("administrativeDivision"),
          value: "a_d",
          width: "200px",
          type: "search",
          readonly: false,
          visible: true,
          options: [],
          headers: [
            { text: this.$t("name"), value: "name" },
            { text: this.$t("nameL"), value: "nameL" }
          ],
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("value"),
          value: "fax",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("extension"),
          value: "extension",
          type: "string",
          width: "200px",
          visible: true,
          readonly: false
        },
        {
          text: "contact_info",
          value: "contact_info",
          width: "0px",
          type: "string",
          readonly: true,
          visible: false
        }
      ];
      this.loading = true;
      await this.$store
        .dispatch("cache/cm", "Fax")
        .then(res => (headers[0].options = res));
      await this.$store
        .dispatch("cache/ad")
        .then(res => (headers[1].options = res))
        .finally(() => (this.loading = false));
      this.headers = _.cloneDeep(headers);
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
