<template>
  <div class="d-flex flex-row align-center">
    <v-btn x-small icon @click="clickInput">
      <v-icon>mdi-calendar</v-icon>
    </v-btn>
    <input
      v-model="displayDate"
      @click="clickInput"
      type="text"
      class="cellInput cursor-pointer"
      autocomplete="off"
      spellcheck="false"
      readonly
    />
    <v-btn
      v-if="displayDate && !readonly"
      color="error"
      @click="clearDates"
      x-small
      icon
    >
      <v-icon>mdi-close</v-icon>
    </v-btn>
    <v-dialog v-model="dialog" width="290px">
      <v-date-picker
        v-model="date"
        @input="setGregorian"
        :locale="$i18n.locale"
      ></v-date-picker>
    </v-dialog>
  </div>
</template>

<script>
// import moment from "moment";
// moment.locale("en-SA");

export default {
  name: "date-picker",
  props: ["value", "readonly"],

  data() {
    return {
      displayDate: null,
      dialog: false,
      date: null
    };
  },

  methods: {
    clickInput() {
      if (!this.readonly) this.dialog = true;
    },
    setGregorian(value) {
      this.date = this.$moment(value).format("YYYY-MM-DD");
      this.displayDate = this.date;
      this.dialog = false;
      this.$emit("input", this.date);
    },
    clearDates() {
      this.date = null;
      this.displayDate = null;
      this.$emit("input", this.date);
    }
  },

  watch: {
    value: {
      handler: function(val) {
        if (!val) return;
        this.date = val;
        this.displayDate = this.date;
      },
      deep: true,
      immediate: true
    }
  }
};
</script>

<style></style>
