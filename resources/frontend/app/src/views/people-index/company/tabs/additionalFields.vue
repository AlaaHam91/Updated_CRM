<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" md="4">
        <user-fields
          :types="additionalFieldsTypes"
          v-model="additionalFieldsValues"
          :touch="touchAdditional"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapFields } from "vuex-map-fields";

import userFields from "@/components/user-fields-viewer.vue";

export default {
  name: "additional-fields",

  components: {
    "user-fields": userFields
  },

  computed: {
    ...mapFields("company", ["additionalFieldsTypes"]),
    ...mapFields("company", ["additionalFieldsValues"]),
    ...mapFields("company", ["touchAdditional"])
  }
};
</script>

<style></style>
