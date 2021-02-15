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
            <v-col cols="12" md="3" xl="2">
              <v-card class="rounded mt-2" elevation="4" max-width="100%">
                <v-tabs
                  vertical
                  v-model="ticketState"
                  background-color="primary"
                  dark
                >
                  <v-tab
                    v-for="(item, i) in stateItems"
                    :key="i"
                    class="d-flex justify-start"
                  >
                    <v-icon class="me-4" v-html="item.icon"></v-icon>

                    <span class="text-caption" v-text="item.text"></span>
                  </v-tab>
                </v-tabs>
              </v-card>
            </v-col>
            <v-col cols="12" md="9" xl="10" class="pa-2">
              <v-expansion-panels class="mb-4">
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ $t("filters") }}
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <div
                      class="d-flex justify-center mb-2"
                      style="width: 100%;"
                    >
                      <div>
                        <v-tabs v-model="dateRange">
                          <v-tab v-for="(item, i) in dateRangeItems" :key="i">
                            <div class="d-flex flex-column justify-center">
                              <v-icon color="blue darken-4">
                                mdi-calendar-range-outline
                              </v-icon>
                              <div class="text-body-2" v-text="item.text"></div>
                            </div>
                          </v-tab>
                        </v-tabs>
                      </div>
                    </div>

                    <v-tabs-items v-model="ticketState">
                      <v-tab-item
                        :transition="false"
                        :reverse-transition="false"
                        :value="0"
                      >
                        <v-row>
                          <v-col cols="12" md="4">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('user')"
                            ></div>
                            <search
                              :placeholder="$t('user')"
                              v-model="user"
                              :item-text="
                                $i18n.locale == 'en' ? 'latin_name' : 'name'
                              "
                              item-value="id"
                              api="users/user.api.js"
                              :headers="searchHeaders"
                            ></search>
                          </v-col>
                          <v-col cols="12" md="4">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('employee')"
                            ></div>
                            <search
                              :placeholder="$t('employee')"
                              v-model="employee"
                              :item-text="
                                $i18n.locale == 'en' ? 'latin_name' : 'name'
                              "
                              item-value="id"
                              api="users/user.api.js"
                              :headers="searchHeaders"
                            ></search>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-checkbox
                              class="mt-6"
                              v-model="ownTickets"
                              :label="$t('onlyOwnTickets')"
                            ></v-checkbox>
                          </v-col>
                        </v-row>
                      </v-tab-item>
                      <v-tab-item
                        :transition="false"
                        :reverse-transition="false"
                        :value="4"
                      >
                        <v-row>
                          <v-col cols="12" md="6">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('employee')"
                            ></div>
                            <div class="d-flex">
                              <search
                                :placeholder="$t('employee')"
                                v-model="employee"
                                :item-text="
                                  $i18n.locale == 'en' ? 'latin_name' : 'name'
                                "
                                item-value="id"
                                api="users/user.api.js"
                                :headers="searchHeaders"
                              ></search>
                              <v-select
                                v-model="employeeFilter"
                                :items="filterTypes"
                                outlined
                                dense
                                :item-text="
                                  $i18n.locale == 'en' ? 'labelEn' : 'labelAr'
                                "
                                item-value="value"
                                class="ml-1"
                              >
                                <template v-slot:selection="{ item }">
                                  <span
                                    class="d-flex justify-center font-weight-regular"
                                    style="width: 100%;"
                                  >
                                    {{ item.display }}
                                  </span>
                                </template>
                              </v-select>
                            </div>
                          </v-col>
                          <v-col cols="12" md="6">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('expectedTime')"
                            ></div>
                            <div class="d-flex">
                              <v-text-field
                                v-model="expectedTime"
                                outlined
                                dense
                                type="number"
                                min="0"
                              ></v-text-field>
                              <v-select
                                :items="filterTypes"
                                outlined
                                dense
                                :item-text="
                                  $i18n.locale == 'en' ? 'labelEn' : 'labelAr'
                                "
                                item-value="value"
                                v-model="expectedTimeFilter"
                                class="ml-1"
                              >
                                <template v-slot:selection="{ item }">
                                  <span
                                    class="d-flex justify-center font-weight-regular"
                                    style="width: 100%;"
                                  >
                                    {{ item.display }}
                                  </span>
                                </template>
                              </v-select>
                            </div>
                          </v-col>
                        </v-row>
                      </v-tab-item>
                      <v-tab-item
                        :transition="false"
                        :reverse-transition="false"
                        :value="5"
                      >
                        <v-row>
                          <v-col cols="12" md="6">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('executionDate')"
                            ></div>
                            <v-menu
                              v-model="executionDateM"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              transition="scale-transition"
                              offset-y
                              min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <div class="d-flex">
                                  <v-text-field
                                    v-model="executionDate"
                                    outlined
                                    dense
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    clearable
                                  ></v-text-field>
                                  <v-select
                                    v-model="executionDateFilter"
                                    :items="filterTypes"
                                    outlined
                                    dense
                                    :item-text="
                                      $i18n.locale == 'en'
                                        ? 'labelEn'
                                        : 'labelAr'
                                    "
                                    item-value="value"
                                    class="ml-1"
                                  >
                                    <template v-slot:selection="{ item }">
                                      <span
                                        class="d-flex justify-center font-weight-regular"
                                        style="width: 100%;"
                                      >
                                        {{ item.display }}
                                      </span>
                                    </template>
                                  </v-select>
                                </div>
                              </template>
                              <v-date-picker
                                v-model="executionDate"
                                @input="executionDateM = false"
                                :locale="$i18n.locale"
                              ></v-date-picker>
                            </v-menu>
                          </v-col>
                          <v-col cols="12" md="6">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('executionTime')"
                            ></div>

                            <v-menu
                              ref="executionTimeMenu"
                              v-model="timeMenu"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              :return-value.sync="executionTime"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <div class="d-flex">
                                  <v-text-field
                                    v-model="executionTime"
                                    outlined
                                    dense
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                  <v-select
                                    v-model="executionTimeFilter"
                                    :items="filterTypes"
                                    outlined
                                    dense
                                    :item-text="
                                      $i18n.locale == 'en'
                                        ? 'labelEn'
                                        : 'labelAr'
                                    "
                                    item-value="value"
                                    class="ml-1"
                                  >
                                    <template v-slot:selection="{ item }">
                                      <span
                                        class="d-flex justify-center font-weight-regular"
                                        style="width: 100%;"
                                      >
                                        {{ item.display }}
                                      </span>
                                    </template>
                                  </v-select>
                                </div>
                              </template>
                              <v-time-picker
                                v-if="timeMenu"
                                v-model="executionTime"
                                full-width
                                format="24hr"
                                @click:minute="
                                  $refs.executionTimeMenu.save(executionTime)
                                "
                              ></v-time-picker>
                            </v-menu>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" md="6">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('executionType')"
                            ></div>
                            <div class="d-flex">
                              <v-select
                                v-model="executionType"
                                :items="executionTypeItems"
                                :item-text="
                                  $i18n.locale == 'en' ? 'nameL' : 'name'
                                "
                                item-value="id"
                                dense
                                outlined
                              ></v-select>
                              <v-select
                                v-model="expectedTimeFilter"
                                :items="filterTypes"
                                outlined
                                dense
                                :item-text="
                                  $i18n.locale == 'en' ? 'labelEn' : 'labelAr'
                                "
                                item-value="value"
                                class="ml-1"
                              >
                                <template v-slot:selection="{ item }">
                                  <span
                                    class="d-flex justify-center font-weight-regular"
                                    style="width: 100%;"
                                  >
                                    {{ item.display }}
                                  </span>
                                </template>
                              </v-select>
                            </div>
                          </v-col>
                        </v-row>
                      </v-tab-item>
                      <v-tab-item
                        :transition="false"
                        :reverse-transition="false"
                        :value="tab6"
                      >
                        <v-row>
                          <v-col cols="12" md="4">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('user')"
                            ></div>
                            <search
                              :placeholder="$t('user')"
                              v-model="user"
                              :item-text="
                                $i18n.locale == 'en' ? 'latin_name' : 'name'
                              "
                              item-value="id"
                              api="users/user.api.js"
                              :headers="searchHeaders"
                            ></search>
                          </v-col>
                          <v-col cols="12" md="4">
                            <div
                              class="text-body-1 black--text mb-2"
                              v-text="$t('employee')"
                            ></div>
                            <search
                              :placeholder="$t('employee')"
                              v-model="employee"
                              :item-text="
                                $i18n.locale == 'en' ? 'latin_name' : 'name'
                              "
                              item-value="id"
                              api="users/user.api.js"
                              :headers="searchHeaders"
                            ></search>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-checkbox
                              v-model="ownTickets"
                              class="mt-6"
                              :label="$t('onlyOwnTickets')"
                            ></v-checkbox>
                          </v-col>
                        </v-row>
                      </v-tab-item>
                    </v-tabs-items>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
              <!-- table -->
              <v-card class="border-lg" elevation="4">
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
                              <search-filter @set-filter="setFilter(i, $event)">
                              </search-filter>
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
import searcFilter from "@/components/filter";
import _ from "lodash";
import { mapFields } from "vuex-map-fields";
import module from "./index.store.js";
import search from "@/components/search";

