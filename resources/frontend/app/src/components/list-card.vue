<template>
  <div>
    <v-sheet
      width="100%"
      color="#FFFFFF"
      class="d-flex flex-column align-start mb-4 pa-4"
      style="border-bottom: 1px solid lightgray;"
    >
      <div class="text-h5 ml-4" v-text="title"></div>
      <v-breadcrumbs v-if="breadcrumbs" :items="breadcrumbs"></v-breadcrumbs>
    </v-sheet>

    <v-container>
      <v-card width="100%" class="mb-4" v-if="!hideToolBar">
        <v-card-text>
          <v-btn
            v-if="newBtn"
            color="blue"
            class="white--text"
            @click="$emit('create-item')"
            depressed
          >
            <v-icon class="me-2">mdi-plus-circle-outline</v-icon>
            <span v-text="$t('newBtn')"></span>
          </v-btn>
        </v-card-text>
      </v-card>
      <slot name="top"></slot>
      <v-card width="100%">
        <v-data-table
          :headers="tHeaders"
          :items="items"
          class="elevation-1"
          height="20rem"
          fixed-header
          :loading="loading"
          :loading-text="loadingText"
        >
          <template v-slot:[`item.actions`]="{ item }">
            <v-hover v-slot:default="{ hover }" v-if="viewBtn">
              <v-btn icon>
                <v-icon
                  :color="hover ? 'primary' : ''"
                  class="mx-2"
                  @click="$emit('view-item', item)"
                >
                  mdi-eye-outline
                </v-icon>
              </v-btn>
            </v-hover>
            <v-hover v-slot:default="{ hover }" v-if="editBtn">
              <v-btn icon>
                <v-icon
                  :color="hover ? 'primary' : ''"
                  class="mx-2"
                  @click="$emit('edit-item', item)"
                >
                  mdi-file-edit-outline
                </v-icon>
              </v-btn>
            </v-hover>
            <v-hover v-slot:default="{ hover }" v-if="deleteBtn">
              <v-btn icon>
                <v-icon
                  :color="hover ? 'error' : ''"
                  class="mx-2"
                  @click="deleteItem(item)"
                >
                  mdi-delete-forever-outline
                </v-icon>
              </v-btn>
            </v-hover>
          </template>
          <template v-slot:no-data>
            <div class="text-body-1" v-text="$t('noData')"></div>
          </template>

          <template v-slot:header="{ props: { headers } }">
            <thead>
              <tr>
                <th v-for="(h, i) in headers" :key="i">
                  <search-filter
                    v-if="h.value != 'actions' && filters"
                    @set-filter="setFilter(i, $event)"
                  ></search-filter>
                </th>
              </tr>
            </thead>
          </template>
        </v-data-table>
      </v-card>
      <div class="d-flex justify-center mt-4">
        <slot name="footer"></slot>
      </div>
    </v-container>
    <confirm-dialog
      :isOpen="deleteInfo.dialog"
      :title="deleteInfo.title"
      :msg="deleteInfo.msg"
      @cancel="deleteInfo.dialog = false"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script>
import confirmDialog from "./confirmDialog";
import searcFilter from "./filter";
import { cloneDeep } from "lodash";

export default {
  name: "list-card",

  props: {
    title: String,
    headers: { type: Array, required: true },
    items: Array,
    loading: Boolean,
    breadcrumbs: Array,
    filters: { type: Boolean, default: true },
    hideToolBar: { type: Boolean, default: false },
    editBtn: { type: Boolean, default: true },
    deleteBtn: { type: Boolean, default: true },
    viewBtn: { type: Boolean, default: false },
    newBtn: { type: Boolean, default: true }
  },

  components: {
    "confirm-dialog": confirmDialog,
    "search-filter": searcFilter
  },

  data() {
    return {
      tHeaders: [],
      loadingText: this.$t("loadingText"),
      deleteInfo: {
        dialog: false,
        title: this.$t("delete"),
        msg: this.$t("sureToDoOperation"),
        id: null
      }
    };
  },

  methods: {
    deleteItem(item) {
      this.deleteInfo.dialog = true;
      this.deleteInfo.id = item.id;
    },
    confirmDelete() {
      this.deleteInfo.dialog = false;
      this.$emit("delete-item", this.deleteInfo.id);
    },
    setFilter(index, $event) {
      this.tHeaders[index]["filter"] = {};
      this.tHeaders[index]["filter"] = $event ? cloneDeep($event) : undefined;

      let url = "?";
      this.tHeaders.forEach(header => {
        if (header.filter)
          if (header.filter.value && header.filter.operator)
            url += `${header.value}=${header.filter.value}&${header.value}_filter=${header.filter.operator}`;
      });
      this.$emit("loadData", url.length > 2 ? url : null);
    }
  },

  created() {
    this.tHeaders = cloneDeep(this.headers);
  },

  watch: {
    headers: {
      handler: function(val) {
        if (val) this.tHeaders = cloneDeep(this.headers);
      }
    }
  }
};
</script>

<style scoped></style>
