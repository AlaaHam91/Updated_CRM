<template>
  <card :hide-tool-bar="true" :breadcrumbs="breadcrumbs">
    <template v-slot:customToolBar>
      <v-card width="100%" color="white" class="mb-2">
        <v-card-text>
          <v-btn
            v-if="$can('Cp_userChange', 'Update')"
            class="ml-4"
            rounded
            outlined
            @click="sendAction('decline')"
            :loading="loading && type === 'decline'"
            v-text="$t('decline')"
            color="primary"
          ></v-btn>
          <v-btn
            v-if="$can('Cp_userChange', 'Update')"
            class="ml-4"
            rounded
            outlined
            @click="sendAction('accept')"
            v-text="$t('accept')"
            :loading="loading && type === 'accept'"
            color="success"
          ></v-btn>
        </v-card-text>
      </v-card>
    </template>
    <v-tabs v-model="tab" background-color="grey lighten-3">
      <v-tab>
        {{ $t("details") }}
      </v-tab>
      <v-tab>
        {{ $t("contactInfo") }}
      </v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab" v-if="pageData !== undefined">
      <!-- changes -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text text-center"
              v-text="$t('old')"
            ></div>

            <v-row>
              <v-col cols="12" md="12">
                <image-select
                  v-model="oImage"
                  class="mb-2"
                  size="5rem"
                  :disabled="true"
                ></image-select>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('name')"
                ></div>
                <v-text-field
                  v-model="pageData.oName"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('nameL')"
                ></div>
                <v-text-field
                  v-model="pageData.oNameL"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('userName')"
                ></div>
                <v-text-field
                  v-model="pageData.oUserName"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('email')"
                ></div>
                <v-text-field
                  v-model="pageData.oEmail"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12" v-if="hasLanguage">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('preferredLanguage')"
                ></div>
                <v-text-field
                  v-model="pageData.oLanguage"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text text-center"
              v-text="$t('new')"
            ></div>
            <v-col cols="12" md="12">
              <image-select
                v-model="nImage"
                class="mb-2"
                size="5rem"
                :disabled="true"
              ></image-select>
            </v-col>
            <v-row>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('name')"
                ></div>
                <v-text-field
                  v-model="pageData.nName"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('nameL')"
                ></div>
                <v-text-field
                  v-model="pageData.nNameL"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('userName')"
                ></div>
                <v-text-field
                  v-model="pageData.nUserName"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('email')"
                ></div>
                <v-text-field
                  v-model="pageData.nEmail"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="12" v-if="hasLanguage">
                <div
                  class="text-body-1 black--text mb-2"
                  v-text="$t('preferredLanguage')"
                ></div>
                <v-text-field
                  v-model="pageData.nLanguage"
                  outlined
                  readonly
                ></v-text-field>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- contact info -->
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text text-center"
              v-text="$t('old')"
            ></div>
            <v-card flat>
              <v-expansion-panels
                accordion
                focusable
                multiple
                class="my-4"
                v-model="panel"
              >
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("phone")}` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content class="ma-0 pa-0">
                    <phone
                      @module="phoneModule = $event"
                      :items="pageData.oPhoneItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("fax")} ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <fax
                      @module="faxModule = $event"
                      :items="pageData.oFaxItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("mobile")}  ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <mobile
                      @module="mobileModule = $event"
                      :items="pageData.oMobileItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("email")}  ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <emailInfo
                      @module="emailModule = $event"
                      :items="pageData.oEmailItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("address")}  ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <address-info
                      @module="addressModule = $event"
                      :items="pageData.oAddressItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ $t("location") }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <location
                      @module="locationModule = $event"
                      :items="pageData.oLocationItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-card>
          </v-col>
          <v-col cols="12" md="6">
            <div
              class="text-body-1 black--text text-center"
              v-text="$t('new')"
            ></div>
            <v-card flat>
              <v-expansion-panels
                accordion
                focusable
                multiple
                class="my-4"
                v-model="panel"
              >
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("phone")}` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content class="ma-0 pa-0">
                    <phone
                      @module="phoneModule = $event"
                      :items="pageData.nPhoneItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("fax")} ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <fax
                      @module="faxModule = $event"
                      :items="pageData.nFaxItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("mobile")}  ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <mobile
                      @module="mobileModule = $event"
                      :items="pageData.nMobileItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("email")}  ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <emailInfo
                      @module="emailModule = $event"
                      :items="pageData.nEmailItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    {{ `${$t("address")}  ` }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <address-info
                      @module="addressModule = $event"
                      :items="pageData.nAddressItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>

                  <v-expansion-panel-header>
                    {{ $t("location") }}
                    <template v-slot:actions>
                      <v-icon color="primary"
                        >mdi-arrow-down-bold-circle</v-icon
                      >
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <location
                      @module="locationModule = $event"
                      :items="pageData.nLocationItems"
                      :editable="false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-card>
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>
  </card>
</template>

<script>
import card from "@/components/card";
import addressInfo from "./../../people-index/company/tabs/contact/address";
import emailInfo from "./../../people-index/company/tabs/contact/email";
import fax from "./../../people-index/company/tabs/contact/fax";
import mobile from "./../../people-index/company/tabs/contact/mobile";
import phone from "./../../people-index/company/tabs/contact/phone";
import location from "./../../people-index/company/tabs/contact/location";

import imageSelect from "./../../../components/image-selector";
import fileApi from "@/services/api/files/file.api";

export default {
  name: "changes-show",
  components: {
    card,
    addressInfo,
    emailInfo,
    fax,
    mobile,
    phone,
    location,
    imageSelect
  },
  props: {
    pageData: Object,
    hasLanguage: { type: Boolean, default: true },
    api: {
      type: String
    },
    breadcrumbs: { type: Array },
    can: { type: Boolean, default: false },
    returnPath: { type: String }
  },
  data() {
    return {
      tab: null,
      panel: null,
      loading: false,
      type: null,
      oImage: null,
      nImage: null
    };
  },
  methods: {
    sendAction(type) {
      this.type = type;
      this.loading = true;
      let api = require(`@/services/api/${this.api}`);
      api.default
        .SendRequest({
          change_id: this.pageData.new.id,
          request_type: type
        })
        .then(() => this.$router.push({ name: this.returnPath }))
        .catch(() => {})

        .finally(() => (this.loading = false));
    }
  },
  created() {
    if (this.api === undefined) {
      this.$router.push({ name: "home" });
    } else this.breadcrumbs.push({ text: this.$t("showChanges") });
  },
  watch: {
    pageData: {
      handler: function(val) {
        if (val.oImage)
          if (val.oImage.includes("avatar")) this.oImage = val.oImage;
          else
            fileApi
              .getFile(val.oImage)
              .then(res => (this.oImage = URL.createObjectURL(res.data)));

        if (val.nImage)
          if (val.nImage.includes("avatar")) this.nImage = val.nImagev;
          else
            fileApi
              .getFile(val.nImage)
              .then(res => (this.nImage = URL.createObjectURL(res.data)));
      },
      immediate: true
    }
  }
};
</script>
