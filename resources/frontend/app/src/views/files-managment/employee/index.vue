<template>
  <div>
    <list-card
      :title="$t('employeeFiles')"
      :headers="headers"
      :items="items"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      @create-item="createItem"
      @delete-item="deleteItem"
      @edit-item="editItem"
      :new-btn="$can('Cp_fM_EmployeeFile', 'Create')"
      :delete-btn="$can('Cp_fM_EmployeeFile', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/files-managment/employee.api";

export default {
  name: "employees-files-index",

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
          text: this.$t("type"),
          value: "type",
          align: "center",
          sortable: true,
          width: "200px"
        },
        // {
        //   text: this.$t("size"),
        //   value: "file_size",
        //   align: "center",
        //   sortable: true,
        //   width: "200px"
        // },
        // {
        //   text: this.$t("DownloadNo"),
        //   value: "downloads_no",
        //   align: "center",
        //   sortable: true,
        //   width: "200px"
        // },
        {
          text: this.$t("createdDate"),
          value: "created_at",
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
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("filesManagment")
        },
        {
          text: this.$t("employeesFiles")
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
    },
    editItem() {
      this.$router.push({
        name: "employee-files-show",
        params: { id: 0 }
      });
    },
    createItem() {
      this.$router.push({
        name: "employee-files-show",
        params: { id: 0 }
      });
    },
    deleteItem(id) {
      let data = { data_id: id };

      api
        .delete(data)
        .then(() => {
          this.load();
        })
        .catch(() => {});
    }
  },

  created() {
    this.load();
  }
};
</script>

<style></style>
