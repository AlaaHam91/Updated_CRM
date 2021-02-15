import { getField, updateField } from "vuex-map-fields";
import promise from "promise";
import i18n from "@/plugins/i18n";

import _ from "lodash";
import axios from "@/plugins/api";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [{ text: i18n.t("all") }],
    items: [],
    apiPath: null,
    id: null,
    //dialog
    dialog: { modal: false },
    urlText: null,
    alias: null,
    parent: null,
    remoteFiles: [],
    foldersItems: [],
    editedItem: {},
    //
    archiveloading: false,
    modalLoading: false,
    itemType: null,
    view: "list",
    messages: []
  },
  getters: {
    getField
  },
  mutations: {
    updateField,
    messages(state, data) {
      state.messages = data.messages;
    },
    loadItems(state, res) {
      state.items = res;
    },
    loadFolders(state, res) {
      state.foldersItems = res;
    }
  },
  actions: {
    //load parent folders for the modal
    loadFolders({ state, commit }) {
      state.modalLoading = true;
      let api = require(`@/services/api/${state.apiPath}`);
      api.default
        .getFolders(state.id)
        .then(res => commit("loadFolders", res))
        .catch(() => {})
        .finally(() => {
          state.modalLoading = false;
        });
    },
    checkLoading({ state, commit }, res) {
      let lastBreadcrumb = state.breadcrumbs[state.breadcrumbs.length - 1];
      //check where user add with last b
      if (state.parent === null) {
        if (state.breadcrumbs.length > 1) return;
      } else {
        let parent = state.foldersItems.find(
          folder => folder.id === state.parent
        );

        if (parent.id !== lastBreadcrumb.id) return;
      }
      commit("loadItems", res);
    },
    step({ state, commit }, id) {
      state.archiveloading = true;
      let api = require(`@/services/api/${state.apiPath}`);
      api.default
        .getFilesByFolderID(state.id, id)
        .then(res => commit("loadItems", res))
        .catch(() => {})

        .finally(() => {
          state.archiveloading = false;
        });
    },
    goToRoot({ state, commit }) {
      state.archiveloading = true;
      let api = require(`@/services/api/${state.apiPath}`);
      api.default
        .getRoot(state.id)
        .then(res => commit("loadItems", res))
        .catch(() => {})

        .finally(() => {
          state.archiveloading = false;
        });
    },
    //delete selected remote item
    delete({ state, commit }, id) {
      return new promise(async (resolve, reject) => {
        let api = require(`@/services/api/${state.apiPath}`);
        api.default
          .deleteFile(state.id, id)
          .then(res => commit("loadItems", res))
          .catch(() => reject());
      });
    },
    //edit item
    async editModal({ state, dispatch }, item) {
      await dispatch("loadFolders");
      state.editedItem = item;
      item.type == "url"
        ? (state.urlText = item.alias_name)
        : (state.alias = item.alias_name);
      state.parent = item.parent_id;
    },
    async resetModal({ state, commit }) {
      state.alias = null;
      state.parent = null;
      state.remoteFiles = [];
      state.urlText = null;
      state.editedItem = {};
      state.itemType = null;

      await commit("messages", { messages: [] });
    },
    async save({ state, dispatch }) {
      if (_.isEmpty(state.editedItem)) dispatch("create");
      else dispatch("update");
    },
    async create({ state, commit, dispatch }) {
      if (state.itemType !== "file") {
        let data = await dispatch("initiateData");
        let api = require(`@/services/api/${state.apiPath}`);
        api.default
          .addItem(state.id, data)
          .then(res => {
            dispatch("checkLoading", res);
            commit("messages", { messages: [] });
          })
          .catch(res => {
            commit("messages", {
              messages: Array.isArray(res.data.message)
                ? res.data.message
                : [res.data.message]
            });
          });
      } else
        for (const fileName in state.remoteFiles) {
          let data = await dispatch("initiateData");
          data.name = state.remoteFiles[fileName].name;
          let ex = data.name.split(".")[1];
          data.alias_name =
            ex !== undefined
              ? state.remoteFiles[fileName].alias + "." + ex
              : state.remoteFiles[fileName].alias;
          let api = require(`@/services/api/${state.apiPath}`);
          api.default
            .addItem(state.id, data)
            .then(res => {
              dispatch("checkLoading", res);
              commit("messages", { messages: [] });
            })
            .catch(res => {
              commit("messages", {
                messages: Array.isArray(res.data.message)
                  ? res.data.message
                  : [res.data.message]
              });
            });
        }
    },
    //the api need update from back
    async update({ state, commit, dispatch }) {
      let data = await dispatch("initiateData");

      let api = require(`@/services/api/${state.apiPath}`);
      api.default
        .updateItem(state.id, state.editedItem.id, data)
        .then(res => {
          commit("loadItems", res);
          commit("messages", { messages: [] });
        })
        .catch(res => {
          commit("messages", {
            messages: Array.isArray(res.data.message)
              ? res.data.message
              : [res.data.message]
          });
        });
    },
    initiateData({ state }) {
      let data = {};

      if (state.parent !== null) data.parent_id = state.parent;

      if (!_.isEmpty(state.editedItem)) {
        if (state.editedItem.type == "url") data.alias_name = state.urlText;
        else data.alias_name = state.alias;
      } else {
        if (state.itemType == "url") {
          data.alias_name = state.urlText;
          data.type = "url";
        }
        if (state.itemType == "folder") {
          data.alias_name = state.alias;
          data.type = "folder";
        } else data.type = "file";
      }

      return data;
    },
    downloadFile(context, item) {
      return new promise((resolve, reject) => {
        const method = "GET";
        axios
          .request({
            url: item.url,
            method,
            responseType: "blob"
          })
          .then(response => {
            let fileURL = window.URL.createObjectURL(new Blob([response.data]));
            let fileLink = document.createElement("a");
            fileLink.href = fileURL;
            let fileExt = item.name.split(".").pop();
            let fileName = item.name.split(".")[0];
            fileLink.setAttribute("download", `${fileName}.${fileExt}`);
            document.body.appendChild(fileLink);
            resolve();

            fileLink.click();
            fileLink.remove();
          })
          .catch(() => {
            reject();
          });
      });
    }
  }
};
