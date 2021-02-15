const signin = () => import("./signin");
const signup = () => import("./signup.vue");
const signout = () => import("./signout.vue");
const profile = () => import("./profile/profile.vue");
const page403 = () => import("./page403.vue");
const changePass = () => import("./changePassword.vue");

export default [
  {
    path: "/auth/sign-in",
    name: "signin",
    component: signin,
    meta: {
      title: "signin",
      type: "show",
      requireAuth: false
    }
  },
  {
    path: "/auth/sign-up",
    name: "signup",
    component: signup,
    meta: {
      title: "signup",
      type: "show",
      requireAuth: false
    }
  },
  {
    path: "/auth/sign-out",
    name: "signout",
    component: signout,
    meta: {
      title: "signout",
      type: "show",
      requireAuth: false
    }
  },
  {
    path: "/auth/user-profile",
    name: "user-profile",
    component: profile,
    meta: {
      title: "userProfile",
      type: "show",
      requireAuth: true
    }
  },
  {
    path: "/auth/change-password",
    name: "change-pass",
    component: changePass,
    meta: {
      title: "changePass",
      type: "show",
      requireAuth: true
    }
  },
  {
    path: "/error-403",
    name: "error-403",
    component: page403,
    meta: {
      title: "error",
      type: "show",
      requireAuth: false
    }
  }
];
