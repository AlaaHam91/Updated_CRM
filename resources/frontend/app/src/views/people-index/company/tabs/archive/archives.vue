<template>
  <div>
    <v-row v-for="(item, i) in messages" :key="i" no-gutters>
      <v-col cols="12" md="4">
        <v-alert
          icon="mdi-information"
          color="red"
          type="error"
          v-text="item"
        ></v-alert>
      </v-col>
    </v-row>
    <v-toolbar class="my-2" elevation="1">
      <v-btn
        v-if="can"
        class="mx-2"
        v-text="$t('addFile')"
        @click="openModal('file')"
      ></v-btn>
      <v-btn
        v-if="can"
        class="mx-2"
        v-text="$t('addFolder')"
        @click="openModal('folder')"
      ></v-btn>
      <!-- <v-btn
        class="mx-2"
        v-text="$t('addLink')"
        @click="openModal('url')"
      ></v-btn> -->
      <v-spacer></v-spacer>
      <v-btn-toggle v-model="view" dense mandatory style="direction: ltr;">
        <v-btn value="list">
          <v-icon>mdi-view-list</v-icon>
        </v-btn>
        <v-btn value="cards">
          <v-icon>mdi-view-module</v-icon>
        </v-btn>
      </v-btn-toggle>
    </v-toolbar>
    <v-row class="justify-space-between">
      <v-breadcrumbs :items="breadcrumbs" divider="/">
        <v-breadcrumbs-item
          v-for="breadcrumb in breadcrumbs"
          :key="breadcrumb.id"
          exact
          @click="back(breadcrumb)"
        >
          {{ breadcrumb.text }}
        </v-breadcrumbs-item>
      </v-breadcrumbs>
    </v-row>
    <div
      v-if="archiveloading"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
    <archive-list v-else />

    <v-dialog
      scrollable
      max-width="500px"
      transition="dialog-transition"
      v-model="dialog.modal"
      persistent
    >
      <div
        v-if="modalLoading"
        class="d-flex justify-center align-center"
        style="height: 5rem;"
      >
        <v-progress-circular indeterminate></v-progress-circular>
      </div>
      <modal v-else-if="dialog.modal" />
    </v-dialog>
  </div>
</template>

<script>
import archiveList from "./archive-list";
import { mapFields } from "vuex-map-fields";
import module from "./archive.store";
import modal from "./modal";

export default {
  components: {
    "archive-list": archiveList,
    modal
  },
  name: "archive-show",
  props: {
    api: {
      type: String,
      required: true
    },
    recordId: {
      type: undefined,
      required: true
    },
    can: { type: Boolean, default: true }
  },
  data() {
    return {
      search: null,
      displayData: []
    };
  },
  computed: {
    ...mapFields("archives", ["archiveloading"]),
    ...mapFields("archives", ["dialog"]),
    ...mapFields("archives", ["modalLoading"]),
    ...mapFields("archives", ["view"]),
    ...mapFields("archives", ["itemType"]),
    ...mapFields("archives", ["breadcrumbs"]),
    ...mapFields("archives", ["apiPath"]),
    ...mapFields("archives", ["id"]),
    ...mapFields("archives", ["messages"]),

    items() {
      return this.$store.state.archives.items;
    }
  },
  methods: {
    load() {
      if (this.id && this.apiPath) this.$store.dispatch(`archives/goToRoot`);
    },
    async openModal(type) {
      await this.$store.dispatch(`archives/resetModal`);
      await this.$store.dispatch(`archives/loadFolders`);
      this.itemType = type;
      this.dialog.modal = true;
    },
    back(breadcrumb) {
      let lastBreadcrumb = this.breadcrumbs[this.breadcrumbs.length - 1];
      if (breadcrumb.id === lastBreadcrumb.id) return;

      if (
        this.breadcrumbs.length > 1 &&
        this.breadcrumbs[0].id === breadcrumb.id
      ) {
        this.breadcrumbs.length = 1;
        this.$store.dispatch(`archives/goToRoot`);
      } else {
        let currentBreadcrumbIndex = this.breadcrumbs.indexOf(breadcrumb);
        let sufBreadcrumb = this.breadcrumbs[currentBreadcrumbIndex + 1];
        this.$store.dispatch(`archives/step`, sufBreadcrumb.parent_id);
        this.breadcrumbs.splice(currentBreadcrumbIndex + 1);
      }
    }
  },
  watch: {
    api: {
      handler: function(newVal, oldVal) {
        if (newVal === oldVal) return;
        this.apiPath = this.newVal;
        this.load();
      }
    },
    recordId: {
      handler: function(val) {
        this.id = val;
        this.load();
      }
    }
  },
  created() {
    this.$store.registerModule("archives", module);
    this.apiPath = this.api;
    this.id = this.recordId;
    this.load();
  },
  beforeDestroy() {
    this.$store.unregisterModule("archives");
  }
};
</script>

<style></style>
