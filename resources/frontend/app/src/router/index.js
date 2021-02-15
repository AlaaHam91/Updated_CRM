import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);

import i18n from "@/plugins/i18n";
import store from "@/store/index.js";

import adminRoutes from "./../views/control-panel/routes";
import homeRoutes from "./../views/home/routes";
import peopleSettings from "./../views/people-settings/routes";
import peopleIndex from "./../views/people-index/routes";
import componentsRoutes from "./../views/components-examples/routes";
import page404 from "./../views/auth/page404";
import auth from "./../views/auth/routes";
import companyData from "./../views/company-data/routes";
import users from "./../views/users/routes";
import filesManagment from "./../views/files-managment/routes";
import ticket from "./../views/ticket/routes";
import report from "./../views/report/routes";

const routes = [
  {
    path: "/",
    redirect: { name: "ticket-index" }
  },
  {
    path: "/control-panel",
    name: "control-panel",
    requireAuth: true
  },
  ...homeRoutes,
  ...adminRoutes,
  ...peopleSettings,
  ...peopleIndex,
  ...componentsRoutes,
  ...auth,
  ...companyData,
  ...users,
  ...filesManagment,
  ...ticket,
  ...report,
  {
    path: "*",
    name: "page404",
    component: page404
  }
];

const router = new VueRouter({
  mode: "history",
  base: "/",
  routes
});

router.beforeEach(async (to, from, next) => {
  store.commit("ui/startBar");

  if (to.meta.section) {
    if (to.meta.requireAuth && !store.getters["auth/isSignedIn"])
      return next({ name: "signin" });
    if (await store.getters["auth/can"](to.meta.section, "Open")) return next();
    else return next({ name: "error-403" });
  }
  return next();
});

const DEFAULT_TITLE = "Motkamel CRM";

router.afterEach(to => {
  store.commit("ui/stopBar");
  Vue.nextTick(() => {
    document.title = i18n.t(to.meta.title) || DEFAULT_TITLE;
  });
  window.scrollTo(0, 0);
});

export default router;
