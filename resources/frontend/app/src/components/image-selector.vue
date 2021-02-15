<template>
  <div class="ma-4 d-flex justify-center align-center">
    <div id="image-container">
      <v-avatar color="inputBack" class="elevation-3" :size="size">
        <v-img v-if="imagePreview" :src="imagePreview"></v-img>
        <v-btn :disabled="disabled == true" v-else @click="addImage" icon>
          <v-icon>mdi-camera-plus</v-icon>
        </v-btn>
      </v-avatar>
      <input
        type="file"
        style="display:none;"
        id="imageInput"
        @change="onImageChange"
        accept="image/*"
      />
      <v-btn
        class="image-control"
        v-if="value"
        :disabled="disabled == true"
        icon
        small
        @click="removeImage"
      >
        <v-icon color="red">mdi-close</v-icon>
      </v-btn>
    </div>
    <div style="min-height: 2rem; width: 100%;">
      <div class="red--text text-body-2" v-for="e in errorMessages" :key="e">
        {{ e }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "profile-image",
  props: {
    errorMessages: Array,
    value: null,
    disabled: Boolean,
    size: { type: undefined, default: "5rem" }
  },
  data() {
    return {
      file: null
    };
  },
  methods: {
    onImageChange(e) {
      this.file = e.target.files[0];
      this.$emit("input", e.target.files[0]);
    },
    removeImage() {
      this.file = null;
      this.$emit("input", null);
    },
    addImage() {
      document.getElementById("imageInput").click();
    }
  },
  computed: {
    imagePreview() {
      if (typeof this.value == "string") return this.value;
      else if (this.file) return URL.createObjectURL(this.file);
      else return null;
    }
  }
};
</script>

<style scoped>
#image-container {
  position: relative;
  margin-left: 2rem;
}

.image-control {
  position: absolute;
  top: 90%;
  left: -0.5rem;
}
</style>
