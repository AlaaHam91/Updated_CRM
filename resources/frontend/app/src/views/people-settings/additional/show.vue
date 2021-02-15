<template>
  <card
    :title="$t('additionalFields')"
    @reset-form="resetForm"
    @save="save"
    @delete-item="doDelete"
    save-btn
    :delete-btn="id ? $can('Pg_add_info', 'Delete') : false"
    :navigate="false"
    :breadcrumbs="breadcrumbs"
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
    <!-- field properties  -->

    <v-card class="mx-auto" flat>
      <v-row no-gutters>
        <v-col cols="12" md="3">
          <div
            class="text-body-2 text-no-wrap black--text mb-2"
            v-text="$t('type')"
          ></div>
          <v-select
            color
            v-model="type"
            :items="types"
            :error-messages="typeErrors"
            :readonly="id > 0"
            class="mr-1"
            dense
            outlined
            item-value="type"
            item-text="label"
          ></v-select>
        </v-col>
        <v-col cols="12" md="3">
          <div
            class="text-body-2 text-no-wrap black--text mb-2"
            v-text="$t('order')"
          ></div>
          <v-text-field
            dense
            outlined
            v-model="order"
            :error-messages="orderErrors"
            type="number"
            min="0"
            autocomplete="off"
            class="mr-1"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row no-gutters>
        <v-col cols="12" md="6" class="pt-8">
          <v-row no-gutters>
            <v-col cols="12" md="6">
              <div
                class="text-body-2 text-no-wrap black--text mb-2"
                v-text="$t('name')"
              ></div>
              <v-text-field
                outlined
                v-model="name"
                :error-messages="nameErrors"
                autocomplete="off"
                class="mr-1"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <div
                class="text-body-2 text-no-wrap black--text mb-2"
                v-text="$t('nameL')"
              ></div>
              <v-text-field
                outlined
                v-model="nameL"
                autocomplete="off"
                :error-messages="nameLErrors"
                class="mr-1"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row no-gutters>
            <v-col cols="12" md="6">
              <div
                class="text-body-2 text-no-wrap black--text mb-2"
                v-text="$t('defaultVal')"
              ></div>
              <v-text-field
                outlined
                v-model="defaultVal"
                autocomplete="off"
                class="mr-1"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <div
                class="text-body-2 text-no-wrap black--text mb-2"
                v-text="$t('defaultValL')"
              ></div>
              <v-text-field
                outlined
                v-model="defaultValL"
                autocomplete="off"
                class="mr-1"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12" md="6">
          <div
            class="text-body-2 text-no-wrap black--text mb-1"
            v-text="$t('accessType')"
          ></div>
          <datatable
            v-if="id > 0 ? accessTableItems.length > 0 : true"
            :headers="accessHeaders"
            :items="accessTableItems"
            @storeModule="accessTableStore = $event"
            height="8rem"
            module-prefix="access"
          />
          <div
            class="red--text text-body-2"
            v-for="(e, i) in accessErrors"
            :key="i"
            v-text="e"
          ></div>
        </v-col>
      </v-row>
      <v-row v-if="group == 1">
        <v-col cols="12" md="2" class="d-flex">
          <div
            class="text-body-2 text-no-wrap black--text mt-2 mr-2"
            v-text="$t('maxLength')"
          ></div>
          <v-text-field
            type="number"
            min="1"
            v-model="maxLength"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row v-if="group == 2">
        <v-col cols="12" md="2">
          <div
            class="text-body-2 text-no-wrap black--text mb-2"
            v-text="$t('min')"
          ></div>
          <v-text-field
            type="number"
            v-model="min"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="2">
          <div
            class="text-body-2 text-no-wrap black--text mb-2"
            v-text="$t('max')"
          ></div>
          <v-text-field
            type="number"
            v-model="max"
            outlined
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="12" md="2">
          <div
            class="text-body-2 text-no-wrap black--text mb-2"
            v-text="$t('step')"
          ></div>
          <v-text-field
            type="number"
            min="0"
            v-model="step"
            outlined
            dense
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row v-if="group == 3">
        <v-col cols="12" md="4">
          <v-checkbox
            v-model="multiSelect"
            :label="$t('multiSelect')"
            value="1"
            outlined
            dense
            hide-details
          ></v-checkbox>
        </v-col>
        <v-col cols="12" md="6">
          <datatable
            v-if="id > 0 ? optionsTableItems.length > 0 : true"
            :headers="optionsHeaders"
            :items="optionsTableItems"
            @storeModule="optionsTableStore = $event"
            height="10rem"
            module-prefix="options"
          />
          <div
            class="red--text text-body-2"
            v-for="e in optionsErrors"
            :key="e"
            v-text="e"
          ></div>
        </v-col>
      </v-row>
      <v-row v-if="group == 4">
        <v-col cols="12" md="4">
          <v-checkbox
            v-model="multiFile"
            :label="$t('multiFile')"
            value="allow_multi_file"
            outlined
            dense
            hide-details
          ></v-checkbox>
        </v-col>
      </v-row>
    </v-card>

    <!-- field -->
    <v-card
      elevation="1"
      class="mb-4 list-group-item"
      style="overflow: hidden;"
    >
      <v-card-title class="grey lighten-2" v-text="$t('field')"> </v-card-title>
      <v-card-text class="pa-4">
        <v-text-field
          v-if="type == 'Text'"
          outlined
          dense
          placeholder="Text Field"
          hide-details
        ></v-text-field>
        <v-text-field
          v-if="type == 'Password'"
          type="password"
          outlined
          dense
          placeholder="Password"
          hide-details
        ></v-text-field>
        <v-text-field
          v-if="type == 'Email'"
          outlined
          dense
          placeholder="Email"
          hide-details
        ></v-text-field>
        <!-- <quill-editor v-if="type == 'RichEditor'" /> -->
        <v-text-field
          v-if="type == 'Date'"
          placeholder="Date"
          outlined
          dense
          readonly
          hide-details
        ></v-text-field>
        <!-- <v-text-field
                      v-if="type == 'DateTime'"
                      placeholder="DateTime"
                      outlined
                      dense
                      
                      readonly
                      hide-details
                    ></v-text-field> -->
        <v-text-field
          v-if="type == 'Time'"
          placeholder="Time"
          outlined
          dense
          readonly
          hide-details
        ></v-text-field>
        <v-text-field
          v-if="['H1', 'H2', 'H3'].includes(type)"
          placeholder="Label"
          outlined
          dense
          readonly
          hide-details
        ></v-text-field>
        <v-text-field
          v-if="type == 'Number'"
          placeholder="0"
          type="number"
          outlined
          dense
          readonly
          hide-details
        ></v-text-field>
        <v-select
          v-if="type == 'Select'"
          placeholder="Select"
          outlined
          dense
          hide-details
        ></v-select>
        <v-checkbox
          label="checkbok"
          v-if="type == 'CheckGroup'"
          dense
          hide-details
        ></v-checkbox>
        <v-radio-group v-if="type == 'RadioGroup'" dense hide-details>
          <v-radio label="RadioGroup"></v-radio>
        </v-radio-group>
        <v-text-field
          v-if="type == 'FileUploader'"
          outlined
          dense
          placeholder="File uploader"
          hide-details
          readonly
        ></v-text-field>
      </v-card-text>
    </v-card>
  </card>
