<template>
  <card :title="$t('ticket')" :hide-tool-bar="true" :breadcrumbs="breadcrumbs">
    <template v-slot:customToolBar>
      <v-card width="100%" color="white">
        <v-card-text>
          <div>
            <template v-for="(item, i) in actions">
              <v-btn
                v-if="allowedOperations.includes(item.permission)"
                :key="i"
                @click="startDialog(item.action)"
                :color="item.color"
                class="white--text ma-1"
              >
                <v-icon class="mr-6" v-text="item.icon"></v-icon>
                <span v-text="item.name"></span>
              </v-btn>
            </template>
          </div>
        </v-card-text>
      </v-card>
    </template>
    <v-card class="ma-2" flat>
      <v-card-text>
        <v-row>
          <v-col cols="12" md="3" class="d-flex justify-center align-center">
            <v-icon x-large color="gray">mdi-ticket-account</v-icon>
          </v-col>
          <v-col cols="12" md="9">
            <v-row>
              <v-col cols="12" md="6">
                <div
                  class="text-body-1"
                  v-text="$t('code') + ': ' + code"
                ></div>
                <div
                  class="text-body-1"
                  v-text="$t('company') + ': ' + company"
                ></div>
                <div
                  class="text-body-1"
                  v-text="$t('status') + ': ' + status"
                ></div>
                <div
                  class="text-body-1"
                  v-text="$t('title') + ': ' + title"
                ></div>
              </v-col>
              <v-col cols="12" md="6">
                <div
                  class="text-body-1"
                  v-text="$t('date') + ': ' + date"
                ></div>
                <div
                  class="text-body-1"
                  v-text="$t('status') + ': ' + status"
                ></div>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-tabs v-model="tab" background-color="grey lighten-3">
      <v-tab v-text="$t('details')"> </v-tab>
      <v-tab v-text="$t('info')"> </v-tab>
      <v-tab v-text="$t('contract')"> </v-tab>
      <v-tab v-text="$t('attachment')"> </v-tab>
      <v-tab v-text="$t('history')"> </v-tab>
      <v-tab v-text="$t('tickets')"> </v-tab>
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item>
        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('requestedBy')"
            ></div>
            <v-text-field
              outlined
              dense
              readonly
              v-model="requestedBy"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('department')"
            ></div>
            <v-text-field
              v-model="department"
              readonly
              dense
              outlined
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('branch')"
            ></div>
            <v-text-field
              v-model="branch"
              readonly
              dense
              outlined
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('orderType')"
            ></div>
            <v-text-field
              v-model="orderType"
              readonly
              dense
              outlined
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('orderStatus')"
            ></div>
            <v-text-field
              v-model="status"
              dense
              outlined
              readonly
            ></v-text-field>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="3">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('title')"
            ></div>
            <v-text-field
              v-model="title"
              dense
              outlined
              readonly
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="9">
            <div
              class="text-body-1 black--text mb-2"
              v-text="$t('details')"
            ></div>
            <v-textarea v-model="details" dense outlined readonly></v-textarea>
          </v-col>
        </v-row>
      </v-tab-item>
      <!-- info -->
      <v-tab-item>
        <v-expansion-panels accordion focusable multiple class="my-4">
          <v-expansion-panel>
            <v-expansion-panel-header>
              {{ `${$t("phone")}  ` }}
              <template v-slot:actions>
                <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content class="ma-0 pa-0">
              <phone
                @module="phoneModule = $event"
                :items="phoneItems"
                :editable="false"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>

          <v-expansion-panel>
            <v-expansion-panel-header>
              {{ `${$t("mobile")} ` }}
              <template v-slot:actions>
                <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <mobile
                @module="mobileModule = $event"
                :items="mobileItems"
                :editable="false"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>
          <v-expansion-panel>
            <v-expansion-panel-header>
              {{ `${$t("email")} ` }}
              <template v-slot:actions>
                <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <email
                @module="emailModule = $event"
                :items="emailItems"
                :editable="false"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>

          <v-expansion-panel>
            <v-expansion-panel-header>
              {{ $t("location") }}
              <template v-slot:actions>
                <v-icon color="primary">mdi-arrow-down-bold-circle</v-icon>
              </template>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <location
                @module="locationModule = $event"
                :items="locationItems"
                :editable="false"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-tab-item>
      <!-- contract -->
      <v-tab-item>
        <!--  -->
      </v-tab-item>
      <!-- attachment -->
      <v-tab-item>
        <!--  -->
      </v-tab-item>
    </v-tabs-items>
    <v-dialog
      v-model="dialog.modal"
      width="800"
      persistent
      scrollable
      :fullscreen="$vuetify.breakpoint.smAndDown"
      :overlay="false"
      transition="dialog-transition"
    >
      <component
        v-if="dialog.modal"
        :is="dialog.component"
        v-bind="dialog.props"
        @close="closeDialog"
      ></component>
    </v-dialog>
  </card>
