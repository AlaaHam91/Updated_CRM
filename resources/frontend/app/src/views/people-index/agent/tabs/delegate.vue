<template>
  <div>
    <datatable
      :headers="headers"
      @storeModule="setStoreModule"
      height="10rem"
      :add="false"
      :delete="false"
    />
  </div>
</template>

<script>
import datatable from "@/components/datatable/datatable";

export default {
  name: "docs",

  components: {
    datatable
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("status"),
          value: "status",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("lastStatus"),
          value: "lastStatus",
          width: "200px",
          type: "date",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("name"),
          value: "name",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("jobTitle"),
          value: "jobTitle",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("job"),
          value: "job",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("language"),
          value: "language",
          width: "200px",
          type: "string",
          readonly: true,
          visible: true
        },
        {
          text: this.$t("isManager"),
          value: "isManager",
          type: "string",
          readonly: true,
          visible: true,
          width: "200px"
        },
        {
          text: this.$t("mobile"),
          value: "mobile",
          type: "combobox",
          readonly: true,
          visible: true,
          width: "200px"
        },
        {
          text: this.$t("email"),
          value: "email",
          type: "combobox",
          readonly: true,
          visible: true,
          width: "200px"
        },
        {
          text: this.$t("phone"),
          value: "phone",
          type: "combobox",
          readonly: true,
          visible: true,
          width: "200px"
        },
        {
          text: this.$t("newTicket"),
          value: "link",
          type: "btnLink",
          readonly: true,
          visible: true,
          width: "200px"
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
