<template>
  <div class="pa-4">
    <v-btn
      color="success"
      class="mb-4"
      v-text="$t('addPersonToCompany')"
      @click="addPersonToCompany"
    ></v-btn>
    <v-simple-table fixed-header>
      <template v-slot:default>
        <thead>
          <tr>
            <th
              class="text-center"
              v-for="(header, h) in headers"
              :key="h"
              v-text="header"
            ></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in personItems" :key="i">
            <td class="text-center" v-text="item.name"></td>
            <td class="text-center" v-text="item.nameL"></td>
            <!-- <td v-text="item"></td>
            <td v-text="items.status"></td>
            <td v-text="items.date"></td>
            <td v-text="items.jobTitle"></td>
            <td v-text="items.job"></td>
            <td v-text="items.language"></td>
            <td v-text="items.supervisor"></td>
            <td v-text="items.mobile"></td>
            <td v-text="items.email"></td>
            <td v-text="items.phone"></td> -->
            <td class="text-center">
              <v-btn
                small
                @click="createTicket(item.id)"
                v-text="$t('add')"
              ></v-btn>
            </td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>

<script>
import { mapFields } from "vuex-map-fields";

export default {
  name: "persons",

  data() {
    return {
      headers: [
        this.$t("name"),
        this.$t("nameL"),
        // this.$t("status"),
        // this.$t("date"),
        // this.$t("jobTitle"),
        // this.$t("job"),
        // this.$t("language"),
        // this.$t("supervisor"),
        // this.$t("mobile"),
        // this.$t("email"),
        // this.$t("phone"),
        this.$t("createTicket")
      ]
    };
  },
  computed: {
    ...mapFields("company", ["personItems"]),
    ...mapFields("company", ["id"])
  },

  methods: {
    addPersonToCompany() {
      //
    },
    createTicket(personId) {
      this.$router.push({
        name: "new-ticket-by-client",
        query: { person: personId, company: this.id }
      });
    }
  }
};
</script>

<style></style>
