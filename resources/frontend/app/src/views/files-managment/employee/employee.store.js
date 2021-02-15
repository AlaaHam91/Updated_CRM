import { getField, updateField } from "vuex-map-fields";
import promise from "promise";
import i18n from "@/plugins/i18n";
import api from "@/services/api/files-managment/employee.api";
import axios from "@/plugins/api";
import { isEmpty } from "lodash";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("controlPanel")
      },
      {
        text: i18n.t("employeeFiles")
      }
    ],
    displaybreadcrumbs: [{ text: i18n.t("myFiles"), val: "myFiles" }],
    shareTypes: [
      { label: i18n.t("noOne"), value: "noOne" },
      { label: i18n.t("Public"), value: "sharedWithPublic" },
      {
        label: i18n.t("PublicRegistered"),
        value: "sharedWithAllUsers"
      },
      {
        label: i18n.t("specificUsers"),
        value: "sharedWithHierarchyOrEmployee"
      }
    ],
    items: {
      myFiles: [],
      sharedWithFiles: []
    },
    permissions: {},
    //dialog
    dialog: { modal: false },
    urlText: null,
    name: null,
    parent: null,
    shareStatus: null,
    canOrganaize: null,
    users: [],
    hierarchies: [],
    remoteFiles: [],
    modalHierarchiesItems: [],
    folders: [],
    userItems: [],
    editedItem: {},
    //
    archiveloading: false,
    modalLoading: false,
    treeLoading: false,
    itemType: null,
    //for the tree
    hierarchyItems: [],
    hierarchy: [],
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
    loadData(state, res) {
      state.folders = res.folders;
      state.modalHierarchiesItems = res.hierarchies;
      state.userItems = res.users;
      state.permissions = res.permissions;
    },
    loadItems(state, res) {
      state.items.myFiles = res.myFiles.filter(
        item => item.viewPermission == true
      );
      state.items.sharedWithFiles = res.sharedWithFiles.filter(
        item => item.viewPermission == true
      );
      if (state.displaybreadcrumbs[0].val === "sharedWithMe")
        state.displaybreadcrumbs.length = 1;
    },
    //for the step
    loadCurrentItems(state, res) {
      let firstBreadcrumb = state.displaybreadcrumbs[0];
      if (firstBreadcrumb.val === "myFiles") state.items.myFiles = res;
      else state.items.sharedWithFiles = res;
    }
  },
  actions: {
    //get tree
    loadInitial({ state }) {
      state.treeLoading = true;
      api
        .getTree()
        .then(res => {
          state.hierarchyItems = res;
        })
        .finally(() => (state.treeLoading = false));
    },
    //load hierarchy data [folders,users,items]
    load({ state, commit }) {
      let data = {};
      //user files
      if (state.hierarchy.length == 0) data.hierarchy_type = null;
      //from the tree
      else {
        let leaf = state.hierarchy[state.hierarchy.length - 1];
        data.hierarchy_type = leaf.type;
        data.hierarchy_id = leaf.nodeId;
      }
      state.archiveloading = true;

      api
        .get(data)
        .then(res => {
          commit("loadData", res);
          commit("loadItems", res);
        })
        .finally(() => {
          this.displaybreadcrumbs.length = 1;
          state.archiveloading = false;
        });
    },
    checkLoading({ state, commit }, res) {
      commit("loadData", res);
      let lastBreadcrumb =
        state.displaybreadcrumbs[state.displaybreadcrumbs.length - 1];
      //check where user add with last b
      if (state.parent === null) {
        if (state.displaybreadcrumbs.length > 1) return;
      } else {
        let parent = state.folders.find(folder => folder.id === state.parent);
        if (parent.id !== lastBreadcrumb.id) return;
      }

      commit("loadItems", res);
    },
    //delete selected remote item
    delete({ commit }, item) {
      return new promise(async (resolve, reject) => {
        let data = { data_id: item.id };
        api
          .delete(data)
          .then(res => {
            commit("loadData", res);
            commit("loadItems", res);
          })
          .catch(() => reject());
      });
    },
    step({ state, commit }, payload) {
      let data = { folder_id: payload };
      state.archiveloading = true;
      api
        .getFolderData(data)
        .then(res => commit("loadCurrentItems", res))
        .finally(() => {
          state.archiveloading = false;
        });
    },
    goToRoot({ state, commit }) {
      state.archiveloading = true;
      api
        .getRoot()
        .then(res => commit("loadCurrentItems", res))
        .finally(() => {
          state.archiveloading = false;
        });
    },
    async editModal({ state }, item) {
      state.editedItem = item;
      item.type == "url"
        ? (state.urlText = item.name)
        : (state.name = item.name);
      state.parent = item.parent_id;
      state.shareStatus = item.share_status;
      state.canOrganaize = item.can_organize;
      state.users = item.users.map(u => {
        return u.id;
      });
      state.hierarchies = item.hierarchies.map(h => {
        return h.id;
      });
      state.folders = item.folders;
    },
    async resetModal({ state, commit }) {
      state.name = null;
      state.parent = null;
      state.shareStatus = null;
      state.canOrganaize = null;
      state.users = [];
      state.hierarchies = [];
      state.remoteFiles = [];
      state.urlText = null;
      state.editedItem = {};
      state.itemType = null;

      await commit("messages", { messages: [] });
    },
    async sendUrl({ commit, dispatch }) {
      let data = await dispatch("initiateData");
      api
        .sendUrl(data)
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
    },
    async sendFolder({ commit, dispatch }) {
      let data = await dispatch("initiateData");
      api
        .sendFolder(data)
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
    },
    async sendFiles({ state, commit, dispatch }) {
      if (isEmpty(state.editedItem)) {
        let iterations = state.remoteFiles.length;

        for (const fileName in state.remoteFiles) {
          let data = await dispatch("initiateData");
          //in adding mode we must sent the attachment names
          data.attachment = state.remoteFiles[fileName].name;
          let ex = data.attachment.split(".")[1];
          data.file_name =
            ex !== undefined
              ? state.remoteFiles[fileName].alias + "." + ex
              : state.remoteFiles[fileName].alias;
          api
            .sendFile(data)
            .then(res => {
              commit("messages", { messages: [] });
              if (!--iterations) dispatch("checkLoading", res);
            })
            .catch(res => {
              commit("messages", {
                messages: Array.isArray(res.data.message)
                  ? res.data.message
                  : [res.data.message]
              });
            });
        }
      } else {
        let data = await dispatch("initiateData");

        api
          .sendFile(data)
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
    initiateData({ state }) {
      let users = {},
        hierarchies = {};

      let data = {
        share_status: state.shareStatus,
        can_organize: state.canOrganaize
      };

      for (let i = 0; i < state.users.length; i++) {
        users[i] = { id: state.users[i].toString() };
      }

      for (let j = 0; j < state.hierarchies.length; j++) {
        hierarchies[j] = { id: state.hierarchies[j].toString() };
      }
      if (!isEmpty(users)) data.users = JSON.stringify(users);
      else data.users = JSON.stringify([]);
      if (!isEmpty(hierarchies)) data.hierarchies = JSON.stringify(hierarchies);
      else data.hierarchies = JSON.stringify([]);

      if (state.parent !== undefined) data.parent_id = state.parent;

      if (state.hierarchy.length > 0) {
        let leaf = state.hierarchy[state.hierarchy.length - 1];
        data.hierarchy_type = leaf.type;
        data.hierarchy_id = leaf.nodeId;
      }

      if (!isEmpty(state.editedItem)) {
        if (state.editedItem.type == "url") data.url_name = state.urlText;
        if (state.editedItem.type == "folder") data.folder_name = state.name;
      } else {
        if (state.itemType == "url") data.url_name = state.urlText;
        if (state.itemType == "folder") data.folder_name = state.name;
      }
      //update case
      if (!isEmpty(state.editedItem)) {
        if (state.editedItem.type == "url") data.url_id = state.editedItem.id;
        if (state.editedItem.type == "file") data.file_id = state.editedItem.id;
        if (state.editedItem.type == "folder")
          data.folder_id = state.editedItem.id;
      }
      return data;
    },
    downloadFile(context, url) {
      return new promise((resolve, reject) => {
        const method = "GET";
        axios
          .request({
            url: url,
            method,
            responseType: "blob"
          })
          .then(response => {
            let fileURL = window.URL.createObjectURL(new Blob([response.data]));
            let fileLink = document.createElement("a");
            fileLink.href = fileURL;
            let fileExt = response.headers["content-disposition"]
              .split(".")
              .pop();
            fileLink.setAttribute("download", `file.${fileExt}`);
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
