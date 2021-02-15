<template>
  <div>
    <v-autocomplete
      :value="value"
      :items="api ? apiItems : items"
      :loading="isLoading"
      :item-text="itemText"
      :item-value="itemValue"
      :placeholder="placeholder"
      @input="emitValue"
      prepend-inner-icon="mdi-magnify"
      @click:prepend-inner="startModal"
      return-object
      color="primary"
      background-color="inputBack"
      dense
      outlined
      hide-no-data
      chips
      small-chips
      single-line
      :hint="readonly ? '' : $t('typeToSearch')"
      :clearable="readonly || disabled ? undefined : true"
      eager
      :error-messages="errorMessages"
      :disabled="disabled"
      :readonly="readonly"
    ></v-autocomplete>
    <v-dialog
      v-model="dialog.modal"
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
            v-model="dialog.search"
            append-icon="mdi-magnify"
            :placeholder="$t('search')"
            color="primary"
            background-color="inputBack"
            dense
            outlined
          ></v-text-field>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="api ? apiItems : items"
            class="row-pointer"
            :search="dialog.search"
            @click:row="rowClick"
            fixed-header
            height="20rem"
            :loading="isLoading"
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
    placeholder: String,
    errorMessages: Array,
    itemText: { type: String, default: "" },
    itemValue: { type: String, default: "" },
    api: { type: String },
    funcName: { type: String, default: "getAll" },
    funcPayLoad: { type: undefined, default: null },
    queryParams: { type: Object },
    headers: { type: Array, required: true },
    disabled: { type: Boolean, default: false },
    readonly: { type: Boolean, default: false },
    returnObject: { type: Boolean, default: false },
    items: { type: Array }
  },

  data() {
    return {
      apiItems: [],
      isLoading: false,
      dialog: {
        modal: false,
        search: null
      }
    };
  },

  methods: {
    load() {
      this.isLoading = true;
      let api = require(`@/services/api/${this.api}`);
      api.default[this.funcName](this.funcPayLoad ? this.funcPayLoad : null)
        .then(res => {
          this.apiItems = res;
        })
        .finally(() => (this.isLoading = false));
    },
    rowClick(value) {
      if (value) {
        this.dialog.modal = false;
        this.emitValue(value);
      }
    },
    emitValue(val) {
      let value =
        this.returnObject == true ? val : val ? val[`${this.itemValue}`] : null;
      this.$emit("input", value);
    },
    startModal() {
      if (!this.readonly) this.dialog.modal = true;
    }
  },

  created() {
    if (this.api) this.load();
  },

  beforeDestroy() {
    this.apiItems = [];
    this.isLoading = false;
    this.dialog = {
      modal: false,
      search: null
    };
  },

  watch: {
    api: {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.load();
      }
    },
    funcName: {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.load();
      }
    },
    funcPayLoad: {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.load();
      }
    }
  }
};
</script>

<style lang="css" scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>
