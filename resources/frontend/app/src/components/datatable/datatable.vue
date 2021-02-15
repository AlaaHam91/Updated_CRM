<template>
  <div v-if="storeModule">
    <toolbar :store-module="storeModule" />
    <div class="tableContainer ma-2" :style="`height: ${height};`">
      <datatable :store-module="storeModule" />
    </div>
  </div>
</template>
<script>
import datatable from "./internal/table";
import toolbar from "./internal/toolbar";

import module from "./internal/datatable.store.js";
const { state: stateModule, getters, mutations, actions } = module;

export default {
  props: {
    headers: { type: Array, required: true },
    items: { type: Array },
    height: { type: undefined, default: "20rem" },
    add: { type: Boolean, default: true },
    delete: { type: Boolean, default: true },
    modulePrefix: { type: String, default: "" }
  },
  components: {
    datatable,
    toolbar
  },
  data() {
    return {
      storeModule: null
    };
  },
  methods: {
    generateName() {
      let name = this.modulePrefix ? `${this.modulePrefix}-` : ``;
      name += `datatable-${Math.round(Math.random() * 1000000)}`;

      return name;
    },
    createModule() {
      let name = this.generateName();
      while (this.$store.hasModule(name)) {
        name = this.generateName();
      }
      this.storeModule = name;
      this.$emit("storeModule", name);

      //register new module
      this.$store.registerModule(`${this.storeModule}`, {
        state: stateModule,
        getters,
        mutations,
        actions,
        namespaced: true
      });

      this.$store.commit(`${this.storeModule}/headers`, {
        headers: this.headers
      });
      if (this.items)
        this.$store.dispatch(`${this.storeModule}/items`, {
          items: this.items
        });
      this.$store.commit(`${this.storeModule}/canAdd`, this.add);
      this.$store.commit(`${this.storeModule}/canDelete`, this.delete);
    }
  },

  created() {
    this.createModule();
  },

  beforeDestroy() {
    this.$store.dispatch(`${this.storeModule}/clearData`);
    this.$store.commit(`${this.storeModule}/headers`, { headers: [] });
    // await this.$store.unregisterModule(`${this.storeModule}`);
    // await this.$emit("storeModule", null);
  },

  watch: {
    add: {
      handler: function(val) {
        this.$store.commit(`${this.storeModule}/canAdd`, val);
      }
    },
    delete: {
      handler: function(val) {
        this.$store.commit(`${this.storeModule}/canDelete`, val);
      }
    },
    dirty: {
      handler: function(val) {
        if (val) this.$store.dispatch(`${this.storeModule}/doValidate`);
      },
      immediate: true
    },
    storeItems: {
      handler: function() {
        if (this.dirty) {
          this.$store.dispatch(`${this.storeModule}/doValidate`);
        }
      },
      immediate: true,
      deep: true
    }
  },

  computed: {
    dirty() {
      return this.$store.getters[`${this.storeModule}/dirty`];
    },
    storeItems() {
      return this.$store.getters[`${this.storeModule}/items`];
    }
  }
};
</script>
<style scoped>
.tableContainer {
  overflow-y: scroll;
  overflow-x: scroll;
}

*::-webkit-scrollbar {
  width: 10px;
  height: 5px;
}

*::-webkit-scrollbar-track {
  background: white;
}

*::-webkit-scrollbar-thumb {
  background-color: lightgrey;
  border-radius: 1px;
}

*::-webkit-scrollbar-thumb:hover {
  background-color: gray;
}
</style>
