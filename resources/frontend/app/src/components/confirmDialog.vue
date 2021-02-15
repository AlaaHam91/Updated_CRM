<template>
  <v-dialog :value="isOpen" max-width="290">
    <v-card>
      <v-card-title class="headline" v-text="title"></v-card-title>
      <v-card-text v-text="msg"></v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="$emit('confirm')">{{ $t("yes") }}</v-btn>
        <v-btn text @click="$emit('cancel')">{{ $t("cancel") }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "confirm-dialog",
  props: ["isOpen", "title", "msg"]
};
</script>

<style></style>
