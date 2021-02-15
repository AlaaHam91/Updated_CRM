<template>
  <div>
    <list-card
      :title="$t('companies')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Pg_company', 'Create')"
      :delete-btn="$can('Pg_company', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "@/components/list-card";
import api from "@/services/api/people-index/company.api.js";
export default {
  name: "company-index",

  components: {
    "list-card": listCard
  },

  data() {
    return {
      breadcrumbs: [
        { text: this.$t("peopleIndex") },
        { text: this.$t("company") }
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
        {
          text: this.$t("actions"),
          align: "center",
          value: "actions",
          sortable: false
        }
      ];
    }
  },
  methods: {
    editItem(item) {
      this.$router.push({
        name: "company-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "company-show",
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
