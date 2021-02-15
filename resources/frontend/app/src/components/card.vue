<template>
  <div>
    <!-- back to top btn -->
    <v-fab-transition>
      <v-btn
        v-scroll="onScroll"
        v-show="fab"
        fab
        dark
        fixed
        bottom
        right
        color="primary"
        @click="toTop"
      >
        <v-icon>mdi-chevron-up</v-icon>
      </v-btn>
    </v-fab-transition>
    <div class="d-flex flex-column align-center">
      <!-- title  -->
      <v-sheet
        width="100%"
        color="#FFFFFF"
        class="d-flex align-start pa-4"
        style="border-bottom: 1px solid lightgray; position: -webkit-sticky; position: sticky; top: 3rem; z-index: 2;"
      >
        <div class="d-flex flex-column">
          <div class="text-h5 ml-4" v-text="title"></div>
          <v-breadcrumbs
            v-if="breadcrumbs"
            :items="breadcrumbs"
          ></v-breadcrumbs>
        </div>
        <v-spacer></v-spacer>
        <v-btn v-if="!hideGo" elevation="2" fab @click="$router.go(-1)">
          <v-icon>mdi-arrow-left</v-icon>
        </v-btn>
      </v-sheet>

      <!-- toolbar -->
      <v-row
        v-if="!hideToolBar"
        style="width: 100%; position: -webkit-sticky; position: sticky; top: 10.6rem; z-index: 2; background-color: #F5F5F5;"
        justify="center"
      >
        <v-col cols="12">
          <v-card width="100%" color="white" class="px-4">
            <v-row>
              <v-col cols="12" md="6">
                <div>
                  <v-btn
                    v-for="(item, i) in actions"
                    :key="i"
                    @click="emitAction(item.action)"
                    :color="item.color"
                    class="white--text me-2"
                    depressed
                  >
                    <v-icon
                      color="white"
                      class="me-6"
                      v-text="item.icon"
                    ></v-icon>
                    <span
                      class="font-weight-light"
                      v-text="$t(item.name)"
                    ></span>
                  </v-btn>
                </div>
              </v-col>

              <v-col
                cols="12"
                md="6"
                class="d-flex justify-end"
                v-if="no !== 0 && navigate"
              >
                <v-sheet
                  rounded
                  color="appBack"
                  class="d-flex flex-row align-center px-1 rounded-xl"
                >
                  <template v-for="item in navigateItems">
                    <v-tooltip
                      bottom
                      open-delay="1000"
                      :key="item.index + 't'"
                      v-if="item.icon"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-hover
                          v-slot:default="{ hover }"
                          v-if="item.type == 'btn'"
                        >
                          <v-btn
                            @click="$emit(item.name)"
                            v-bind="attrs"
                            v-on="on"
                            class="mx-1"
                            icon
                            x-small
                          >
                            <v-icon
                              :color="hover ? `primary` : ''"
                              v-html="item.icon"
                            ></v-icon>
                          </v-btn>
                        </v-hover>
                      </template>
                      <span v-text="$t(item.name)"></span>
                    </v-tooltip>
                    <div
                      style="max-width: 3rem; width: 3rem;"
                      class="align-self-center mt-2"
                      :key="item.index"
                      v-if="item.type == 'input'"
                    >
                      <v-text-field
                        :value="no"
                        class="rec-no"
                        type="number"
                        min="0"
                        readonly
                        color="grey lighten-1"
                        outlined
                        filled
                        autocorrect="off"
                        spellcheck="false"
                        autocomplete="off"
                      ></v-text-field>
                    </div>
                  </template>
                </v-sheet>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
      <!-- custom toolbar -->
      <v-row style="width: 100%;">
        <v-col cols="12">
          <slot name="customToolBar"></slot>
        </v-col>
      </v-row>
      <!-- content -->
      <v-row style="width: 100%;">
        <v-col cols="12">
          <v-card width="100%" color="white">
            <v-progress-linear
              indeterminate
              color="#263238"
              v-if="loading"
            ></v-progress-linear>

            <v-card-text>
              <slot></slot>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </div>
    <confirm-dialog
      :isOpen="deleteInfo.dialog"
      :title="$t('delete')"
      :msg="$t('sureToDoOperation')"
      @cancel="deleteInfo.dialog = false"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script>
import confirmDialog from "./confirmDialog";

export default {
  name: "card",
  props: {
    title: String,
    no: Number,
    navigate: { type: Boolean, default: true },
    newBtn: { type: Boolean, default: false },
    saveBtn: { type: Boolean, default: false },
    saveNewBtn: { type: Boolean, default: false },
    deleteBtn: { type: Boolean, default: false },
    breadcrumbs: { type: Array },
    hideToolBar: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    hideGo: { type: Boolean, default: false }
  },
  components: {
    "confirm-dialog": confirmDialog
  },
  data() {
    return {
      actionItems: [
        {
          icon: "mdi-asterisk",
          action: "reset-form",
          name: "newBtn",
          color: "blue"
        },
        {
          icon: "mdi-content-save-outline",
          action: "save",
          name: "saveBtn",
          color: "green"
        },
        {
          icon: "mdi-file-plus",
          action: "save-and-new",
          name: "saveNewBtn",
          color: "green"
        },
        {
          icon: "mdi-delete-sweep",
          action: "delete-item",
          name: "deleteBtn",
          color: "red"
        }
      ],
      navigateItems: [
        { index: 0, type: "btn", icon: "mdi-rewind", name: "first" },
        {
          index: 1,
          type: "btn",
          icon: "mdi-play mdi-flip-h",
          name: "previous"
        },
        { index: 2, type: "input", icon: "no", name: "current" },
        { index: 3, type: "btn", icon: "mdi-play", name: "next" },
        { index: 4, type: "btn", icon: "mdi-fast-forward ", name: "last" }
      ],
      deleteInfo: {
        dialog: false
      },
      fab: false
    };
  },
  methods: {
    emitAction(action) {
      if (action == "delete-item") {
        this.deleteInfo.dialog = true;
      } else this.$emit(action);
    },
    confirmDelete() {
      this.$emit("delete-item");
      this.deleteInfo.dialog = false;
    },
    onScroll(e) {
      if (typeof window === "undefined") return;
      const top = window.pageYOffset || e.target.scrollTop || 0;
      this.fab = top > 20;
    },
    toTop() {
      this.$vuetify.goTo(0);
    }
  },
  computed: {
    actions() {
      let result = [];
      result = this.actionItems.filter(element => {
        if (element.divider) return element;
        else if (this.$props[element.name]) return element;
      });
      result.sort((a, b) => a.index - b.index);

      return result;
    }
  }
};
</script>

<style scoped>
.rec-no >>> .v-text-field__details {
  display: none;
}

.rec-no >>> .v-input__slot {
  min-height: unset !important;
}

.rec-no >>> input {
  max-height: 22px !important;
  text-align: center;
  font-size: 0.75rem;
}

.rec-no >>> input::-webkit-outer-spin-button,
.rec-no >>> input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.cardBack {
  border-color: unset !important;
}

.v-card.bordered-card {
  border-top: 8px solid #4ccee9 !important;
}

.side-toolbar {
  position: fixed;
  right: 1rem;
  top: 9rem;
}

.side-toolbar-rtl {
  position: fixed;
  left: 1rem;
  top: 10rem;
}

.seprator {
  width: 3rem;
  opacity: 0.4;
}
</style>
