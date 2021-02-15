<template>
  <div style="height: 20rem;">
    <!-- items -->
    <v-container>
      <!-- list view -->
      <v-simple-table height="60vh" v-if="view == 'list'">
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-center">{{ $t("name") }}</th>
              <th class="text-center">{{ $t("type") }}</th>
              <th class="text-center">{{ $t("createdDate") }}</th>
              <th class="text-center">{{ $t("updateDate") }}</th>
              <th class="text-center">{{ $t("actions") }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items" :key="item.id" class="cursor-pointer">
              <td class="text-center text-no-wrap">
                <template v-if="item.type === 'url'">
                  <a
                    :href="item.alias_name"
                    v-text="item.alias_name"
                    target="_blank"
                  ></a>
                </template>
                <template v-else>
                  {{ item.alias_name }}
                </template>
              </td>

              <td class="text-center">
                <template v-if="item.type === 'folder'">
                  <v-btn icon small @dblclick="openFolder(item)">
                    <v-icon v-html="iconType(item.type)"> </v-icon>
                  </v-btn>
                </template>
                <template v-else>
                  <v-icon v-html="iconType(item.type)"> </v-icon>
                </template>
              </td>
              <td class="text-center" v-text="item.created_at"></td>
              <td class="text-center" v-text="item.updated_at"></td>

              <td class="text-center">
                <v-btn icon small @click="editItem(item)">
                  <v-icon> mdi-pencil</v-icon>
                </v-btn>
                <v-btn
                  v-if="item.type == 'file'"
                  icon
                  small
                  :disabled="downloaded && downloadedIndex === item.id"
                  @click="downloadItem(item)"
                >
                  <v-icon> mdi-download-outline</v-icon>
                </v-btn>
                <v-btn
                  :loading="deleteLoading && deletedIndex === item.id"
                  color="error"
                  icon
                  small
                  @click="deleteItem(item)"
                >
                  <v-icon> mdi-close-circle</v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
      <!-- card view -->
      <div v-else style="min-height: 60vh;">
        <v-row class="mx-1">
          <v-col cols="12" md="3" v-for="(item, i) in items" :key="i">
            <v-card width="100%" min-height="5rem">
              <v-card-text
                class="d-flex justify-center align-center flex-column "
              >
                <template v-if="item.type === 'folder'">
                  <v-btn icon small @dblclick="openFolder(item)">
                    <v-icon v-html="iconType(item.type)"> </v-icon>
                  </v-btn>
                </template>
                <template v-else>
                  <v-icon v-html="iconType(item.type)"> </v-icon>
                </template>
                <div
                  class="text-caption  text-truncate"
                  style="max-width: 130px;"
                  v-text="item.alias_name"
                ></div>
              </v-card-text>
              <v-card-actions class="d-flex justify-end mx-4">
                <v-btn
                  :loading="deleteLoading && deletedIndex === item.id"
                  color="error"
                  icon
                  small
                  @click="deleteItem(item)"
                >
                  <v-icon> mdi-close-circle</v-icon>
                </v-btn>
                <v-btn
                  v-if="item.type == 'file'"
                  :disabled="downloaded && downloadedIndex === item.id"
                  icon
                  small
                  @click="downloadItem(item)"
                >
                  <v-icon> mdi-download-outline</v-icon>
                </v-btn>
                <v-btn icon small @click="editItem(item)">
                  <v-icon> mdi-pencil</v-icon>
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </div>
</template>

<script>
import { mapFields } from "vuex-map-fields";
export default {
  name: "archive-list",
  data() {
    return {
      deleteLoading: false,
      downloaded: false,
      deletedIndex: null,
      downloadedIndex: null
    };
  },
  computed: {
    ...mapFields("archives", ["dialog"]),
    ...mapFields("archives", ["modalLoading"]),
    ...mapFields("archives", ["view"]),
    ...mapFields("archives", ["breadcrumbs"]),
    ...mapFields("archives", ["items"])
  },

  methods: {
    downloadItem(item) {
      this.clickedIndex = item.id;
      this.downloaded = true;
      this.$store
        .dispatch(`archives/downloadFile`, item)
        .then(() => {
          this.downloaded = false;
        })
        .catch(() => {});
    },
    editItem(item) {
      this.$store
        .dispatch(`archives/editModal`, item)
        .then(() => {
          this.dialog.modal = true;
        })
        .catch(() => {});
    },
    openFolder(folder) {
      this.$store
        .dispatch(`archives/step`, folder.id)
        .then(this.breadcrumbs.push({ text: folder.alias_name, ...folder }))
        .catch(() => {});
    },
    deleteItem(item) {
      this.clickedIndex = item.id;
      this.deleteLoading = true;
      this.$store
        .dispatch(`archives/delete`, item.id)
        .then(() => {
          this.deleteLoading = false;
        })
        .catch(() => {});
    },

    iconType(type) {
      switch (type) {
        case "url":
          return "mdi-timeline-text";
        case "file":
          return "mdi-file-outline";
        case "folder":
          return "mdi-folder-outline";
        default:
          return null;
      }
    }
  }
};
</script>

<style></style>
