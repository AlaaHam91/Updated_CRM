<template>
  <div>
    <list-card
      :title="$t('administrativeDivisions')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Cp_coding_ad', 'Create')"
      :delete-btn="$can('Cp_coding_ad', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/control-panel/administrative-division.api.js";

export default {
  components: {
    "list-card": listCard
  },
  name: "administrative-division-index",
  data() {
    return {
      breadcrumbs: [
        { text: this.$t("controlPanel") },
        { text: this.$t("administrativeDivisions") }
      ],
      items: [],
      deleteInfo: {
        dialog: false,
        id: null
      },
      loading: false
    };
  },
  computed: {
    headers() {
      return [
        {
          text: this.$t("name"),
          value: "name",
          align: "center",
          sortable: true,
          width: "190px"
        },
        {
          text: this.$t("nameL"),
          value: "latin_name",
          align: "center",
          sortable: true,
          width: "190px"
        },
        {
          text: this.$t("country"),
          value: "country",
          align: "center",
          sortable: true,
          width: "190px"
        },
        {
          text: this.$t("level"),
          value: "level",
          align: "center",
          sortable: true,
          width: "190px"
        },
        {
          text: this.$t("parent"),
          value: "parent",
          align: "center",
          sortable: true,
          width: "190px"
        },

        { text: this.$t("actions"), value: "actions", sortable: false }
      ];
    }
  },
  methods: {
    editItem(item) {
      this.$router.push({
        name: "administrative-division-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "administrative-division-show",
        params: { id: 0 }
      });
    },
    deleteItem(id) {
      api
        .delete(id)
        .then(() => {
          this.load();
        })
        .catch(() => {});
    },

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
