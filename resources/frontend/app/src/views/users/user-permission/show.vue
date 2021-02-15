<template>
  <card
    :title="form.id == 0 ? $t('newBtn') : $t('edit')"
    @reset-form="resetForm"
    @save="save"
    @save-and-new="saveAndNew"
    @delete-item="doDelete"
    @first="first"
    @previous="previous"
    @next="next"
    @last="last"
    :new-btn="$can('Cp_userRole', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="form.id ? $can('Cp_userRole', 'Delete') : false"
    :breadcrumbs="breadcrumbs"
    :no="form.id"
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
    <v-row>
      <v-col cols="12" md="6">
        <div class="text-body-1 black--text mb-2" v-text="$t('name')"></div>
        <v-text-field
          :placeholder="$t('name')"
          v-model="form.name"
          outlined
          :error-messages="nameErrors"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div class="text-body-1 black--text mb-2" v-text="$t('nameL')"></div>
        <v-text-field
          :placeholder="$t('nameL')"
          v-model="form.nameL"
          outlined
          :error-messages="nameLErrors"
        ></v-text-field>
      </v-col>
    </v-row>
    <div
      v-if="loading"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
    <v-simple-table v-else>
      <thead>
        <tr>
          <th v-text="$t('page')"></th>
          <th v-for="(type, i) in types" :key="i">
            <v-checkbox
              @change="selectColumn(i, type.val)"
              v-model="type.val"
              :label="type.name"
              dense
              hide-details
              :ripple="false"
            ></v-checkbox>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, i) in form.pages" :key="i">
          <td><span v-text="item.name"></span></td>
          <td>
            <v-checkbox
              v-if="item.data.includes(item.data[0])"
              v-model="item.data[0].val"
              dense
              hide-details
              :ripple="false"
            ></v-checkbox>
          </td>
          <td>
            <v-checkbox
              v-if="item.data.includes(item.data[1])"
              v-model="item.data[1].val"
              dense
              hide-details
              :ripple="false"
            ></v-checkbox>
          </td>
          <td>
            <v-checkbox
              v-if="item.data.includes(item.data[2])"
              v-model="item.data[2].val"
              dense
              hide-details
              :ripple="false"
            ></v-checkbox>
          </td>
          <td>
            <v-checkbox
              v-if="item.data.includes(item.data[3])"
              v-model="item.data[3].val"
              dense
              hide-details
              :ripple="false"
            ></v-checkbox>
          </td>
          <td>
            <v-checkbox
              v-if="item.data.includes(item.data[4])"
              v-model="item.data[4].val"
              dense
              hide-details
              :ripple="false"
            ></v-checkbox>
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
import api from "@/services/api/users/user-permission.api.js";

export default {
  components: {
    card
  },
  name: "user-permission-show",
  data() {
    return {
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("users")
        },
        {
          text: this.$t("userPermission")
        }
      ],
      form: {
        id: null,
        name: null,
        nameL: null,
        pages: []
      },
      types: [
        { name: this.$t("open") },
        { name: this.$t("preview") },
        { name: this.$t("add") },
        { name: this.$t("update") },
        { name: this.$t("delete") }
      ],
      messages: []
    };
  },
  validations: {
    form: {
      name: {
        required
      },
      nameL: {
        required
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Cp_userRole", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Cp_userRole", "Update"))
          reject();
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
          name: this.form.name,
          latin_name: this.form.nameL
        };
        let i = 0;
        this.form.pages.forEach(page => {
          page.data.forEach(group => {
            if (group.val === true) {
              data[`permissions[${i}]`] = group.id;
              i++;
            }
          });
        });
        if (this.form.id == 0)
          api
            .create(data)
            .then(() => resolve())
            .catch(res => {
              this.messages = Array.isArray(res.data.message)
                ? res.data.message
                : [res.data.message];
              reject();
            });
        else if (this.form.id > 0)
          api
            .update(this.form.id, data)
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
          this.$router.push({ name: "user-permission-index" });
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
    resetForm() {
      this.form.id = 0;
      this.form.name = null;
      this.form.nameL = null;
      this.form.permissions = [];
    },
    load() {
      this.loading = true;

      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },

    loadInitial() {
      this.loading = true;

      api
        .getAll()
        .then(res => {
          this.form.pages = res;
        })
        .catch(() => {})
        .finally(() => (this.loading = false));
    },

    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "user-permission-index" });
        })
        .catch(() => {});
    },

    selectColumn(i, val) {
      this.form.pages.forEach(page => {
        page.data[i].val = val;
      });
    },
    first() {
      api
        .first(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    previous() {
      api
        .previous(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    next() {
      api
        .next(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },
    last() {
      api
        .last(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    }
  },

  computed: {
    nameErrors() {
      const errors = [];
      if (!this.$v.form.name.$dirty) return errors;
      !this.$v.form.name.required && errors.push(this.$t("required"));

      return errors;
    },
    nameLErrors() {
      const errors = [];
      if (!this.$v.form.nameL.$dirty) return errors;
      !this.$v.form.nameL.required && errors.push(this.$t("required"));

      return errors;
    }
  },
  async created() {
    this.form.id = parseInt(this.$route.params.id);
    await this.loadInitial();
    if (this.form.id > 0) await this.load();
    else this.resetForm();
  }
};
</script>

<style></style>
