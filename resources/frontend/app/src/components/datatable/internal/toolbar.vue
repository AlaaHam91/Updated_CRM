<template>
  <div>
    <v-toolbar flat dense>
      <v-btn v-if="haveAdd" icon @click="addIEmpytem">
        <v-icon>mdi-plus-box-outline</v-icon>
      </v-btn>

      <v-btn v-if="selected.length > 0 && haveDelete" icon @click="deleteRows">
        <v-icon>mdi-delete</v-icon>
      </v-btn>
    </v-toolbar>
  </div>
</template>

<script>
export default {
  name: "date-table-tool-bar",
  props: {
    storeModule: { type: String, default: null }
  },
  data() {
    return {};
  },
  computed: {
    haveAdd() {
      return this.$store.getters[`${this.storeModule}/canAdd`];
    },
    haveDelete() {
      return this.$store.getters[`${this.storeModule}/canDelete`];
    },
    headers() {
      return this.$store.getters[`${this.storeModule}/headers`];
    },
    selected() {
      return this.$store.getters[`${this.storeModule}/selectedRows`];
    }
  },
  methods: {
    addIEmpytem() {
      this.$store.dispatch(`${this.storeModule}/addEmptyItem`);
    },
    deleteRows() {
      this.$store.commit(`${this.storeModule}/deleteRows`);
    }
  }
};
</script>
<style scoped></style>
