<template>
  <div>
    <list-card
      :title="$t('departments')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Cp_cpData_department', 'Create')"
      :delete-btn="$can('Cp_cpData_department', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/company-data/department.api.js";
export default {
  components: {
    "list-card": listCard
  },
  name: "department-index",
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("companyData"),
          disabled: false
        },
        {
          text: this.$t("departments"),
          disabled: false
        }
      ],
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
          text: this.$t("parent"),
          value: "parent_name",
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
        { text: this.$t("actions"), value: "actions", sortable: false }
      ],
      items: [],
      deleteInfo: {
        dialog: false,
        id: null
      },
      loading: false
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "department-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "department-show",
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
