<template>
  <div>
    <v-card>
      <v-card-title primary-title v-text="$t(currentMode)"> </v-card-title>
      <v-card-text class="d-flex justify-center">
        <v-row>
          <v-col
            cols="12"
            md="12"
            v-show="['addUrl', 'updateUrl'].includes(currentMode)"
          >
            <v-text-field
              class="ma-2"
              placeholder="URL"
              v-model="urlText"
              :error-messages="urlErrors"
              outlined
              dense
              @keyup.enter="addUrl"
            ></v-text-field>
          </v-col>
          <v-col
            cols="12"
            md="12"
            v-show="!['addUrl', 'updateUrl', 'addFile'].includes(currentMode)"
          >
            <div class="text-body-1 black--text mb-2" v-text="$t('name')"></div>
            <v-text-field
              class="ma-2"
              v-model="name"
              outlined
              dense
              :error-messages="nameErrors"
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="12" v-show="currentMode == 'addFile'">
            <uploader v-model="remoteFiles" />

            <div style="min-height: 2rem; width: 100%;">
              <div
                class="red--text text-body-2"
                v-for="e in remoteFilesErrors"
                :key="e"
              >
                {{ e }}
              </div>
            </div>
          </v-col>
          <v-col cols="12" md="12">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('parentFolder')"
            ></div>
            <v-select
              :items="folders"
              v-model="parent"
              item-text="name"
              item-value="id"
              outlined
              dense
              clearable
            ></v-select>
          </v-col>

          <v-col v-if="permissions.permissionAndSharings" cols="12" md="12">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('shareWith')"
            ></div>
            <v-radio-group :mandatory="true" v-model="shareStatus">
              <v-layout align-start row>
                <v-radio
                  v-for="(type, i) in shareTypes"
                  :label="type.label"
                  :value="type.value"
                  :key="i"
                ></v-radio>
              </v-layout>
            </v-radio-group>
          </v-col>
          <v-col
            cols="12"
            md="12"
            v-if="shareStatus == 'sharedWithHierarchyOrEmployee'"
          >
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('users')"
            ></div>
            <v-autocomplete
              :items="userItems"
              v-model="users"
              item-text="name"
              item-value="id"
              outlined
              dense
              clearable
              multiple
              chips
              hide-selected
              :return-object="false"
              :error-messages="usersErrors"
            ></v-autocomplete>
          </v-col>
          <v-col
            cols="12"
            md="12"
            v-if="shareStatus == 'sharedWithHierarchyOrEmployee'"
          >
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('hierarchies')"
            ></div>
            <v-autocomplete
              :items="modalHierarchiesItems"
              v-model="hierarchies"
              :item-text="$i18n == 'ar' ? 'name.ar' : 'name.en'"
              item-value="id"
              outlined
              dense
              clearable
              multiple
              chips
              hide-selected
              :return-object="false"
              :error-messages="hierarchiesErrors"
            ></v-autocomplete>
          </v-col>
          <v-col cols="12" md="12" v-if="shareStatus !== 'noOne'">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('permissions')"
            ></div>
            <v-radio-group v-model="canOrganaize" :mandatory="true">
              <v-layout align-start row>
                <v-radio :label="$t('view')" :value="0"></v-radio>
                <v-radio :label="$t('viewAndOrganaize')" :value="1"></v-radio>
              </v-layout>
            </v-radio-group>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-actions class="justify-center">
        <v-btn color="success" v-text="$t('save')" @click="save"></v-btn>
        <v-btn v-text="$t('cancel')" @click="dialog.modal = false"></v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { url, requiredIf } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import uploader from "./../../../components/uploader-with-names";
import promise from "promise";
import _ from "lodash";

