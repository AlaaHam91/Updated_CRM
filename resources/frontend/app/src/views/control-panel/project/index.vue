<template>
  <div>
    <list-card
      :title="$t('projects')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Cp_other_project', 'Create')"
      :delete-btn="$can('Cp_other_project', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/control-panel/project.api";

export default {
  name: "project-index",

  components: {
    "list-card": listCard
  },

  data() {
    return {
      headers: [
        {
          text: this.$t("code"),
          value: "code",
          align: "center",
          sortable: true,
          width: "140px"
        },
        {
          text: this.$t("name"),
          value: "name",
          align: "center",
          sortable: true,
          width: "140px"
        },
        {
          text: this.$t("nameL"),
          value: "latin_name",
          align: "center",
          sortable: true,
          width: "140px"
        },
        {
          text: this.$t("customer"),
          value: "customer",
          align: "center",
          sortable: true,
          width: "140px"
        },

        {
          text: this.$t("branch"),
          value: "branch",
          align: "center",
          sortable: true,
          width: "140px"
        },
        {
          text: this.$t("department"),
          value: "department",
          align: "center",
          sortable: true,
          width: "140px"
        },
        {
          text: this.$t("employee"),
          value: "employee",
          align: "center",
          sortable: true,
          width: "140px"
        },
        { text: this.$t("actions"), value: "actions", sortable: false }
      ],
      items: [],
      deleteInfo: {
        dialog: false,
        id: null
      },
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("controlPanel")
        },
        {
          text: this.$t("projects")
        }
      ]
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "project-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "project-show",
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
