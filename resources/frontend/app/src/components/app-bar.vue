<template>
  <v-app-bar
    color="#37474F"
    flat
    :clipped-left="$vuetify.rtl ? false : true"
    :clipped-right="$vuetify.rtl"
    app
    dark
    dense
  >
    <v-btn @click="toggleDrawer" icon>
      <v-icon>mdi-menu</v-icon>
    </v-btn>
    <router-link :to="{ name: 'ticket-index' }">
      <v-img
        contain
        :src="logo"
        max-width="4rem"
        :height="this.$vuetify.application.top - 10"
      ></v-img>
    </router-link>

    <v-spacer></v-spacer>

    <!--  Modules menu -->
    <v-menu
      transition="slide-y-transition"
      offset-y
      v-if="isSignedIn"
      open-on-hover
    >
      <template v-slot:activator="{ on: menu, attrs }">
        <v-btn class="mx-2" v-bind="attrs" v-on="{ ...menu }" icon>
          <v-icon>mdi-lan</v-icon>
        </v-btn>
      </template>
      <v-list dense>
        <template v-for="(item, i) in modulesMenu">
          <v-divider v-if="i > 0" :key="i + 's'"></v-divider>
          <v-list-item
            :key="i + 'm'"
            :to="{ name: item.to ? item.to : undefined }"
          >
            <v-list-item-icon v-if="item.icon" :key="i + 'i'">
              <v-icon v-html="item.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="item.label"> </v-list-item-title>
          </v-list-item>
        </template>
      </v-list>
    </v-menu>

    <!-- lang menu -->
    <v-menu transition="slide-y-transition" offset-y>
      <template v-slot:activator="{ on: menu, attrs }">
        <v-btn small class="mx-2" v-bind="attrs" v-on="{ ...menu }" text>
          {{ language.label }} <v-icon>mdi-chevron-down</v-icon>
        </v-btn>
      </template>
      <v-list dense>
        <template v-for="(item, i) in languageMenu">
          <v-divider v-if="i > 0" :key="i + 's'"></v-divider>
          <v-list-item :key="i + 'l'" @click="setLang(item.value)">
            <v-list-item-title v-text="item.label"> </v-list-item-title>
          </v-list-item>
        </template>
      </v-list>
    </v-menu>

    <!-- user menu -->
    <v-menu transition="slide-y-transition" offset-y v-if="isSignedIn">
      <template v-slot:activator="{ on: menu, attrs }">
        <v-btn small class="mx-2" v-bind="attrs" v-on="{ ...menu }" text>
          <span
            class="mx-2 text-caption"
            v-text="$i18n.locale == 'en' ? nameL : name"
          ></span>
          <v-avatar size="30" color="grey lighten-5">
            <img :src="image" v-if="image" />
          </v-avatar>
          <v-icon>mdi-chevron-down</v-icon>
        </v-btn>
      </template>
      <v-list dense>
        <template v-for="(item, i) in userMenu">
          <v-divider v-if="i > 0" :key="i + 's'"></v-divider>
          <v-list-item
            :key="i + 'm'"
            :to="{ name: item.to ? item.to : undefined }"
          >
            <v-list-item-icon v-if="item.icon" :key="i + 'i'">
              <v-icon v-html="item.icon"></v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="item.label"> </v-list-item-title>
          </v-list-item>
        </template>
      </v-list>
    </v-menu>
    <v-progress-linear
      :active="loading"
      indeterminate
      absolute
      top
      color="light-blue"
      height="3px"
    ></v-progress-linear>
  </v-app-bar>
</template>

<script>
import { mapState } from "vuex";
export default {
  name: "app-bar",
  data() {
    return {
      logo: require("./../assets/brand.png"),
      userMenu: [
        {
          label: this.$t("profile"),
          icon: "mdi-account-box-outline",
          to: "user-profile"
        },
        { label: this.$t("changePass"), icon: "mdi-cog", to: "change-pass" },
        { label: this.$t("signout"), icon: "mdi-power", to: "signout" }
      ],
      modulesMenu: [
        {
          label: this.$t("crmSystem"),
          icon: "mdi-account-supervisor-circle",
          color: "yellow",
          to: "home"
        },
        {
          label: this.$t("controlPanel"),
          icon: "mdi-hammer-screwdriver",
          color: "yellow",
          to: "control-panel"
        }
      ],
      languageMenu: [
        { label: this.$t("arabic"), value: "ar" },
        { label: this.$t("english"), value: "en" }
      ],
      language: { label: this.$t("english"), value: "en" }
    };
  },
  methods: {
    setLang(lang) {
      this.$i18n.locale = lang;
      this.$vuetify.rtl = lang == "ar" ? true : false;
      localStorage.setItem("locale", lang);
      localStorage.setItem("rtl", this.$vuetify.rtl);

      this.language = this.languageMenu.find(e => e.value === lang);
      location.reload();
    },
    toggleDrawer() {
      this.$store.commit("ui/toggleDrawer");
    }
  },

  computed: {
    ...mapState("auth", {
      name: state => state.user.name,
      nameL: state => state.user.nameL,
      image: state => state.user.image
    }),
    isSignedIn() {
      return this.$store.getters["auth/isSignedIn"];
    },
    loading() {
      return this.$store.state.ui.appLoadingBar;
    }
  },

  created() {
    if (localStorage.getItem("locale")) {
      this.$i18n.locale = localStorage.getItem("locale");
      this.language = this.languageMenu.find(
        e => e.value === this.$i18n.locale
      );
    }
    if (localStorage.getItem("rtl"))
      this.$vuetify.rtl = localStorage.getItem("rtl") == "true" ? true : false;
  }
};
</script>

<style scoped>
.v-app-bar >>> .v-toolbar__content {
  border-bottom: 2px solid #2196f3;
}
</style>
