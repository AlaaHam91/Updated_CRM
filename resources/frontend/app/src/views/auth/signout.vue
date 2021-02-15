<template>
  <div>
    <div
      v-if="loading"
      class="d-flex justify-center align-center"
      style="height: 80vh;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
  </div>
</template>

<script>
export default {
  name: "signout",

  data() {
    return {
      loading: false
    };
  },

  methods: {
    signout() {
      this.loading = true;

      this.$store
        .dispatch("auth/signout")
        .then(() => {
          //
        })
        .catch(() => {
          //
        })
        .finally(() => {
          this.loading = false;
          this.$router.push({ name: "signin" });
        });
    }
  },
  mounted() {
    this.signout();
  }
};
</script>

<style></style>
