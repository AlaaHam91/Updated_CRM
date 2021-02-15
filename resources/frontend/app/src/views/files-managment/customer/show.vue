<template>
  <card
    :title="$t('archives')"
    :hide-tool-bar="true"
    :hideGo="true"
    :breadcrumbs="breadcrumbs"
    :no="0"
  >
    <v-row>
      <v-col cols="12" md="3">
        <div
          class="text-body-1 text-no-wrap black--text mb-2"
          v-text="$t('customer')"
        ></div>
        <v-select
          :items="customers"
          v-model="customer"
          outlined
          dense
          :item-text="$i18n == 'ar' ? 'name' : 'latin_name'"
          item-value="id"
          return-object
          @change="load"
        ></v-select>
      </v-col>
    </v-row>
    <v-row class="justify-center">
      <div
        class="text-no-wrap black--text mb-2 text-lg-h6"
        v-text="customer.current_status"
      ></div>
    </v-row>
    <div
      v-if="loading"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>

    <archives
      v-else-if="customer.api"
      :api="customer.api"
      :recordId="customer.id"
      :can="customer.can"
    />
  </card>
</template>

<script>
import card from "./../../../components/card";
import archives from "./../../people-index/company/tabs/archive/archives";
import api from "@/services/api/files-managment/customer.api";

export default {
  name: "customer-files",
  components: { archives, card },
  data() {
    return {
      //"$i18n == 'ar' ? customer.mame : customer.latin_name"
      breadcrumbs: [
        {
          text: this.$t("controlPanel"),
          disabled: false
        },
        {
          text: this.$t("customerFiles"),
          disabled: false
        }
      ],
      loading: false,
      customers: [],
      customer: { api: null, can: false }
    };
  },

  methods: {
    loadInitial() {
      api
        .getAll()
        .then(res => (this.customers = res))
        .catch(() => {});
    },
    load() {
      if (!this.customer) return;
      this.loading = true;
      switch (this.customer.current_status) {
        case "Agent":
          this.customer.api = "people-index/agent.api.js";
          this.customer.can = this.$can("Pg_agent", "Update");
          break;
        case "PotentialAgent":
          this.customer.api = "people-index/potential-agent.api.js";
          this.customer.can = this.$can("Pg_poten_agent", "Update");

          break;
        case "Contact":
          this.customer.api = "people-index/contact.api.js";
          this.customer.can = this.$can("Pg_contact", "Update");

          break;
        case "PhoneBook":
          this.customer.api = "people-index/phonebook.api.js";
          this.customer.can = this.$can("Pg_phone_book", "Update");
          break;
        case "Company":
          this.customer.api = "people-index/company.api.js";
          this.customer.can = this.$can("Pg_company", "Update");

          break;
        default:
          this.customer.api = null;
          this.customer.can = false;
      }
      setTimeout(() => (this.loading = false), 2000);
    }
  },
  created() {
    this.loadInitial();
  }
};
</script>
