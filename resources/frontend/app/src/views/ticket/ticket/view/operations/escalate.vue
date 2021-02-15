<template>
  <v-card class="" elevation="">
    <v-card-title
      class="headline grey lighten-2"
      v-text="$t('escalate')"
    ></v-card-title>
    <v-card-text class="pt-2">
      <v-row v-for="(item, i) in messages" :key="i" no-gutters>
        <v-col cols="12" md="12">
          <v-alert outlined type="error">{{ item }} </v-alert>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="12">
          <div class="text-body-1 black--text mb-2" v-text="$t('note')"></div>
          <v-textarea rows="2" v-model="note" dense outlined></v-textarea>
        </v-col>
      </v-row>

      <v-row>
        <v-col>
          <div class="text-body-1 black--text mb-2" v-text="$t('files')"></div>
          <uploader v-model="filesNames" />
        </v-col>
      </v-row>
    </v-card-text>
    <v-card-actions class="d-flex justify-end">
      <v-btn v-text="$t('cancel')" @click="$emit('close')"> </v-btn>
      <v-btn v-text="$t('save')" color="success" @click="save"> </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import uploader from "@/components/uploader.vue";
// import search from "./../../../components/search";
import ticketApi from "@/services/api/ticket/ticket.api.js";
// import { required } from "vuelidate/lib/validators";

export default {
  name: "escalate",

  props: {
    ticketId: { type: Number, required: true }
  },

  components: {
    uploader
  },

  data() {
    return {
      note: null,
      filesNames: [],
      messages: []
    };
  },

  methods: {
    async save() {
      let data = {
        note: this.note
      };
      this.filesNames.forEach((item, i) => {
        data[`files[${i}]`] = item;
      });

      ticketApi
        .escalate(this.ticketId, data)
        .then(() => this.$emit("close"))
        .catch(err => (this.messages = err));
    }
  },

  created() {}
};
</script>

<style></style>
