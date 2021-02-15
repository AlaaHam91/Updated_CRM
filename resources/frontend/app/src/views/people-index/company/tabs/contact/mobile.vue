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
      module-prefix="mobile"
      :add="editable"
      :delete="editable"
    />
  </div>
</template>

<script>
import datatable from "@/components/datatable/datatable";
import _ from "lodash";

export default {
  name: "mobiles",

  props: {
    items: { type: Array, required: true },
    editable: { type: Boolean, default: true }
  },

  components: {
    datatable
  },
  data() {
    return {
      headers: [],
      module: null,
      loading: false
    };
  },

  methods: {
    setStoreModule(name) {
      this.module = name;
      this.$emit("module", name);
    },

    async load() {
      this.loading = true;

      let headers = [
        {
          text: this.$t("type"),
          value: "cm_mobile",
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
          text: this.$t("country"),
          value: "country",
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
          value: "mobile",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("socialMedia"),
          value: "socialMedia",
          type: "multiselect",
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          readonly: false,
          visible: true,
          width: "300px",
          options: []
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
        .dispatch("cache/cm", "Mobile")
        .then(res => (headers[0].options = res));

      await this.$store
        .dispatch("cache/country")
        .then(res => (headers[1].options = res));
      await this.$store
        .dispatch("cache/social")
        .then(res => {
          headers[3].options = res;
          this.headers = _.cloneDeep(headers);
        })
        .finally(() => (this.loading = false));
      this.headers = await _.cloneDeep(headers);
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
