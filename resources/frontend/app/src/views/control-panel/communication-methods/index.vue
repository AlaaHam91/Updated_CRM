<template>
  <div>
    <list-card
      :title="$t('communicationMethods')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Pg_comm_methods', 'Create')"
      :delete-btn="$can('Pg_comm_methods', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/control-panel/communication-method.api.js";
export default {
  components: {
    "list-card": listCard
  },
  name: "communication-method-index",
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("controlPanel")
        },
        {
          text: this.$t("communicationMethods")
        }
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
          width: "300px"
        },
        {
          text: this.$t("nameL"),
          value: "latin_name",
          align: "center",
          sortable: true,
          width: "300px"
        },

        { text: this.$t("actions"), value: "actions", sortable: false }
      ];
    }
  },
  methods: {
    editItem(item) {
      this.$router.push({
        name: "communication-method-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "communication-method-show",
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
