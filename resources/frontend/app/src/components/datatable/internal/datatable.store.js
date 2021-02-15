import Vue from "vue";
import _ from "lodash";

const state = () => {
  return {
    headers: [],
    items: [],
    errors: [],
    selectedRows: [],
    canAdd: false,
    canDelete: false,
    dirty: false,
    hasErrors: false,
    validatePatterns: {
      email: /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
    }
  };
};

const getters = {
  headers(state) {
    return state.headers;
  },
  items(state) {
    return state.items;
  },
  cell: state => data => {
    let item = state.items.find(e => e.id == data.itemId);
    let headerVal = state.headers.find(e => e.id == data.headerId).value;
    return item[headerVal];
  },
  dedicatedOptions: state => data => {
    let item = state.items.find(e => e.id == data.id);
    return item[data.options];
  },
  isSelected: state => itemId => {
    return state.selectedRows.find(e => e == itemId) >= 0;
  },
  selectedRows(state) {
    return state.selectedRows;
  },
  canAdd(state) {
    return state.canAdd;
  },
  canDelete(state) {
    return state.canDelete;
  },
  dirty(state) {
    return state.dirty;
  },
  errors(state) {
    return state.errors;
  },
  hasErrors(state) {
    return state.hasErrors;
  }
};

const mutations = {
  headers(state, data) {
    let headers = _.cloneDeep(data.headers);

    state.headers = headers.map((header, index) => ({
      id: index + 1,
      ...header
    }));
  },
  cell(state, data) {
    let i = state.items.findIndex(x => x.id == data.itemId);
    let j = state.headers.find(e => e.id == data.headerId).value;

    state.items[i][j] =
      data.type === "number" ? Number(data.value) : data.value;
    if (state.items[i].status !== "Add") state.items[i].status = "Update";
  },
  toggleColumns(state, data) {
    let cols = state.headers.slice(0);
    cols.forEach(col => {
      if (!data.headers.includes(col.id)) col.visible = false;
      else col.visible = true;
    });
    state.headers = cols.slice(0);
  },
  selectedRows(state, data) {
    state.selectedRows = _.cloneDeep(data.ids);
  },
  deleteRows(state) {
    for (let i = 0; i < state.selectedRows.length; i++) {
      let j = state.items.findIndex(e => e.id === state.selectedRows[i]);
      if (j > -1) {
        if (state.items[j].status === "Add") state.items.splice(j, 1);
        else state.items[j].status = "Delete";
      }
    }
    state.selectedRows = [];
  },
  canAdd(state, val) {
    state.canAdd = val;
  },
  canDelete(state, val) {
    state.canDelete = val;
  },
  touchValidation(state) {
    state.dirty = true;
  },
  setError(state, data) {
    let index = state.errors.findIndex(e => e.id === data.id);
    state.errors[index][data.key] = { hasError: data.hasError, msg: data.msg };
  },
  hasErrors(state, data) {
    state.hasErrors = data;
  },
  dirty(state, data) {
    state.dirty = data;
  },
  addItem(state, item) {
    item["status"] = "Add";
    state.items.push(_.cloneDeep(item));
  },
  editItem(state, data) {
    Object.assign(state.items[data.index], { ...data.item });
  },
  deleteItem(state, index) {
    if (state.items[index].status === "Add") state.items.splice(index, 1);
    else state.items[index].status = "Delete";
  },
  constructValidation(state, errors) {
    state.errors = errors;
  },
  clearData(state) {
    state.items = [];
  },
  dedicatedOptions(state, data) {
    Vue.set(state.items[data.index], data.optionsName, data.options);
    // state.items[data.index][data.optionsName] = data.options;
  },
  autoInc(state, header) {
    for (let i = 0; i < state.items.length; i++) state.items[i][header] = i + 1;
  }
};

