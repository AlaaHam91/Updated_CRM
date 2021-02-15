<template>
  <div class="d-flex flex-row align-center">
    <v-btn x-small icon @click="clickInput">
      <v-icon>mdi-magnify</v-icon>
    </v-btn>
    <input
      :value="selectedValue"
      @click="clickInput"
      type="text"
      class="cellInput cursor-pointer"
      autocomplete="off"
      spellcheck="false"
      readonly
    />
    <v-btn
      v-if="selectedValue && !readonly"
      color="error"
      @click="clearData"
      x-small
      icon
    >
      <v-icon>mdi-close</v-icon>
    </v-btn>
    <v-dialog
      v-model="dialog"
      scrollable
      :fullscreen="$vuetify.breakpoint.mobile"
      :overlay="false"
      max-width="500px"
      transition="dialog-transition"
    >
      <v-card>
        <v-card-title primary-title>
          <span v-text="$t('search')"></span>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            :placeholder="$t('search')"
            color="primary"
            background-color="inputBack"
            dense
            outlined
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="items"
            :items-per-page="10"
            :search="search"
            class="elevation-1 row-pointer"
            height="15rem"
            @click:row="rowClick"
            dense
          ></v-data-table>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "search",
  props: {
    value: undefined,
    headers: { type: Array, required: true },
    items: { type: Array, required: true },
    itemText: { type: String, required: true },
    itemValue: { type: String, required: true },
    readonly: { type: Boolean, default: false }
  },

  data() {
    return {
      selectedValue: null,
      dialog: false,
      search: null
    };
  },

  methods: {
    clickInput() {
      if (!this.readonly) this.dialog = true;
    },
    clearData() {
      this.selectedValue = null;
      this.$emit("input", null);
    },
    rowClick(val) {
      if (val) {
        this.selectedValue = val[this.itemText];
        this.dialog = false;
        this.$emit("input", val[this.itemValue]);
      }
    }
  },

  watch: {
    value: {
      handler: async function(val) {
        if (!val) return;

        let item = await this.items.find(e => e[`${this.itemValue}`] == val);
        this.selectedValue = await item[`${this.itemText}`];
      },
      deep: true,
      immediate: true
    }
  },

  created() {
    //
  }
};
</script>

<style lang="css" scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>
