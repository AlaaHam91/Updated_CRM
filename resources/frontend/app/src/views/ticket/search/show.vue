<template>
  <div>
    <v-sheet
      width="100%"
      color="#FFFFFF"
      class="d-flex flex-column align-start pa-4"
      style="border-bottom: 1px solid lightgray;"
    >
      <div class="text-h5 ml-4" v-text="$t('tickets')"></div>
      <v-breadcrumbs v-if="breadcrumbs" :items="breadcrumbs"></v-breadcrumbs>
    </v-sheet>

    <v-container>
      <v-card width="100%" flat color="transparent">
        <v-card-text class="ma-0 pa-0">
          <v-row no-gutters>
            <v-col cols="12" md="12" class="pa-2">
              <v-expansion-panels class="mb-4">
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    <div>
                      <v-icon class="mr-6" color="primary">
                        mdi-filter-outline
                      </v-icon>
                      {{ $t("filters") }}
                    </div>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-row>
                      <v-col cols="12" md="3">
                        <div
                          class="text-body-1 black--text mb-2"
                          v-text="$t('searchText')"
                        ></div>
                        <v-text-field
                          :placeholder="$t('search')"
                          v-model="searchText"
                          outlined
                          dense
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="3">
                        <div
                          class="text-body-1 black--text mb-2"
                          v-text="$t('dateFrom')"
                        ></div>
                        <v-menu
                          v-model="dateFromM"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          transition="scale-transition"
                          offset-y
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <div class="d-flex">
                              <v-text-field
                                v-model="dateFrom"
                                outlined
                                dense
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                clearable
                              ></v-text-field>
                            </div>
                          </template>
                          <v-date-picker
                            v-model="dateFrom"
                            @input="dateFromM = false"
                            :locale="$i18n.locale"
                          ></v-date-picker>
                        </v-menu>
                      </v-col>
                      <v-col cols="12" md="3">
                        <div
                          class="text-body-1 black--text mb-2"
                          v-text="$t('dateTo')"
                        ></div>
                        <v-menu
                          v-model="dateToM"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          transition="scale-transition"
                          offset-y
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <div class="d-flex">
                              <v-text-field
                                v-model="dateTo"
                                outlined
                                dense
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                clearable
                                :error-messages="dateToErrors"
                              ></v-text-field>
                            </div>
                          </template>
                          <v-date-picker
                            v-model="dateTo"
                            @input="dateToM = false"
                            :locale="$i18n.locale"
                          ></v-date-picker>
                        </v-menu>
                      </v-col>
                    </v-row>
                    <div class="d-flex">
                      <div
                        class="text-body-1 black--text mb-2"
                        v-text="$t('searchFields')"
                      ></div>
                      <v-btn small icon @click="addSearchField">
                        <v-icon color="">mdi-plus</v-icon>
                      </v-btn>
                    </div>
                    <v-row
                      v-for="(item, i) in searchFields"
                      :key="i"
                      no-gutters
                    >
                      <v-col cols="12" md="3">
                        <v-select
                          v-model="searchFields[i]"
                          :items="searchFieldsItems"
                          item-text="label"
                          item-value="value"
                          dense
                          outlined
                          append-outer-icon="mdi-delete-empty-outline"
                          @click:append-outer="removeSearchField(i)"
                        ></v-select>
                      </v-col>
                    </v-row>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
              <!-- table -->
              <v-card class="border-lg" elevation="4">
                <v-progress-linear
                  indeterminate
                  v-if="loading"
                ></v-progress-linear>
                <v-card-text>
                  <v-simple-table height="20rem" fixed-header>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th
                            class="text-center"
                            v-for="(header, i) in headers"
                            :key="i"
                          >
                            <div style="min-width: 14rem;">
                              <div
                                class="text-body-2 mt-2"
                                v-text="header.text"
                              ></div>
                            </div>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(item, j) in items"
                          :key="j"
                          @click="rowClick(item.id)"
                        >
                          <td
                            class="text-center cursor-pointer"
                            v-for="(header, i) in headers"
                            :key="i"
                            v-text="item[header.value]"
                          ></td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import api from "@/services/api/ticket/ticket.api.js";
