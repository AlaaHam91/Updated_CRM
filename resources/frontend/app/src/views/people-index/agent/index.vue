<template>
  <div>
    <list-card
      :title="$t('agent')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Pg_agent', 'Create')"
      :delete-btn="$can('Pg_agent', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/people-index/agent.api";

export default {
  name: "agent-index",

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
          sortable: true
        },
        {
          text: this.$t("nameL"),
          value: "latin_name",
          align: "center",
          sortable: true
        },
        {
          text: this.$t("actions"),
          value: "actions",
          align: "center",
          sortable: false
        }
      ],
      items: [],
      deleteInfo: {
        dialog: false,
        id: null
      },
      loading: false,
      filters: {
        // areaName: null
      },
      breadcrumbs: [
        {
          text: this.$t("peopleIndex")
        },
        {
          text: this.$t("agent")
        }
      ]
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "agent-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "agent-show",
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
