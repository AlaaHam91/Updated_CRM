<template>
  <div>
    <list-card
      :title="$t('additionalFields')"
      :headers="headers"
      :items="items"
      @create-item="createItem"
      @edit-item="editItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Pg_add_info', 'Create')"
      :delete-btn="$can('Pg_add_info', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/people-settings/additional.api";

export default {
  name: "company-index",

  components: {
    "list-card": listCard
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("name"),
          value: "name",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("nameL"),
          value: "latin_name",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("type"),
          value: "type",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("order"),
          value: "order_number",
          align: "center",
          sortable: true,
          width: "200px"
        },
        {
          text: this.$t("actions"),
          align: "center",
          value: "actions",
          sortable: false,
          width: "200px"
        }
      ],
      items: [],
      deleteInfo: {
        dialog: false,
        id: null
      },
      loading: false,
      breadcrumbs: [
        { text: this.$t("peopleSettings") },
        { text: this.$t("additionalFields") }
      ]
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "additional-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "additional-show",
        params: { id: 0 }
      });
    },
    deleteItem(id) {
      api.delete(id).then(() => {
        this.load();
      });
    },
    load(payload = null) {
      this.loading = true;
      api
        .getAll(payload)
        .then(res => {
          this.items = res;
        })
        .finally(() => (this.loading = false));
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