import { requiredIf } from "vuelidate/lib/validators";
const isAfterDate = (value, vm) => {
  if (vm.dateFrom && vm.dateTo)
    return new Date(value).getTime() > new Date(vm.dateFrom).getTime();
  else return true;
};
export default {
  name: "ticket-search",

  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("tickets"),
          disabled: false
        },
        {
          text: this.$t("search"),
          disabled: false
        }
      ],
      loading: false,
      dateFromM: false,
      dateToM: false,
      searchText: "",
      dateFrom: null,
      dateTo: null,
      searchFields: [],
      headers: [
        { text: this.$t("code"), value: "code" },
        { text: this.$t("company"), value: "company" },
        { text: this.$t("requestBy"), value: "requestBy" },
        { text: this.$t("department"), value: "department" },
        { text: this.$t("branch"), value: "branch" },
        { text: this.$t("orderType"), value: "ordertype" },
        { text: this.$t("title"), value: "title" },
        { text: this.$t("date"), value: "date" },
        { text: this.$t("time"), value: "time" }
      ],
      searchFieldsItems: [
        { value: "TicketNo", label: this.$t("code") },
        { value: "Branch", label: this.$t("branch") },
        { value: "Department", label: this.$t("department") },
        { value: "Company", label: this.$t("company") },
        { value: "RequestBy", label: this.$t("requestBy") },
        { value: "Mobile", label: this.$t("mobile") },
        { value: "Telephone", label: this.$t("phone") },
        { value: "Email", label: this.$t("email") },
        { value: "Area", label: this.$t("area") },
        { value: "TicketTitle", label: this.$t("title") },
        { value: "Description", label: this.$t("description") }
      ],
      items: []
    };
  },

  validations: {
    dateTo: {
      required: requiredIf(function() {
        return this.dateFrom;
      }),
      isAfterDate
    }
  },

  methods: {
    addSearchField() {
      if (this.searchFields.length < 12) this.searchFields.push("");
    },
    removeSearchField(index) {
      this.searchFields.splice(index, 1);
    },
    load() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      } else {
        this.loading = true;
        let url = "";
        let params = [];
        if (this.searchText)
          params.push({ param: "search_text", val: this.searchText });
        this.searchFields.forEach((element, i) => {
          if (element)
            params.push({ param: `search_fields[${i}]`, val: element });
        });
        if (this.dateFrom)
          params.push({ param: "date_from", val: this.dateFrom });
        if (this.dateTo) params.push({ param: "date_to", val: this.dateTo });

        params.forEach((element, i) => {
          if (i == 0) url += `?${element.param}=${element.val}`;
          else url += `&${element.param}=${element.val}`;
        });
        api
          .search(url)
          .then(res => {
            this.items = [];
            res.forEach(item => {
              this.items.push({
                id: item.id,
                code: item.code,
                company:
                  this.$i18n.locale == "en"
                    ? item.company.latin_name
                    : item.company.name,
                requestBy:
                  this.$i18n.locale == "en"
                    ? item.request_by.latin_name
                    : item.request_by.name,
                department:
                  this.$i18n.locale == "en"
                    ? item.department.latin_name
                    : item.department.name,
                branch:
                  this.$i18n.locale == "en"
                    ? item.branch.latin_name
                    : item.branch.name,
                ordertype:
                  this.$i18n.locale == "en"
                    ? item.order_type.latin_name
                    : item.order_type.name,
                title: item.title,
                date: item.date,
                time: item.time
              });
            });
          })
          .finally(() => (this.loading = false));
      }
    },
    rowClick(id) {
      this.$router.push({ name: "ticket-show", params: { id: id } });
    }
  },

  computed: {
    dateToErrors() {
      const errors = [];
      if (!this.$v.dateTo.$dirty) return errors;
      !this.$v.dateTo.required && errors.push(this.$t("required"));
      !this.$v.dateTo.isAfterDate && errors.push(this.$t("latterDate"));
      return errors;
    },
    filters() {
      return `${this.searchText} |
                ${this.dateFrom} |
                ${this.dateTo}`;
    }
  },

  watch: {
    filters() {
      this.load();
    },
    searchFields: {
      handler: function() {
        this.load();
      },
      deep: true
    }
  },

  created() {
    this.load();
  }
};
</script>

<style scoped></style>
