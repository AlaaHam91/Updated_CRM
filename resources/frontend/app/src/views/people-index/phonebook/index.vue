<template>
  <div>
    <list-card
      :title="$t('phonebook')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Pg_phone_book', 'Create')"
      :delete-btn="$can('Pg_phone_book', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/people-index/phonebook.api";

export default {
  name: "phonebook-index",

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
      breadcrumbs: [
        { text: this.$t("peopleIndex") },
        { text: this.$t("phonebook") }
      ]
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "phonebook-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "phonebook-show",
        params: { id: 0 }
      });
    },
    deleteItem(id) {
      api.delete(id).then(() => {
        this.load();
      });
    },
    load(payload = null) {
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