export default {
  name: "ticket-index",

  components: {
    "search-filter": searcFilter,
    search
  },

  data() {
    return {
      executionDateM: false,
      timeMenu: false
    };
  },

  methods: {
    setFilter(index, $event) {
      this.headers[index]["filter"] = {};
      this.headers[index]["filter"] = $event ? _.cloneDeep($event) : undefined;

      this.tableHeadersFilter = "";
      this.headers.forEach(header => {
        if (header.filter)
          if (header.filter.value && header.filter.operator)
            this.tableHeadersFilter += `${header.value}=${header.filter.value}&${header.value}_filter=${header.filter.operator}`;
      });
      if (this.tableHeadersFilter.length > 2) this.load();
    },
    load() {
      this.$store.dispatch(`ticketIndex/load`);
    },
    rowClick(id) {
      this.$router.push({ name: "ticket-show", params: { id: id } });
    }
  },

  computed: {
    ...mapFields("ticketIndex", ["breadcrumbs"]),
    ...mapFields("ticketIndex", ["searchHeaders"]),
    ...mapFields("ticketIndex", ["filterTypes"]),
    ...mapFields("ticketIndex", ["executionTypeItems"]),
    ...mapFields("ticketIndex", ["executionTypeFilter"]),
    ...mapFields("ticketIndex", ["dateRangeItems"]),
    //
    ...mapFields("ticketIndex", ["stateItems"]),
    ...mapFields("ticketIndex", ["headers"]),
    ...mapFields("ticketIndex", ["ticketState"]),
    ...mapFields("ticketIndex", ["items"]),
    ...mapFields("ticketIndex", ["tableHeadersFilter"]),
    // filters
    ...mapFields("ticketIndex", ["dateRange"]),
    ...mapFields("ticketIndex", ["user"]),
    ...mapFields("ticketIndex", ["employee"]),
    ...mapFields("ticketIndex", ["ownTickets"]),
    ...mapFields("ticketIndex", ["expectedTime"]),
    ...mapFields("ticketIndex", ["executionDate"]),
    ...mapFields("ticketIndex", ["expectedTimeFilter"]),
    ...mapFields("ticketIndex", ["executionDateFilter"]),
    ...mapFields("ticketIndex", ["executionTime"]),
    ...mapFields("ticketIndex", ["executionType"]),
    ...mapFields("ticketIndex", ["executionTimeFilter"]),
    ...mapFields("ticketIndex", ["employeeFilter"]),
    tab6() {
      if (this.ticketState > 5) return this.ticketState;
      return -1;
    }
  },

  watch: {
    "$store.state.ticketIndex.ticketState"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.dateRange"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.user"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.employee"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.ownTickets"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.expectedTime"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.executionDate"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.expectedTimeFilter"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.executionDateFilter"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.executionTime"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.executionType"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.executionTimeFilter"(val) {
      if (val) this.load();
    },
    "$store.state.ticketIndex.employeeFilter"(val) {
      if (val) this.load();
    }
  },

  async created() {
    await this.$store.registerModule("ticketIndex", module);
    await this.$store.dispatch("ticketIndex/loadInitial");
    await this.load();
  },

  beforeDestroy() {
    this.$store.unregisterModule("ticketIndex");
  }
};
</script>

<style scoped></style>