export default {
  name: "modal",
  components: { uploader },

  validations: {
    urlText: {
      url,
      required: requiredIf(function() {
        return ["addUrl", "updateUrl"].includes(this.currentMode);
      })
    },
    name: {
      required: requiredIf(function() {
        return !["addUrl", "updateUrl", "addFile"].includes(this.currentMode);
      })
    },
    remoteFiles: {
      required: requiredIf(function() {
        return this.currentMode == "addFile";
      })
    },
    users: {
      required: requiredIf(function() {
        return (
          this.shareStatus == "sharedWithHierarchyOrEmplyee" &&
          this.hierarchies.length == 0
        );
      })
    },
    hierarchies: {
      required: requiredIf(function() {
        return (
          this.shareStatus == "sharedWithHierarchyOrEmplyee" &&
          this.users.length == 0
        );
      })
    }
  },
  computed: {
    ...mapFields("employeeFiles", ["dialog"]),
    ...mapFields("employeeFiles", ["shareTypes"]),
    ...mapFields("employeeFiles", ["urlText"]),
    ...mapFields("employeeFiles", ["uploadProgress"]),
    ...mapFields("employeeFiles", ["parent"]),
    ...mapFields("employeeFiles", ["folders"]),
    ...mapFields("employeeFiles", ["shareStatus"]),
    ...mapFields("employeeFiles", ["canOrganaize"]),
    ...mapFields("employeeFiles", ["users"]),
    ...mapFields("employeeFiles", ["userItems"]),
    ...mapFields("employeeFiles", ["remoteFiles"]),
    ...mapFields("employeeFiles", ["modalHierarchiesItems"]),
    ...mapFields("employeeFiles", ["hierarchies"]),
    ...mapFields("employeeFiles", ["name"]),
    ...mapFields("employeeFiles", ["permissions"]),
    ...mapFields("employeeFiles", ["editedItem"]),
    ...mapFields("employeeFiles", ["itemType"]),
    ...mapFields("employeeFiles", ["archiveloading"]),

    currentMode() {
      if (_.isEmpty(this.editedItem)) {
        if (this.itemType == "url") return "addUrl";
        if (this.itemType == "file") return "addFile";
        return "addFolder";
      } else {
        if (this.editedItem.type == "url") return "updateUrl";
        if (this.editedItem.type == "file") return "updateFile";
        return "updateFolder";
      }
    },

    urlErrors() {
      const errors = [];
      if (!this.$v.urlText.$dirty) return errors;
      !this.$v.urlText.url && errors.push(this.$t("urlNotValid"));
      return errors;
    },
    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.required && errors.push(this.$t("required"));
      return errors;
    },
    remoteFilesErrors() {
      const errors = [];
      if (!this.$v.remoteFiles.$dirty) return errors;
      !this.$v.remoteFiles.required && errors.push(this.$t("required"));
      return errors;
    },
    usersErrors() {
      const errors = [];
      if (!this.$v.users.$dirty) return errors;
      !this.$v.users.required && errors.push(this.$t("required"));
      return errors;
    },
    hierarchiesErrors() {
      const errors = [];
      if (!this.$v.hierarchies.$dirty) return errors;
      !this.$v.hierarchies.required && errors.push(this.$t("required"));
      return errors;
    }
  },

  methods: {
    save() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
        return;
      } else
        this.sendItem()
          .then(() => {
            this.dialog.modal = false;
            this.archiveloading = true;
            this.editedItem = {};
          })
          .catch(() => {})
          .finally(() => (this.archiveloading = false));
    },
    sendItem() {
      return new promise(async (resolve, reject) => {
        if (["addUrl", "updateUrl"].includes(this.currentMode))
          this.$store
            .dispatch(`employeeFiles/sendUrl`)
            .then(() => resolve())
            .catch(() => reject());
        else if (["addFile", "updateFile"].includes(this.currentMode))
          this.$store
            .dispatch(`employeeFiles/sendFiles`)
            .then(() => resolve())
            .catch(() => reject());
        else
          this.$store
            .dispatch(`employeeFiles/sendFolder`)
            .then(() => resolve())
            .catch(() => reject());
      });
    }
  },

  created() {
    this.$v.urlText.$touch();
  }
};
</script>

<style></style>
