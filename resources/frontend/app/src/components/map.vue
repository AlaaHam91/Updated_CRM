<template>
  <v-card width="100%" flat>
    <v-card-text>
      <div class="map-container">
        <GmapMap
          :center="mapCenter"
          :zoom="7"
          map-type-id="terrain"
          style="width: 100%; height: 100%"
          @center_changed="updateCenter"
        >
          <GmapMarker
            :position="location"
            :draggable="true"
            @dragend="setLocation($event.latLng)"
          />
        </GmapMap>
        <div class="red--text" v-for="e in errorMessages" :key="e">
          {{ e }}
        </div>
      </div>
    </v-card-text>
    <v-card-actions class="d-flex justify-center">
      <v-btn rounded @click="addMarker">
        <v-icon>mdi-map-marker-plus-outline</v-icon>
        {{ $t("addMarker") }}
      </v-btn>
      <v-btn rounded @click="deleteMarker">
        <v-icon>mdi-map-marker-off-outline</v-icon>
        {{ $t("removeMarker") }}
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "gMap",

  props: {
    errorMessages: Array,
    center: Object,
    value: undefined
  },

  data() {
    return {
      location: null,
      mapCenter: { lat: 24.774265, lng: 46.738586 }
    };
  },

  methods: {
    setLocation(evnt) {
      this.location = { lat: evnt.lat(), lng: evnt.lng() };
      this.$emit("input", this.location);
    },
    updateCenter(evnt) {
      this.mapCenter = { lat: evnt.lat(), lng: evnt.lng() };
    },
    addMarker() {
      this.location = JSON.parse(JSON.stringify(this.mapCenter));
      this.$emit("input", this.location);
    },
    deleteMarker() {
      this.location = null;
    }
  },

  created() {
    if (this.center) this.mapCenter = JSON.parse(JSON.stringify(this.center));
  },

  watch: {
    value: {
      handler: function(val) {
        if (!val) return;
        this.selectedValue = val;
        this.location = val;
        this.mapCenter = val;
      },
      deep: true,
      immediate: true
    }
  }
};
</script>

<style scoped>
.map-container {
  width: 100%;
  height: 400px;
  background-color: gray;
}
</style>
