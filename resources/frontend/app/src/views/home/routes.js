const home = () => import("./home.vue");

export default [
  {
    path: "/home",
    name: "home",
    component: home,
    meta: {
      title: "home",
      type: "definition",
      requireAuth: true
    }
  }
];