</template>

<script>
import card from "@/components/card";
import { required, requiredIf } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
import module from "./additional.store";
// import promise from "promise";
// import draggable from "vuedraggable";
// import "quill/dist/quill.core.css";
// import "quill/dist/quill.snow.css";
// import "quill/dist/quill.bubble.css";
// import { quillEditor } from "vue-quill-editor";
import datatable from "@/components/datatable/datatable";

export default {
  components: {
    card,
    // quillEditor,
    datatable
  },
  name: "additional-show",

  data() {
    return {
      drag: false
    };
  },

  validations: {
    name: { required },
    nameL: { required },
    order: { required },
    type: { required },
    accessTableItems: { required },
    optionsTableItems: {
      required: requiredIf(function() {
        return this.group == 3;
      })
    }
  },

  methods: {
    async save() {
      this.$v.$touch();
      await this.$store.dispatch(`userFields/touchDatatables`);
      if (
        this.$v.$invalid ||
        (await this.invalidOptionsTable) ||
        (await this.invalidAccessTable)
      ) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
        return;
      } else if (
        !this.$v.$invalid &&
        (await !this.invalidOptionsTable) &&
        (await !this.invalidAccessTable)
      )
        this.$store
          .dispatch("userFields/save")
          .then(() => {
            this.$router.push({ name: "additional-index" });
          })
          .catch(() => {
            //
          });
    },
    load() {
      this.$store.dispatch(`userFields/load`);
    },
    resetForm() {
      this.$v.$reset();
      this.$store.dispatch(`userFields/reset`);
    },
    loadTheInitial() {
      this.$store.dispatch(`userFields/loadTheInitial`);
    },
    doDelete() {
      this.$store
        .dispatch(`userFields/delete`)
        .then(() => this.$router.push({ name: "additional-index" }))
        .catch(() => {
          //
        });
    }
  },

  computed: {
    ...mapFields("userFields", ["breadcrumbs"]),
    ...mapFields("userFields", ["messages"]),
    ...mapFields("userFields", ["types"]),
    ...mapFields("userFields", ["accessHeaders"]),
    ...mapFields("userFields", ["accessTableStore"]),
    ...mapFields("userFields", ["optionsHeaders"]),
    ...mapFields("userFields", ["optionsTableStore"]),
    // properties
    ...mapFields("userFields", ["id"]),
    ...mapFields("userFields", ["type"]),
    ...mapFields("userFields", ["order"]),
    ...mapFields("userFields", ["group"]),
    ...mapFields("userFields", ["name"]),
    ...mapFields("userFields", ["nameL"]),
    ...mapFields("userFields", ["defaultVal"]),
    ...mapFields("userFields", ["defaultValL"]),
    ...mapFields("userFields", ["maxLength"]),
    ...mapFields("userFields", ["min"]),
    ...mapFields("userFields", ["max"]),
    ...mapFields("userFields", ["step"]),
    ...mapFields("userFields", ["multiSelect"]),
    ...mapFields("userFields", ["accessTableItems"]),
    ...mapFields("userFields", ["optionsTableItems"]),
    ...mapFields("userFields", ["multiFile"]),

    typeErrors() {
      const errors = [];
      if (!this.$v.type.$dirty) return errors;
      !this.$v.type.required && errors.push(this.$t("required"));
      return errors;
    },
    orderErrors() {
      const errors = [];
      if (!this.$v.order.$dirty) return errors;
      !this.$v.order.required && errors.push(this.$t("required"));
      return errors;
    },
    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.required && errors.push(this.$t("required"));
      return errors;
    },
    nameLErrors() {
      const errors = [];
      if (!this.$v.nameL.$dirty) return errors;
      !this.$v.nameL.required && errors.push(this.$t("required"));
      return errors;
    },
    invalidOptionsTable() {
      return this.optionsTableStore
        ? this.$store.getters[`${this.optionsTableStore}/hasErrors`]
        : false;
    },
    optionsErrors() {
      const errors = [];
      if (!this.$v.optionsTableItems.$dirty) return errors;
      !this.$v.optionsTableItems.required && errors.push(this.$t("required"));
      this.invalidOptionsTable && errors.push(this.$t("checkInputs"));
      return errors;
    },
    invalidAccessTable() {
      return this.accessTableStore
        ? this.$store.getters[`${this.accessTableStore}/hasErrors`]
        : false;
    },
    accessErrors() {
      const errors = [];
      if (!this.$v.accessTableItems.$dirty) return errors;
      !this.$v.accessTableItems.required && errors.push(this.$t("required"));
      this.invalidAccessTable && errors.push(this.$t("checkInputs"));
      return errors;
    }
  },

  watch: {
    "$store.state.userFields.type"(val) {
      if (val) this.group = this.types.find(e => e.type == val).group;
    }
  },

  created() {
    this.$store.registerModule("userFields", module);
    this.id = parseInt(this.$route.params.id);
    if (this.id) this.load();
    else if (this.id == 0) this.resetForm();
  },

  beforeDestroy() {
    this.$store.unregisterModule("userFields");
  }
};
</script>

<style></style>