</template>

<script>
import card from "@/components/card";
// import { required } from "vuelidate/lib/validators";
import { mapFields } from "vuex-map-fields";
// import promise from "promise";
// import search from "./../../../components/search";
import module from "./show.store";
import email from "@/views/people-index/company/tabs/contact/email.vue";
import mobile from "@/views/people-index/company/tabs/contact/mobile.vue";
import phone from "@/views/people-index/company/tabs/contact/phone.vue";
import location from "@/views/people-index/company/tabs/contact/location.vue";
// operations
import assign from "./operations/assign";
import schedule from "./operations/schedule";
import redirect from "./operations/redirect";
import finish from "./operations/finish";
import escalate from "./operations/escalate";
import share from "./operations/share";
import report from "./operations/report";
import note from "./operations/note";
import sendEmail from "./operations/email";
import sendSms from "./operations/sms";
import closeTicket from "./operations/close";

export default {
  name: "ticket-show",

  components: {
    card,
    email,
    mobile,
    phone,
    location
  },

  data() {
    return {
      dialog: {
        modal: false,
        component: null,
        components: [
          { name: "assign", component: assign },
          { name: "schedule", component: schedule },
          { name: "redirect", component: redirect },
          { name: "finish", component: finish },
          { name: "escalate", component: escalate },
          { name: "share", component: share },
          { name: "report", component: report },
          { name: "addNote", component: note },
          { name: "sendEmail", component: sendEmail },
          { name: "sendSms", component: sendSms },
          { name: "closeTicket", component: closeTicket }
        ],
        props: null
      }
    };
  },

  computed: {
    ...mapFields("ticket", ["actions"]),
    ...mapFields("ticket", ["allowedOperations"]),
    ...mapFields("ticket", ["breadcrumbs"]),
    ...mapFields("ticket", ["tab"]),
    ...mapFields("ticket", ["statusItems"]),
    //form
    ...mapFields("ticket", ["id"]),
    ...mapFields("ticket", ["code"]),
    ...mapFields("ticket", ["company"]),
    ...mapFields("ticket", ["status"]),
    ...mapFields("ticket", ["orderStatus"]),
    ...mapFields("ticket", ["title"]),
    ...mapFields("ticket", ["date"]),
    ...mapFields("ticket", ["requestedBy"]),
    ...mapFields("ticket", ["department"]),
    ...mapFields("ticket", ["branch"]),
    ...mapFields("ticket", ["orderType"]),
    ...mapFields("ticket", ["details"]),
    // contact info
    ...mapFields("ticket", ["mobileItems"]),
    ...mapFields("ticket", ["emailItems"]),
    ...mapFields("ticket", ["phoneItems"]),
    ...mapFields("ticket", ["locationItems"]),
    // modules
    ...mapFields("ticket", ["mobileModule"]),
    ...mapFields("ticket", ["phoneModule"]),
    ...mapFields("ticket", ["emailModule"]),
    ...mapFields("ticket", ["locationModule"])
  },

  methods: {
    startDialog(action) {
      this.dialog.component = this.dialog.components.find(
        e => e.name == action
      ).component;
      this.dialog.props = {
        ticketId: this.id
      };
      this.dialog.modal = true;
    },
    closeDialog() {
      this.dialog.modal = false;
      this.dialog.component = null;
      this.dialog.props = null;
    },
    load() {
      this.$store.dispatch(`ticket/load`);
    },
    loadTheInitial() {
      this.$store.dispatch(`ticket/loadTheInitial`);
    }
  },

  created() {
    this.$store.registerModule("ticket", module);
    this.id = parseInt(this.$route.params.id);
    this.loadTheInitial();
    if (this.id) this.load();
  },

  beforeDestroy() {
    this.$store.unregisterModule("ticket");
  }
};
</script>

<style></style>
