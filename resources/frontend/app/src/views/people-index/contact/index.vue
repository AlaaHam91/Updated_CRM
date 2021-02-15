<template>
  <div>
    <list-card
      :title="$t('contact')"
      :headers="headers"
      :items="items"
      @edit-item="editItem"
      @create-item="createItem"
      @delete-item="deleteItem"
      :loading="loading"
      @loadData="load"
      :breadcrumbs="breadcrumbs"
      :new-btn="$can('Pg_contact', 'Create')"
      :delete-btn="$can('Pg_contact', 'Delete')"
    >
    </list-card>
  </div>
</template>

<script>
import listCard from "./../../../components/list-card";
import api from "@/services/api/people-index/contact.api";

export default {
  name: "contact-index",

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
        { text: this.$t("contact") }
      ]
    };
  },

  methods: {
    editItem(item) {
      this.$router.push({
        name: "contact-show",
        params: { id: item.id }
      });
    },
    createItem() {
      this.$router.push({
        name: "contact-show",
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
