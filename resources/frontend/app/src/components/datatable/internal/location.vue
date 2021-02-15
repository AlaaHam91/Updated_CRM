<template>
  <div class="d-flex flex-row align-center">
    <v-btn x-small icon @click="clickInput">
      <v-icon>mdi-map-marker</v-icon>
    </v-btn>
    <input
      :value="selectedValue"
      @click="clickInput"
      type="text"
      class="cellInput cursor-pointer"
      autocomplete="off"
      spellcheck="false"
      readonly
    />
    <v-btn
      v-if="selectedValue && !readonly"
      color="error"
      @click="clearData"
      x-small
      icon
    >
      <v-icon>mdi-close</v-icon>
    </v-btn>
    <v-dialog
      v-model="dialog"
      scrollable
      :fullscreen="$vuetify.breakpoint.mobile"
      :overlay="false"
      max-width="500px"
      transition="dialog-transition"
    >
      <v-card>
        <v-card-text>
          <g-map :center="location ? location : center" v-model="location" />
        </v-card-text>
        <v-card-actions class="d-flex justify-space-between">
          <v-btn
            rounded
            color="success"
            v-text="$t('ok')"
            @click="setLocation"
            :disabled="!location"
          >
          </v-btn>
          <v-btn rounded v-text="$t('cancel')" @click="dialog = false"> </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import map from "./../../map.vue";

export default {
  name: "location-input",
  props: {
    value: undefined,
    center: {
      type: Object,
      default: function() {
        return { lat: 24.774265, lng: 46.738586 };
      }
    },
    readonly: { type: Boolean, default: false }
  },

  components: {
    "g-map": map
  },

  data() {
    return {
      selectedValue: null,
      dialog: false,
      location: null
    };
  },

  methods: {
    clickInput() {
      if (!this.readonly) this.dialog = true;
    },
    clearData() {
      this.selectedValue = null;
      this.$emit("input", null);
    },
    setLocation() {
      this.$emit("input", this.location);
      this.dialog = false;
    }
  },

  watch: {
    value: {
      handler: function(val) {
        if (!val) return;
        this.selectedValue = `${val.lat}, ${val.lng}`;
        this.location = val;
      },
      deep: true,
      immediate: true
    }
  }
};
</script>

<style></style>
