import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import i18n from "./plugins/i18n";

Vue.config.productionTip = false;

import Vuelidate from "vuelidate";
Vue.use(Vuelidate);

import Notification from "vue-notification";
Vue.use(Notification);

import Moment from "moment/src/moment";
Moment.locale("en");
Object.defineProperty(Vue.prototype, "$moment", { value: Moment });

import * as VueGoogleMaps from "vue2-google-maps";
Vue.use(VueGoogleMaps, {
  load: {
    key: "",
    // key: '',
    libraries: "places"
  },
  installComponents: true
});

import checkPermission from "./mixins/can.vue";
Vue.mixin(checkPermission);

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: h => h(App)
}).$mount("#app");
