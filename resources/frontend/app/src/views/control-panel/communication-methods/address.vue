<template>
  <v-container fluid>
    <v-simple-table class="table table-striped table-bordered">
      <thead>
        <tr>
          <!-- <th>#</th> -->
          <th v-text="$t('country')"></th>
          <th v-text="$t('administrativeDivision')"></th>
          <th v-text="$t('fields')"></th>
          <th v-text="$t('actions')"></th>
          <th>
            <v-btn class="my-2 ml-4" small icon @click="addRow('main')">
              <v-icon>mdi-plus-circle-outline</v-icon>
            </v-btn>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, i) in $v.tempItems.$each.$iter" :key="i + 'p'">
          <!-- <td>{{ i + 1 }}</td> -->
          <td>
            <v-select
              :items="countries"
              v-model="item.$model.country"
              item-value="id"
              :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
              class="mt-2"
              dense
              outlined
              color="primary"
              background-color="inputBack"
              @change="loadDivisions(item.$model.country)"
            ></v-select>
            <div
              class="red--text text-body-2"
              v-if="!item.country.required"
              v-text="$t('required')"
            ></div>
          </td>
          <td>
            <v-select
              :items="computedDivisions"
              v-model="item.$model.administrativeDivision"
              :item-text="$i18n == 'ar' ? 'name' : 'nameL'"
              item-value="id"
              class="mt-2"
              dense
              outlined
              color="primary"
              background-color="inputBack"
            ></v-select>
            <div
              class="red--text text-body-2"
              v-if="!item.administrativeDivision.required"
              v-text="$t('required')"
            ></div>
          </td>
          <td>
            <v-btn
              v-model="item.$model.address_config"
              v-text="$t('fields')"
              @click="openDialog(i)"
              hide-details
            >
            </v-btn>
            <div
              class="red--text text-body-2"
              v-if="!item.address_config.required"
              v-text="$t('required')"
            ></div>
          </td>
          <td>
            <v-btn class="my-2 ml-4" small icon @click="removeRow('main', i)">
              <v-icon>
                mdi-delete
              </v-icon>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </v-simple-table>

    <v-dialog
      color="transparent"
      v-model="itemDialog"
      max-width="500px"
      scrollable
      :fullscreen="$vuetify.breakpoint.mobile"
    >
      <v-card color="cardBack">
        <v-card-text>
          <v-container>
            <v-simple-table>
              <thead>
                <tr>
                  <!-- <th>#</th> -->
                  <th v-text="$t('name')"></th>
                  <th v-text="$t('nameL')"></th>
                  <th v-text="$t('isRequired')"></th>
                  <th></th>
                  <th>
                    <v-btn
                      class="my-2 ml-4"
                      small
                      icon
                      @click="addRow('child')"
                    >
                      <v-icon>mdi-plus-circle-outline</v-icon>
                    </v-btn>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(field, j) in $v.subFields.address_config.$each.$iter"
                  :key="j"
                >
                  <!-- <td>{{ j + 1 }}</td> -->

                  <td>
                    <v-text-field
                      outlined
                      v-model="field.$model.name"
                      autocomplete="off"
                      hide-details
                    ></v-text-field>
                    <div
                      class="red--text text-body-2"
                      v-if="!field.name.required"
                      v-text="$t('required')"
                    ></div>
                  </td>
                  <td>
                    <v-text-field
                      outlined
                      v-model="field.$model.latin_name"
                      autocomplete="off"
                      hide-details
                    ></v-text-field>
                    <div
                      class="red--text text-body-2"
                      v-if="!field.latin_name.required"
                      v-text="$t('required')"
                    ></div>
                  </td>
                  <td>
                    <v-checkbox v-model="field.$model.is_required">
                    </v-checkbox>
                  </td>
                  <td>
                    <v-text-field
                      hidden
                      v-model="field.$model.config"
                    ></v-text-field>
                  </td>
                  <td>
                    <v-btn
                      class="my-2 ml-4"
                      small
                      icon
                      @click="removeRow('child', j)"
                    >
                      <v-icon>
                        mdi-delete
                      </v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </v-simple-table>
          </v-container>
        </v-card-text>

        <v-card-actions class="d-flex justify-center">
          <v-btn
            class="mr-4"
            color="secondary"
            rounded
            @click="saveDialog"
            v-text="$t('saveBtn')"
          ></v-btn>
          <v-btn
            class="ml-4"
            rounded
            outlined
            @click="closeDialog"
            v-text="$t('cancel')"
          ></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import countryApi from "@/services/api/company-data/country.api.js";
