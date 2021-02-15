<template>
  <div>
    <list-card
      :title="$t('users')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Cp_user_user', 'Create')"
      :delete-btn="$can('Cp_user_user', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/users/user.api";

export default {
  name: "user-index",

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
          width: "138px"
        },
        {
          text: this.$t("nameL"),
          value: "latin_name",
          align: "center",
          sortable: true,
          width: "138px"
        },
        // {
        //   text: this.$t("mobile"),
        //   value: "mobile",
        //   align: "center",
        //   sortable: true
        // },
        {
          text: this.$t("email"),
          value: "email",
          align: "center",
          sortable: true,
          width: "138px"
        },
        {
          text: this.$t("job"),
          value: "job",
          align: "center",
          sortable: true,
          width: "138px"
        },
        {
          text: this.$t("directManager"),
          value: "manager",
          align: "center",
          sortable: true,
          width: "138px"
        },
        {
          text: this.$t("branch"),
          value: "branch",
          align: "center",
          sortable: true,
          width: "138px"
        },
        {
          text: this.$t("department"),
          value: "department",
          align: "center",
          sortable: true,
          width: "138px"
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
          text: this.$t("users")
        },
        {
          text: this.$t("users")
        }
      ]
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "user-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "user-show",
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
