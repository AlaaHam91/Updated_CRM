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
    :new-btn="$can('Pg_social_media', 'Create')"
    save-btn
    save-new-btn
    :delete-btn="form.id ? $can('Pg_social_media', 'Delete') : false"
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
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('name')"
        ></div>
        <v-text-field
          outlined
          v-model="form.name"
          autocomplete="off"
          :error-messages="nameErrors"
        ></v-text-field>
      </v-col>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('nameL')"
        ></div>
        <v-text-field
          outlined
          v-model="form.nameL"
          autocomplete="off"
          :error-messages="nameLErrors"
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12" md="6">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('notes')"
        ></div>
        <v-text-field
          outlined
          v-model="form.note"
          autocomplete="off"
          :error-messages="noteErrors"
        ></v-text-field>
      </v-col>
    </v-row>
  </card>
</template>

<script>
import card from "./../../../components/card";
import { required } from "vuelidate/lib/validators";
import api from "@/services/api/people-settings/social-media.api.js";
import promise from "promise";

export default {
  name: "social-link-show",
  components: { card },
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("indexSettings"),
          disabled: false
        },
        {
          text: this.$t("socialLink"),
          disabled: false
        }
      ],
      form: {
        id: null,
        name: null,
        nameL: null,
        note: null
      },
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
      },
      note: {
        required
      }
    }
  },

  methods: {
    doSave() {
      return new promise((resolve, reject) => {
        if (this.form.id == 0 && !this.$canMsg("Pg_social_media", "Create"))
          reject();
        if (this.form.id > 0 && !this.$canMsg("Pg_social_media", "Update"))
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
          latin_name: this.form.nameL,
          notes: this.form.note
        };
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
          this.$router.push({ name: "social-link-index" });
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
      this.form.note = null;
    },
    load() {
      api
        .get(this.form.id)
        .then(res => (this.form = res))
        .catch(() => {});
    },

    doDelete() {
      api
        .delete(this.form.id)
        .then(() => {
          this.$router.push({ name: "social-link-index" });
        })
        .catch(() => {});
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
    },
    noteErrors() {
      const errors = [];
      if (!this.$v.form.note.$dirty) return errors;
      !this.$v.form.note.required && errors.push(this.$t("required"));

      return errors;
    }
  },

  created() {
    this.form.id = parseInt(this.$route.params.id);
    if (this.form.id > 0) this.load();
    else this.resetForm();
  }
};
</script>
