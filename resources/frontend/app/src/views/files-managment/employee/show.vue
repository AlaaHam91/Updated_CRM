<template>
  <card :no="0" :hideToolBar="true" :breadcrumbs="breadcrumbs">
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
    <v-row>
      <v-col cols="12" md="3">
        <div
          v-if="treeLoading"
          class="d-flex justify-center align-center"
          style="height: 5rem;"
        >
          <v-progress-circular indeterminate></v-progress-circular>
        </div>
        <v-card v-else max-width="500" class="mt-2">
          <v-sheet class="pa-4 transparent lighten-2">
            <v-text-field
              v-model="search"
              :label="$t('search')"
              hide-details
              clearable
              clear-icon="mdi-close-circle-outline"
              color="primary"
              background-color="inputBack"
              dense
              outlined
              selection-type="independent"
            ></v-text-field>
          </v-sheet>
          <v-card-text>
            <v-treeview
              :items="hierarchyItems"
              color="primary"
              background-color="inputBack"
              dense
              activatable
              :search="search"
              :search-input.sync="search"
              active-class="grey lighten-4 indigo--text"
              open-all
              hoverable
              transition
              @update:active="loadFromTree"
              return-object
            >
            </v-treeview>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="9">
        <v-toolbar v-if="permissions.addData" class="my-2" elevation="1">
          <v-btn
            class="mx-2"
            v-text="$t('addFile')"
            @click="openModal('file')"
          ></v-btn>
          <v-btn
            class="mx-2"
            v-text="$t('addFolder')"
            @click="openModal('folder')"
          ></v-btn>
          <v-btn
            class="mx-2"
            v-text="$t('addLink')"
            @click="openModal('url')"
          ></v-btn>
          <v-spacer></v-spacer>
          <v-btn-toggle v-model="view" mandatory>
            <v-btn value="list">
              <v-icon>mdi-view-list</v-icon>
            </v-btn>
            <v-btn value="cards">
              <v-icon>mdi-view-module</v-icon>
            </v-btn>
          </v-btn-toggle>
        </v-toolbar>

        <v-row class="justify-space-between">
          <v-breadcrumbs :items="displaybreadcrumbs" divider="/">
            <v-breadcrumbs-item
              v-for="breadcrumb in displaybreadcrumbs"
              :key="breadcrumb.id"
              exact
              @click="back(breadcrumb)"
            >
              {{ breadcrumb.text }}
            </v-breadcrumbs-item>
          </v-breadcrumbs>
          <v-btn-toggle v-model="tab">
            <v-btn @click="load" v-text="$t('myFiles')" value="myFiles"></v-btn>
            <v-btn
              @click="load"
              v-if="permissions.permissionAndSharings"
              v-text="$t('sharedWithMe')"
              value="sharedWithMe"
            ></v-btn>
          </v-btn-toggle>
        </v-row>

        <div
          v-if="archiveloading"
          class="d-flex justify-center align-center"
          style="height: 5rem;"
        >
          <v-progress-circular indeterminate></v-progress-circular>
        </div>

        <archives :items="displayData" @open-folder="openFolder" v-else />
      </v-col>

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
      <!-- controls -->
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import archives from "./archives";
import { mapFields } from "vuex-map-fields";
import module from "./employee.store";
import modal from "./modal";
import { cloneDeep } from "lodash";

export default {
  components: {
    card,
    archives,
    modal
  },
  name: "employees-files-show",
  data() {
    return {
      search: null,
      displayData: [],
      tab: "myFiles"
    };
  },
  computed: {
    ...mapFields("employeeFiles", ["breadcrumbs"]),
    ...mapFields("employeeFiles", ["displaybreadcrumbs"]),
    ...mapFields("employeeFiles", ["treeLoading"]),
    ...mapFields("employeeFiles", ["archiveloading"]),
    ...mapFields("employeeFiles", ["hierarchyItems"]),
    ...mapFields("employeeFiles", ["hierarchy"]),
    ...mapFields("employeeFiles", ["dialog"]),
    ...mapFields("employeeFiles", ["modalLoading"]),
    ...mapFields("employeeFiles", ["permissions"]),
    ...mapFields("employeeFiles", ["view"]),
    ...mapFields("employeeFiles", ["itemType"]),
    ...mapFields("employeeFiles", ["items"]),
    ...mapFields("employeeFiles", ["messages"])
  },
  methods: {
    loadFromTree(selectedLeaf) {
      this.hierarchy = cloneDeep(selectedLeaf);
      this.load();
    },
    load() {
      this.$store.dispatch(`employeeFiles/load`);
    },
    loadInitial() {
      this.$store.dispatch(`employeeFiles/loadInitial`);
      this.load();
    },
    async openModal(type) {
      await this.$store.dispatch(`employeeFiles/resetModal`);
      this.itemType = type;
      this.dialog.modal = true;
    },
    openFolder(folder) {
      this.$store
        .dispatch(`employeeFiles/step`, folder.id)
        .then(this.displaybreadcrumbs.push({ text: folder.name, ...folder }))
        .catch(() => {});
    },
    back(breadcrumb) {
      let lastBreadcrumb = this.displaybreadcrumbs[
        this.displaybreadcrumbs.length - 1
      ];
      if (breadcrumb.id === lastBreadcrumb.id) return;

      if (
        this.displaybreadcrumbs.length > 1 &&
        this.displaybreadcrumbs[0].id === breadcrumb.id
      ) {
        this.displaybreadcrumbs.length = 1;
        this.$store.dispatch(`employeeFiles/goToRoot`);
      } else {
        let currentBreadcrumbIndex = this.displaybreadcrumbs.indexOf(
          breadcrumb
        );
        let sufBreadcrumb = this.displaybreadcrumbs[currentBreadcrumbIndex + 1];

        this.$store.dispatch(`employeeFiles/step`, sufBreadcrumb.parent_id);
        this.displaybreadcrumbs.splice(currentBreadcrumbIndex + 1);
      }
    }
  },
  watch: {
    "$store.state.employeeFiles.items": {
      handler(val) {
        if (this.tab == "myFiles") this.displayData = cloneDeep(val.myFiles);
        else this.displayData = cloneDeep(val.sharedWithFiles);
      },
      deep: true
    },
    async tab(val) {
      this.displaybreadcrumbs = [];
      if (val == "myFiles") {
        this.displayData = cloneDeep(this.items.myFiles);
        this.displaybreadcrumbs.push({ text: this.$t(val), val: "myFiles" });
      } else {
        this.displayData = cloneDeep(this.items.sharedWithFiles);
        this.displaybreadcrumbs.push({
          text: this.$t(val),
          val: "sharedWithMe"
        });
      }
    }
  },

  created() {
    this.$store.registerModule("employeeFiles", module);
    this.loadInitial();
  },
  beforeDestroy() {
    this.$store.unregisterModule("employeeFiles");
  }
};
</script>

<style></style>
