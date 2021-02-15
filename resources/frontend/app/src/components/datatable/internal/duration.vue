<template>
  <div class="d-flex">
    <input
      v-model="hours"
      type="number"
      min="0"
      max="99"
      step="01"
      class="cellInput d-inline"
      autocomplete="off"
      spellcheck="false"
      @change="formatHours"
    />
    <span>:</span>
    <input
      v-model="minutes"
      min="0"
      max="59"
      step="01"
      type="number"
      class="cellInput d-inline"
      autocomplete="off"
      spellcheck="false"
      @change="formatMinutes"
    />
    <v-icon small>mdi-camera-timer</v-icon>
  </div>
</template>

<script>
export default {
  name: "duration-cell",

  props: {
    value: undefined
  },

  data() {
    return {
      hours: "00",
      minutes: "00"
    };
  },

  methods: {
    emit() {
      if (this.hours >= 0 && this.minutes >= 0)
        this.$emit("input", `${this.hours}:${this.minutes}`);
    },
    formatHours() {
      this.hours = ("0" + this.hours).slice(-2);
    },
    formatMinutes() {
      this.minutes = ("0" + this.minutes).slice(-2);
    }
  },

  created() {
    if (this.value) {
      let parts = this.value.split(":", 2);
      if (parts.length !== 2)
        console.log(
          "%c Error in data-table duration cell: format should have ':'",
          "background: Brown;"
        );
      else {
        this.hours = ("0" + parts[0]).slice(-2);
        this.minutes = ("0" + parts[1]).slice(-2);
      }
    }
  },

  watch: {
    hours(val) {
      if (val) this.emit();
    },
    minutes(val) {
      if (val) this.emit();
    }
  }
};
</script>

<style>
.cellInput[type="number"]::-webkit-outer-spin-button,
.cellInput[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
.cellInput[type="number"] {
  -moz-appearance: textfield;
}

.cellInput[type="number"] {
  text-align: center;
}
</style>
