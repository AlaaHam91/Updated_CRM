<template>
  <div>
    <datatable
      :headers="headers"
      :items="items"
      @storeModule="setStoreModule"
      height="20rem"
      :add="false"
      :delete="false"
    />
  </div>
</template>

<script>
import datatable from "@/components/datatable/datatable";

export default {
  name: "log",

  props: {
    items: { type: Array, required: true }
  },

  components: {
    datatable
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("code"),
          value: "code",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("company"),
          value: "company",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("department"),
          value: "department",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("branch"),
          value: "branch",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("title"),
          value: "title",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("date"),
          value: "date",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("time"),
          value: "time",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        }
      ]
    };
  },

  methods: {
    setStoreModule(name) {
      this.$emit("module", name);
    }
  }
};
</script>

<style></style>