const actions = {
  items({ state, dispatch }, data) {
    //add id, isSelected
    let items = data.items.map((item, index) => ({
      id: index + 1,
      isSelected: false,
      status: "None",
      ...item
    }));
    state.items = _.cloneDeep(items);
    dispatch("constructValidation");
  },
  constructValidation({ state, commit }) {
    //construct errors array
    let errors = [];
    state.items.forEach(item => {
      let row = {};
      for (const key of Object.keys(item)) {
        if (key === "id") row[key] = item[key];
        else if (key === "isSelected") continue;
        else row[key] = { hasError: false, msg: null };
      }
      errors.push(row);
    });
    commit("constructValidation", errors);
  },
  getLastId({ state }) {
    let id = 0;
    state.items.forEach(element => {
      if (element.id > id) id = element.id;
    });

    return id;
  },
  async addEmptyItem({ state, commit, dispatch }) {
    let id = await dispatch("getLastId");
    let newItem = {};
    newItem["id"] = ++id;

    state.headers.forEach(element => {
      if (element.type == "checkbox") newItem[element.value] = false;
      else newItem[element.value] = null;
    });

    commit("dirty", false);
    commit("hasErrors", false);
    commit("addItem", newItem);
    dispatch("constructValidation");
  },
  async addItem({ commit, state, dispatch }, item) {
    let result = true;
    state.headers.forEach(element => {
      if (!Object.prototype.hasOwnProperty.call(item, element.value)) {
        console.log(
          "%c Error in data-table addItem: Please add all properties to the item",
          "background: Brown;"
        );
        result = false;
      }
    });
    if (!result) return;

    let id = await dispatch("getLastId");

    let newItem = _.cloneDeep(item);
    newItem["id"] = ++id;
    newItem["isSelected"] = false;
    newItem["status"] = "Add";

    commit("dirty", false);
    commit("hasErrors", false);
    commit("addItem", newItem);
    dispatch("constructValidation");
  },
  editItem({ state, commit, dispatch }, data) {
    let i = state.items.findIndex(e => e.id === data.id);
    if (i > -1) {
      commit("dirty", false);
      commit("hasErrors", false);
      commit("editItem", { index: i, item: _.cloneDeep(data) });
      dispatch("constructValidation");
    }
  },
  deleteItem({ state, commit, dispatch }, id) {
    let i = state.items.findIndex(e => e.id === id);

    if (i > -1) {
      commit("dirty", false);
      commit("hasErrors", false);
      commit("deleteItem", i);
      dispatch("constructValidation");
    }
  },
  checkUnique({ state, commit }) {
    let result = true;
    state.headers.forEach(header => {
      if (!Object.prototype.hasOwnProperty.call(header, "unique")) return;
      if (header.unique !== true) return;
      //get column values
      let arr = [];
      //  state.items.filter(item => item[header.value] !== null);
      state.items.forEach(item => {
        if (item[header.value]) arr.push(item[header.value]);
      });
      if (arr.length == 0) return;

      //check for uniqueness
      if (new Set(arr).size !== arr.length) {
        //column have non-unique values!

        state.errors.forEach(row => {
          row[header.value].hasError = true;
          row[header.value].msg = header.uniqueMessage;
        });
        result = false;
      }
    });
    if (!result) {
      commit("hasErrors", result);
    }
  },
  async doValidate({ commit, dispatch, state }) {
    //clear errors array
    dispatch("constructValidation");

    //iterate through state.items to check against header rules
    let result = false;
    state.items.forEach(item => {
      for (const key of Object.keys(item)) {
        if (["id", "isSelected"].includes(key)) continue;
        let val = item[key];

        let header = state.headers.find(e => e.value === key);
        if (!header) continue;
        if (!Object.prototype.hasOwnProperty.call(header, "rules")) continue;

        let valValidate = { id: item.id, key: key, hasError: false, msg: null };
        header["rules"].forEach(rule => {
          switch (rule.type) {
            case "required":
              if (val == null) {
                valValidate.hasError = true;
                valValidate.msg = rule.message;
              }
              break;

            case "email":
              if (val && !state.validatePatterns.email.test(val)) {
                valValidate.hasError = true;
                valValidate.msg = rule.message;
              }
              break;

            case "regex":
              if (val && !val.match(new RegExp(rule.value, "gi"))) {
                valValidate.hasError = true;
                valValidate.msg = rule.message;
              }
              break;

            case "requiredEither":
              if (!Object.prototype.hasOwnProperty.call(item, rule.other))
                console.log(
                  `%c Error in data-table validate rule: header ${rule.other} wasn't found`,
                  "background: Brown;"
                );
              else if (
                (!val || val == 0) &&
                (!item[`${rule.other}`] || item[`${rule.other}`] == 0)
              ) {
                valValidate.hasError = true;
                valValidate.msg = rule.message;
              }
              break;

            case "requiredIf":
              if (item[`${rule.other}`] && !val) {
                valValidate.hasError = true;
                valValidate.msg = rule.message;
              }
              break;

            default:
              console.log(
                `%c Error in data-table validation: unknown rule - ${rule.type}`,
                "background: Brown;"
              );
              break;
          }
        });

        if (valValidate.hasError) {
          commit("setError", valValidate);
          result = true;
        }
      }
    });
    await dispatch("checkUnique");
    commit("hasErrors", result);
  },
  async resetValidation({ state, dispatch }) {
    state.dirty = false;
    state.hasErrors = false;
    await dispatch("constructValidation");
  },
  async clearData({ commit, dispatch }) {
    await dispatch("resetValidation");
    commit("clearData");
  }
};

export default {
  state,
  getters,
  mutations,
  actions
};
