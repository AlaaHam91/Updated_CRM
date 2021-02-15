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
    />
  </div>
</template>

<script>
import datatable from "@/components/datatable/datatable";

export default {
  name: "contracts",

  props: {
    items: { type: Array, required: true }
  },

  components: {
    datatable
  },

  data() {
    return {
      loading: false,
      headers: [
        {
          text: this.$t("number"),
          value: "number",
          width: "200px",
          type: "string",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("startDate"),
          value: "startDate",
          width: "200px",
          type: "date",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("endDate"),
          value: "endDate",
          width: "200px",
          type: "date",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("internalvisits"),
          value: "internalvisits",
          width: "200px",
          type: "number",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("externalVisits"),
          value: "externalVisits",
          width: "200px",
          type: "number",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("supportHours"),
          value: "supportHours",
          width: "200px",
          type: "number",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: this.$t("trainingHours"),
          value: "trainingHours",
          width: "200px",
          type: "number",
          readonly: false,
          visible: true,
          rules: [{ type: "required", message: this.$t("required") }]
        }
      ],
      module: null
    };
  },

  methods: {
    setStoreModule(name) {
      this.module = name;
      this.$emit("module", name);
    },
    async load() {
      //
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