import divisionApi from "./../../../services/api/control-panel/administrative-division.api";
import { mapFields } from "vuex-map-fields";

import { cloneDeep } from "lodash";

export default {
  name: "communication-method-address-show",
  props: {
    "validate-table": { type: Boolean, default: false }
  },
  data() {
    return {
      countries: [],
      divisions: [],
      itemDialog: false,
      subFields: {
        id: 0,
        address_config: []
      },
      tempItems: []
    };
  },
  validations: {
    //main table
    tempItems: {
      $each: {
        country: { required },
        administrativeDivision: { required },
        address_config: { required }
      }
    },
    //child table
    subFields: {
      address_config: {
        $each: {
          name: { required },
          latin_name: { required }
        }
      }
    }
  },
  methods: {
    async loadInitial() {
      await countryApi
        .getAll()
        .then(res => (this.countries = res))
        .catch(() => {});
    },
    async loadDivisions(id) {
      //get all divisions by countryId
      await divisionApi
        .getByCountry(id)
        .then(res => (this.divisions = res))
        .catch(() => {});
    },
    getId(arr) {
      let id = 0;
      arr.forEach(element => {
        if (element.id > id) id = element.id;
      });
      return id + 1;
    },
    addRow(type) {
      if (type === "main")
        this.tempItems.push({
          id: this.getId(this.tempItems),
          country: null,
          administrativeDivision: null,
          address_config: []
        });
      else if (type === "child") {
        this.subFields.address_config.push({
          name: null,
          latin_name: null,
          is_required: false,
          config: null
        });
      } else return;
    },
    removeRow(type, index) {
      this.$v.$reset();

      if (type == "main" && index > -1) this.tempItems.splice(index, 1);
      else if (type == "child" && index > -1)
        this.subFields.address_config.splice(index, 1);
      else return;
    },
    openDialog(index) {
      this.subFields.address_config = cloneDeep(
        this.tempItems[index].address_config
      );
      this.subFields.id = index;
      this.itemDialog = true;
    },
    closeDialog() {
      this.itemDialog = false;
    },
    //check child table elemnts
    saveDialog() {
      this.$v.subFields.$touch();
      if (this.$v.subFields.$invalid) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
        return;
      }
      let parentIndex = this.subFields.id;
      //update parent address_config
      this.itemDialog = false;
      this.tempItems[parentIndex].address_config = cloneDeep(
        this.subFields.address_config
      );
    },
    async checkInputs() {
      this.$v.tempItems.$touch();
      this.$v.subFields.$touch();

      if (this.$v.tempItems.$invalid || this.$v.subFields.$invalid) {
        this.$notify({
          text: this.$t("checkInputs"),
          type: "warning"
        });
        return;
      }
      //send valid data to the store
      this.$emit("isValid", true);
      this.newAddressItems = cloneDeep(this.tempItems);
      await this.$store.dispatch(`communicationMethods/UpdateAddressItems`);
    }
  },
  computed: {
    ...mapFields("communicationMethods", ["newAddressItems"]),
    ...mapFields("communicationMethods", ["addressItems"]),

    computedDivisions() {
      return this.divisions.filter(
        division =>
          !this.tempItems.find(c => c.administrativeDivision == division.id)
      );
    },
    subFieldsErrors() {
      const errors = [];
      if (!this.$v.subFields.$dirty) return errors;
      !this.$v.subFields.required && errors.push(this.$t("required"));

      return errors;
    }
  },
  watch: {
    validateTable(val) {
      if (val) {
        this.checkInputs();
      }
    }
  },
  created() {
    this.loadInitial();
    this.tempItems = cloneDeep(this.addressItems);
  }
};
</script>
<style scoped></style>
