import { getField, updateField } from "vuex-map-fields";
import i18n from "./../../../plugins/i18n";
import promise from "promise";

// APIs
import api from "@/services/api/users/user.api";
import permissionApi from "@/services/api/users/role-department-permission.api.js";
import managerialLevelApi from "@/services/api/users/managerial-level.api.js";
import branchApi from "@/services/api/company-data/branch.api.js";
import managementApi from "@/services/api/company-data/management.api.js";
import departmentApi from "@/services/api/company-data/department.api.js";
import jobApi from "@/services/api/users/job.api.js";
import specializationApi from "@/services/api/control-panel/specialization.api.js";
import hierarchyApi from "@/services/api/company-data/hierarchy.api.js";

export default {
  namespaced: true,
  state: {
    breadcrumbs: [
      {
        text: i18n.t("users"),
        disabled: false
      },
      {
        text: i18n.t("user"),
        disabled: false
      }
    ],
    headers: [
      {
        text: i18n.t("name"),
        value: "name.ar",
        width: "200px",
        type: "string",
        readonly: true,
        visible: true,
        sortable: true,
        divider: true
      },
      {
        text: i18n.t("nameL"),
        value: "name.en",
        width: "200px",
        type: "string",
        readonly: true,
        visible: true,
        sortable: true,
        divider: true
      }
    ],
    tab: null,
    panel: [],
    permissionsPanel: [],
    messages: [],
    managerialLevels: [],
    branches: [],
    departments: [],
    jobs: [],
    managers: [],
    managements: [],
    specializationItems: [],
    hierarchies: [],
    //permissions
    departmentRoles: [],
    branchRoles: [],
    // modules
    mobileModule: null,
    emailModule: null,
    phoneModule: null,
    faxModule: null,
    addressModule: null,
    noteModule: null,
    locationModule: null,
    // form
    id: null,
    userId: null,
    image: null,
    name: null,
    nameL: null,
    isActive: false,
    accountNumber: null,
    idNumber: null,
    management: null,
    branch: null,
    department: null,
    manager: null,
    job: null,
    jobTitle: null,
    ticketsEmail: null,
    notes: null,
    hierarchy: [],
    userName: null,
    password: null,
    email: null,
    specializations: [], //check
    managerialLevel: null,
    phoneItems: [],
    faxItems: [],
    mobileItems: [],
    emailItems: [],
    addressItems: [],
    noteItems: [],
    locationItems: [],
    websiteItems: []
  },
  getters: {
    getField
  },
  mutations: {
    updateField,

    messages(state, data) {
      state.messages = data.messages;
    },
    managerialLevelItems(state, data) {
      state.managerialLevels = data;
    },
    branchItems(state, data) {
      state.branches = data;
    },
    departmentItems(state, data) {
      state.departments = data;
    },
    jobItems(state, data) {
      state.jobs = data;
    },
    managerItems(state, data) {
      state.managers = data;
    },
    departmentRoles(state, data) {
      state.departmentRoles = data;
    },
    branchRoles(state, data) {
      state.branchRoles = data;
    },
    managmentItems(state, data) {
      state.managements = data;
    },

    specializationItems(state, data) {
      state.specializationItems = data;
    },
    hierarchies(state, data) {
      state.hierarchies = data;
    }
  },

  actions: {
    async loadItem({ state, dispatch }, data) {
      await dispatch("reset");
      state.id = data.id;
      state.userId = data.id;
      state.image = data.image;
      state.name = data.name;
      state.nameL = data.latin_name;
      state.isActive = data.is_active;
      state.accountNumber = data.account_number;
      state.idNumber = data.id_number;
      state.management = data.management_id;
      state.branch = data.branch_id;
      state.department = data.department_id;
      state.manager = data.manager_id;
      state.job = data.job_id;
      state.jobTitle = data.job_title;
      state.ticketsEmail = data.tickets_email;
      state.notes = data.notes;
      state.hierarchy.push(data.hierarchy_id); //////check
      state.userName = data.user_name;
      state.password = "#@!2";
      state.email = data.email;
      state.specializations = data.specializations;
      state.managerialLevel = data.managerial_level;
      await data.contact_infos.forEach(item => {
        switch (item.type) {
          case "Address":
            state.addressItems.push({
              cm_address: item.cm_address,
              a_d: item.a_d,
              address: item.address_value,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Mobile":
            state.mobileItems.push({
              cm_mobile: item.cm_mobile,
              country: item.country,
              mobile: item.value,
              socialMedia: item.social_media,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Phone":
            state.phoneItems.push({
              cm_phone: item.cm_phone,
              a_d: item.a_d,
              country: item.country,
              phone: item.value,
              extension: item.transfer_no,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Fax":
            state.faxItems.push({
              cm_fax: item.cm_fax,
              a_d: item.a_d,
              country: item.country,
              fax: item.value,
              extension: item.transfer_no,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Email":
            state.emailItems.push({
              cm_email: item.cm_email,
              email: item.value,
              socialMedia: item.social_media,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Position":
            state.locationItems.push({
              cm_position: item.cm_position,
              location: {
                lat: item.gps_location_x,
                lng: item.gps_location_y
              },
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Note":
            state.noteItems.push({
              cm_note: item.cm_note,
              note: item.note_value,
              notice: item.declaration,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          case "Website":
            state.websiteItems.push({
              cm_website: item.cm_website,
              website: item.website_value,
              contact_info: item.contact_info,
              status: "none"
            });
            break;
          default:
            break;
        }
      });
      await dispatch("loadUserPermissions");
    },
    async reset({ state, commit, dispatch }) {
      state.id = 0;
      state.image = null;
      state.name = null;
      state.nameL = null;
      state.isActive = null;
      state.accountNumber = null;
      state.idNumber = null;
      state.management = null;
      state.branch = null;
      state.department = null;
      state.manager = null;
      state.job = null;
      state.jobTitle = null;
      state.ticketsEmail = null;
      state.notes = null;
      state.hierarchy = [];
      state.userName = null;
      state.password = null;
      state.email = null;
      state.specializations = []; //check
      state.managerialLevel = null;
      state.phoneItems = [];
      state.faxItems = [];
      state.mobileItems = [];
      state.emailItems = [];
      state.addressItems = [];
      state.noteItems = [];
      state.locationItems = [];

      await commit("messages", { messages: [] });

      //reset data tables
      if (state.phoneModule) {
        await dispatch(`${state.phoneModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.phoneModule}/clearData`, null, { root: true });
      }
      if (state.faxModule) {
        await dispatch(`${state.faxModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.faxModule}/clearData`, null, { root: true });
      }
      if (state.mobileModule) {
        await dispatch(`${state.mobileModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.mobileModule}/clearData`, null, { root: true });
      }
      if (state.emailModule) {
        await dispatch(`${state.emailModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.emailModule}/clearData`, null, { root: true });
      }
      if (state.addressModule) {
        await dispatch(`${state.addressModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.addressModule}/clearData`, null, {
          root: true
        });
      }
      if (state.noteModule) {
        await dispatch(`${state.noteModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.noteModule}/clearData`, null, { root: true });
      }
      if (state.locationModule) {
        await dispatch(`${state.locationModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.locationModule}/clearData`, null, {
          root: true
        });
      }
      if (state.websiteModule) {
        await dispatch(`${state.websiteModule}/resetValidation`, null, {
          root: true
        });
        await dispatch(`${state.websiteModule}/clearData`, null, {
          root: true
        });
      }
    },
    touchDatatables({ state, commit }) {
      if (state.phoneModule)
        commit(`${state.phoneModule}/touchValidation`, null, { root: true });

      if (state.faxModule)
        commit(`${state.faxModule}/touchValidation`, null, { root: true });

      if (state.mobileModule)
        commit(`${state.mobileModule}/touchValidation`, null, { root: true });

      if (state.emailModule)
        commit(`${state.emailModule}/touchValidation`, null, { root: true });

      if (state.addressModule)
        commit(`${state.addressModule}/touchValidation`, null, {
          root: true
        });

      if (state.noteModule)
        commit(`${state.noteModule}/touchValidation`, null, { root: true });

      if (state.locationModule)
        commit(`${state.locationModule}/touchValidation`, null, { root: true });
      if (state.websiteModule)
        commit(`${state.websiteModule}/touchValidation`, null, { root: true });
    },
    loadTheInitial({ commit }) {
      jobApi
        .getAll()
        .then(res => {
          commit("jobItems", res);
        })
        .catch(() => {});
      managerialLevelApi
        .getAll()
        .then(res => {
          commit("managerialLevelItems", res);
        })
        .catch(() => {});
      branchApi
        .getAll()
        .then(res => {
          commit("branchItems", res);
        })
        .catch(() => {});
      departmentApi
        .getAll()
        .then(res => {
          commit("departmentItems", res);
        })
        .catch(() => {});
      api
        .getAll()
        .then(res => {
          commit("managerItems", res);
        })
        .catch(() => {});
      managementApi
        .getAll()
        .then(res => {
          commit("managmentItems", res);
        })
        .catch(() => {});

      specializationApi
        .getAll()
        .then(res => {
          commit("specializationItems", res);
        })
        .catch(() => {});

      hierarchyApi
        .getTree()
        .then(res => {
          commit("hierarchies", res);
        })
        .catch(() => {});
    },
    loadUserPermissions({ state, commit }) {
      permissionApi
        .getEmployeeDepartmentRole(state.id)
        .then(res => {
          commit("departmentRoles", res);
        })
        .catch(() => {});
      permissionApi
        .getEmployeeBranchRole(state.id)
        .then(res => {
          commit("branchRoles", res);
        })
        .catch(() => {});
    },
    loadFromModules({ state, rootGetters }) {
      // populte data-table items from store modules

      if (state.phoneModule)
        state.phoneItems = rootGetters[`${state.phoneModule}/items`];

      if (state.faxModule)
        state.faxItems = rootGetters[`${state.faxModule}/items`];

      if (state.mobileModule)
        state.mobileItems = rootGetters[`${state.mobileModule}/items`];

      if (state.emailModule)
        state.emailItems = rootGetters[`${state.emailModule}/items`];

      if (state.addressModule)
        state.addressItems = rootGetters[`${state.addressModule}/items`];

      if (state.noteModule)
        state.noteItems = rootGetters[`${state.noteModule}/items`];

      if (state.locationModule)
        state.locationItems = rootGetters[`${state.locationModule}/items`];

      if (state.websiteModule)
        state.websiteItems = rootGetters[`${state.websiteModule}/items`];
    },
    async initiateData({ state, dispatch }) {
      //load data from addresses, phones, mobiles, notes, ... modules
      await dispatch("loadFromModules");

      let data = {
        is_active: state.isActive == true ? 1 : 0,
        name: state.name,
        latin_name: state.nameL,
        account_number: state.accountNumber,
        id_number: state.idNumber,
        management_id: state.management,
        branch_id: state.branch,
        department_id: state.department,
        manager_id: state.manager,
        job_id: state.job,
        job_title: state.jobTitle,
        tickets_email: state.ticketsEmail,
        notes: state.notes,
        hierarchy_id: state.hierarchy[0],
        user_name: state.userName,
        email: state.email,
        managerial_level_id: state.managerialLevel, //
        password: state.password
        //  specialization_id: state.specializations //check
      };
      for (let j = 0; j < state.specializations.length; j++)
        data[`specialization_id[${j}]`] = state.specializations[j];

      let i = 0;

      for (let j = 0; j < state.addressItems.length; j++) {
        if (state.addressItems[j].status == "none") continue;

        if (state.addressItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.addressItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.addressItems[j].status;
        data[`contact_infos[${i}][type]`] = "Address";
        data[`contact_infos[${i}][a_d]`] = state.addressItems[j].a_d;
        data[`contact_infos[${i}][cm_address]`] =
          state.addressItems[j].cm_address;
        data[`contact_infos[${i}][address_value]`] =
          state.addressItems[j].address;
        i++;
      }

      for (let j = 0; j < state.mobileItems.length; j++) {
        if (state.mobileItems[j].status == "none") continue;

        if (state.mobileItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.mobileItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.mobileItems[j].status;
        data[`contact_infos[${i}][type]`] = "Mobile";
        data[`contact_infos[${i}][country]`] = state.mobileItems[j].country;
        data[`contact_infos[${i}][cm_mobile]`] = state.mobileItems[j].cm_mobile;
        data[`contact_infos[${i}][value]`] = state.mobileItems[j].mobile;
        if (state.mobileItems.socialMedia)
          for (let k = 0; k < state.mobileItems.socialMedia.length; k++)
            data[`contact_infos[${i}][social_media][${k}]`] =
              state.mobileItems.socialMedia[k];
        i++;
      }

      for (let j = 0; j < state.phoneItems.length; j++) {
        if (state.phoneItems[j].status == "none") continue;

        if (state.phoneItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.phoneItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.phoneItems[j].status;
        data[`contact_infos[${i}][type]`] = "Phone";
        data[`contact_infos[${i}][a_d]`] = state.phoneItems[j].a_d;
        data[`contact_infos[${i}][cm_phone]`] = state.phoneItems[j].cm_phone;
        data[`contact_infos[${i}][value]`] = state.phoneItems[j].phone;
        data[`contact_infos[${i}][transfer_no]`] =
          state.phoneItems[j].extension;
        i++;
      }

      for (let j = 0; j < state.faxItems.length; j++) {
        if (state.faxItems[j].status == "none") continue;

        if (state.faxItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.faxItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.faxItems[j].status;
        data[`contact_infos[${i}][type]`] = "Fax";
        data[`contact_infos[${i}][a_d]`] = state.faxItems[j].a_d;
        data[`contact_infos[${i}][cm_fax]`] = state.faxItems[j].cm_fax;
        data[`contact_infos[${i}][value]`] = state.faxItems[j].fax;
        i++;
      }

      for (let j = 0; j < state.emailItems.length; j++) {
        if (state.emailItems[j].status == "none") continue;

        if (state.emailItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.emailItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.emailItems[j].status;
        data[`contact_infos[${i}][type]`] = "Email";
        data[`contact_infos[${i}][cm_email]`] = state.emailItems[j].cm_email;
        data[`contact_infos[${i}][value]`] = state.emailItems[j].email;
        if (state.emailItems.socialMedia)
          for (let k = 0; k < state.emailItems.socialMedia.length; k++)
            data[`contact_infos[${i}][social_media][${k}]`] =
              state.emailItems.socialMedia[k];
        i++;
      }

      for (let j = 0; j < state.locationItems.length; j++) {
        if (state.locationItems[j].status == "none") continue;

        if (state.locationItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.locationItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.locationItems[j].status;
        data[`contact_infos[${i}][type]`] = "Position";
        data[`contact_infos[${i}][cm_position]`] =
          state.locationItems[j].cm_position;
        data[`contact_infos[${i}][gps_location_x]`] =
          state.locationItems[j].location.lat;
        data[`contact_infos[${i}][gps_location_y]`] =
          state.locationItems[j].location.lng;
        i++;
      }

      for (let j = 0; j < state.noteItems.length; j++) {
        if (state.noteItems[j].status == "none") continue;

        if (state.noteItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.noteItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.noteItems[j].status;
        data[`contact_infos[${i}][type]`] = "Note";
        data[`contact_infos[${i}][cm_note]`] = state.noteItems[j].cm_note;
        data[`contact_infos[${i}][note_value]`] = state.noteItems[j].note;
        data[`contact_infos[${i}][declaration]`] = state.noteItems[j].notice;
        i++;
      }

      for (let j = 0; j < state.websiteItems.length; j++) {
        if (state.websiteItems[j].status == "none") continue;

        if (state.websiteItems[j].status !== "Add")
          data[`contact_infos[${i}][contact_info]`] =
            state.websiteItems[j].contact_info;
        data[`contact_infos[${i}][status]`] = state.websiteItems[j].status;
        data[`contact_infos[${i}][type]`] = "Website";
        data[`contact_infos[${i}][cm_website]`] =
          state.websiteItems[j].cm_website;
        data[`contact_infos[${i}][website_value]`] =
          state.websiteItems[j].website;
        i++;
      }
      return data;
    },
    async create({ commit, dispatch }) {
      let data = await dispatch("initiateData");

      await api
        .create(data)
        .then(() => {
          commit("messages", { messages: [] });
          //console.log(res.data.content);

          return true;
        })
        .catch(err => {
          commit("messages", { messages: err.data.message });
          return false;
        });
    },
    async save({ state, commit, dispatch }) {
      return new promise(async (resolve, reject) => {
        let data = await dispatch("initiateData");

        if (state.id == 0)
          api
            .create(data)
            .then(() => {
              commit("messages", { messages: [] });
              resolve();
            })
            .catch(res => {
              commit("messages", {
                messages: Array.isArray(res.data.message)
                  ? res.data.message
                  : [res.data.message]
              });
              reject();
            });
        else if (state.id > 0)
          api
            .update(state.id, data)
            .then(() => {
              commit("messages", { messages: [] });
              resolve();
            })
            .catch(res => {
              commit("messages", {
                messages: Array.isArray(res.data.message)
                  ? res.data.message
                  : [res.data.message]
              });
              reject();
            });
      });
    },
    delete({ state }) {
      return new promise(async (resolve, reject) => {
        api
          .delete(state.id)
          .then(() => true)
          .catch(() => reject());
      });
    },
    load({ dispatch, state }) {
      api
        .get(state.id)
        .then(res => dispatch("loadItem", res))
        .catch(() => {});
    },
    first({ state, dispatch }) {
      api
        .first(state.id)
        .then(res => dispatch("loadItem", res))
        .catch(() => {});
    },
    previous({ state, dispatch }) {
      api
        .previous(state.id)
        .then(res => dispatch("loadItem", res))
        .catch(() => {});
    },
    next({ state, dispatch }) {
      api
        .next(state.id)
        .then(res => dispatch("loadItem", res))
        .catch(() => {});
    },
    last({ state, dispatch }) {
      api
        .last(state.id)
        .then(res => dispatch("loadItem", res))
        .catch(() => {});
    },
    storeImage({ state }) {
      api
        .image(state.image, state.id)
        .then(() => true)
        .catch(() => {});
    }
  }
};
