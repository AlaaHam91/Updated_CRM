<template>
  <card
    @save="save"
    save-btn
    :saveNewBtn="false"
    :breadcrumbs="breadcrumbs"
    :hideGo="true"
    :no="0"
  >
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
    <div
      v-if="loadData"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <v-simple-table v-else>
      <thead>
        <tr>
          <th width="200px">
            <div class="text-body-1 black--text mb-2" v-text="$t('user')"></div>
            <v-select
              :items="users"
              outlined
              dense
              :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
              item-value="id"
              v-model="form.user"
              clearable
              :error-messages="userErrors"
              @change="load"
            ></v-select>
          </th>
          <th v-for="(branch, i) in branches" :key="i">
            <v-checkbox
              :label="$i18n == 'ar' ? branch.name : branch.nameL"
              v-model="branch.val"
              @change="selectColumn(i, branch.val)"
            ></v-checkbox>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, i) in form.permissions" :key="i">
          <td>
            <v-checkbox
              :label="
                $i18n == 'ar' ? departments[i].name : departments[i].nameL
              "
              v-model="departments[i].val"
              @change="selectRow(i, departments[i].val)"
            ></v-checkbox>
          </td>
          <td v-for="(element, j) in item" :key="j">
            <v-checkbox v-model="element.val"></v-checkbox>
          </td>
        </tr>
      </tbody>
    </v-simple-table>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import promise from "promise";
import api from "@/services/api/users/role-department-permission.api.js";
import userApi from "@/services/api/users/user.api.js";
import branchApi from "@/services/api/company-data/branch.api.js";
import departmentApi from "@/services/api/company-data/department.api.js";

import { cloneDeep } from "lodash";

export default {
  components: {
    card
  },
  name: "departmentes-branches-permission-show",
  data() {
    return {
      loadData: false,
      users: [],
      departments: [],
      branches: [],
      breadcrumbs: [
        {
          text: this.$t("users"),
          disabled: false
        },
        {
          text: this.$t("branchesDepartmentsRoles"),
          disabled: false
        }
      ],
      form: {
        user: null,
        permissions: []
      },
      messages: []
    };
  },
  validations: {
    form: {
      user: {
        required
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        // if (this.id == 0 && !this.$canMsg("Cp_user_departRole", "Create"))
        //   reject();
        if (!this.$canMsg("Cp_user_departRole", "Update")) reject();
        this.$v.$touch();
        if (this.$v.$invalid) {
          this.$notify({
            text: this.$t("checkInputs"),
            type: "warning"
          });
          reject();
          return;
        }

        let data = {
          employee_id: this.form.user
        };

        let j = 0;

        this.form.permissions.forEach(row => {
          let i = 0;
          let departmentSeen = false;

          row.forEach(col => {
            if (col["val"] === true) {
              data[`data[${j}][branches][${i}]`] = col.branchId;
              data[`data[${j}][department]`] = col.departmentId;
              i++;
              departmentSeen = true;
            }
          });

          if (departmentSeen) {
            j++;
          }
        });
        this.loadData = true;
        api
          .create(data)
          .then(() => resolve())
          .catch(res => {
            this.messages = Array.isArray(res.data.message)
              ? res.data.message
              : [res.data.message];
            reject();
          });
      });
    },
    save() {
      this.doSave()
        .then(() => {
          setTimeout(() => (this.loadData = false));
        })
        .catch(() => {});
    },
    saveAndNew() {
      this.doSave()
        .then(() => {
          this.resetForm();
        })
        .catch(() => {});
    },

    load() {
      this.form.permissions.forEach(row => {
        row.forEach(col => {
          col["val"] = false;
        });
      });
      if (!this.form.user) return;
      this.loadData = true;

      api
        .getEmployeeDepartmentRole(this.form.user)
        .then(result => {
          let permissions = cloneDeep(this.form.permissions);
          result.forEach(res => {
            permissions.forEach(row => {
              row.forEach(col => {
                if (col.departmentId == res.id)
                  res.branches.forEach(branch => {
                    if (col.branchId == branch.id) col["val"] = true;
                  });
              });
            });
          });
          this.form.permissions = cloneDeep(permissions);
        })
        .catch(() => {})
        .finally(() => (this.loadData = false));
    },
    loadInitial() {
      this.loadData = true;

      userApi
        .getAll()
        .then(res => {
          this.users = res;
        })
        .catch(() => {});
      departmentApi
        .getAll()
        .then(res => {
          this.departments = res;
        })
        .catch(() => {});
      branchApi
        .getAll()
        .then(res => {
          this.branches = res;
          let deps = [],
            permissions = [];

          for (let i = 0; i < this.departments.length; i++) {
            for (let j = 0; j < this.branches.length; j++) {
              deps.push({
                branchId: this.branches[j].id,
                departmentId: this.departments[i].id
              });
            }
            permissions.push(deps);
            deps = [];
          }
          this.form.permissions = cloneDeep(permissions);
        })
        .catch(() => {})
        .finally(() => (this.loadData = false));
    },
    selectRow(i, val) {
      this.form.permissions[i].forEach(col => {
        col["val"] = val;
      });
    },
    selectColumn(i, val) {
      this.form.permissions.forEach(row => {
        row.forEach((col, j) => {
          if (j == i) col["val"] = val;
        });
      });
    }
  },

  computed: {
    userErrors() {
      const errors = [];
      if (!this.$v.form.user.$dirty) return errors;
      !this.$v.form.user.required && errors.push(this.$t("required"));
      return errors;
    }
  },
  async created() {
    await this.loadInitial();
  }
};
</script>

<style></style>
