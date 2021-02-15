<template>
  <div>
    <list-card
      :title="$t('eventLog')"
      :headers="headers"
      :items="items"
      :loading="loading"
      @loadData="load"
      :newBtn="false"
      :breadcrumbs="breadcrumbs"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/users/event-log.api";

export default {
  name: "event-log-index",

  components: {
    "list-card": listCard
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("date"),
          value: "date",
          align: "center",
          sortable: true
        },
        {
          text: this.$t("userName"),
          value: "user",
          align: "center",
          sortable: true
        },
        {
          text: this.$t("module"),
          value: "module",
          align: "center",
          sortable: true
        },
        {
          text: this.$t("page"),
          value: "page",
          align: "center",
          sortable: true
        },
        {
          text: this.$t("action"),
          value: "action",
          align: "center",
          sortable: true
        },
        {
          text: this.$t("details"),
          value: "details",
          align: "center",
          sortable: true
        }
      ],
      items: [],
      deleteInfo: {
        dialog: false,
        id: null
      },
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("users")
        },
        {
          text: this.$t("eventLog")
        }
      ]
    };
  },

  methods: {
    load(payload = null) {
      this.loading = true;

      api
        .getAll(payload)
        .then(res => {
          this.items = res;
        })
        .catch(() => {})
        .finally(() => (this.loading = false));
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
