<template>
  <v-app>
    <side-nav v-if="isLogged" />
    <app-bar v-if="isLogged" />
    <v-main>
      <notification />
      <v-container
        fluid
        style="background-color: #F5F5F5; min-height: 90vh;"
        class="pa-0 ma-0"
      >
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import appBar from "./components/app-bar";
import notification from "./components/notification";
import sideNav from "./components/side-nav";
import axios from "@/plugins/api";

export default {
  name: "App",

  components: {
    notification,
    "app-bar": appBar,
    "side-nav": sideNav
  },

  data: () => ({
    //
  }),
  created() {
    if (this.$store.state.auth.token) {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.auth.token;
    }
    if (localStorage.getItem("locale"))
      this.$i18n.locale = localStorage.getItem("locale");
  },

  computed: {
    isLogged() {
      return this.$store.getters["auth/isSignedIn"];
    }
  }
};
</script>
<style lang="scss">
@import "./sass/app.scss";
</style>
