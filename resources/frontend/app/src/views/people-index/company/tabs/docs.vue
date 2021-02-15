<template>
  <div>
    <div
      v-if="loading"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
    <div v-if="internalItems.length > 0 && !resetDocs">
      <datatable
        :headers="headers"
        :items="internalItems"
        @storeModule="setStoreModule"
        height="10rem"
        :add="false"
        :delete="false"
        module-prefix="docs"
      />
    </div>
  </div>
</template>

<script>
import datatable from "@/components/datatable/datatable";
import documentApi from "@/services/api/people-settings/document.api";
import documentSrcApi from "@/services/api/people-settings/document-source.api";
import { mapFields } from "vuex-map-fields";
import _ from "lodash";

export default {
  name: "docs",

  components: {
    datatable
  },

  props: {
    items: undefined
  },

  data() {
    return {
      loading: false,
      module: null,
      headers: [
        {
          text: this.$t("document"),
          value: "document",
          width: "200px",
          type: "select",
          readonly: true,
          visible: true,
          options: [],
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id"
        },
        {
          text: this.$t("number"),
          value: "number",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true
        },
        {
          text: this.$t("source"),
          value: "document_source",
          width: "200px",
          type: "select",
          readonly: false,
          visible: true,
          options: [],
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id"
        },
        {
          text: this.$t("issueDate"),
          value: "start_date",
          width: "200px",
          type: "date",
          readonly: false,
          visible: true,
          rules: [
            {
              type: "requiredIf",
              other: "number",
              message: this.$t("required")
            }
          ]
        },
        {
          text: this.$t("expireDate"),
          value: "end_date",
          width: "200px",
          type: "date",
          readonly: false,
          visible: true,
          rules: [
            {
              type: "requiredIf",
              other: "number",
              message: this.$t("required")
            }
          ]
        },
        {
          text: this.$t("note"),
          value: "note",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true
        },
        {
          text: this.$t("files"),
          value: "files",
          type: "uploadFiles",
          readonly: false,
          visible: true,
          width: "200px"
        }
      ],
      internalItems: []
    };
  },

  methods: {
    setStoreModule(name) {
      this.module = name;
      this.$emit("module", name);
    },
    async load() {
      this.loading = true;

      await documentSrcApi
        .getAll()
        .then(res => (this.headers[2].options = res));
      await documentApi.getAll().then(res => {
        this.headers[0].options = res;
      });
      if (this.items.length > 0)
        this.internalItems = await _.cloneDeep(this.items);
      this.loading = await false;
    }
  },

  computed: {
    ...mapFields("company", ["resetDocs"])
  },

  created() {
    this.load();
  },

  watch: {
    resetDocs: {
      handler: function(val) {
        if (val) this.load();
      }
    }
  }
};
</script>

<style></style>
