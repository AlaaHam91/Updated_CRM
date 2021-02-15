const jobType = () => import("./job-type/show.vue");
const jobTypes = () => import("./job-type/index.vue");
const job = () => import("./job/show.vue");
const jobs = () => import("./job/index.vue");
const managerialLevel = () => import("./managerial-level/show.vue");
const managerialLevels = () => import("./managerial-level/index.vue");
const branchesDepartmentsRoles = () =>
  import("./branches-departments-permissions/show.vue");
const user = () => import("./user/show.vue");
const users = () => import("./user/index.vue");
const eventLog = () => import("./event-log/index.vue");
const userChanges = () => import("./user-changes/index.vue");
const changesShow = () => import("./user-changes/show.vue");
const userPermission = () => import("./user-permission/show.vue");
const userPermissions = () => import("./user-permission/index.vue");

export default [
  {
    path: "/control-panel/users/job-type-index/:id",
    name: "job-type-show",
    component: jobType,
    meta: {
      title: "jobType",
      type: "show",
      section: "Cp_jobType",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/job-type-index",
    name: "job-type-index",
    component: jobTypes,
    meta: {
      title: "jobTypes",
      type: "index",
      section: "Cp_jobType",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/job-index/:id",
    name: "job-show",
    component: job,
    meta: {
      title: "job",
      type: "show",
      section: "Cp_user_job",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/job-index",
    name: "job-index",
    component: jobs,
    meta: {
      title: "jobs",
      type: "index",
      section: "Cp_user_job",
      requireAuth: true
    }
  },

  {
    path: "/control-panel/users/managerial-level-index/:id",
    name: "managerial-level-show",
    component: managerialLevel,
    meta: {
      title: "managerialLevel",
      type: "show",
      section: "Cp_userRole",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/managerial-level-index",
    name: "managerial-level-index",
    component: managerialLevels,
    meta: {
      title: "managerialLevels",
      type: "index",
      section: "Cp_userRole",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/branches-departments-role-index",
    name: "branches-departments-roles",
    component: branchesDepartmentsRoles,
    meta: {
      title: "branchesDepartmentsRoles",
      type: "index",
      section: "Cp_user_departRole",
      requireAuth: true
    }
  },

  {
    path: "/control-panel/users/user-index/:id",
    name: "user-show",
    component: user,
    meta: {
      title: "user",
      type: "show",
      section: "Cp_user_user",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/user-index",
    name: "user-index",
    component: users,
    meta: {
      title: "users",
      type: "index",
      section: "Cp_user_user",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/event-log-index",
    name: "event-log-index",
    component: eventLog,
    meta: {
      title: "eventLog",
      type: "index",
      section: "Cp_user_eventLog",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/user-change-index",
    name: "user-changes-index",
    component: userChanges,
    meta: {
      title: "userChanges",
      type: "index",
      section: "Cp_userChange",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/show-changes",
    name: "show-changes",
    component: changesShow,
    props: true,
    meta: {
      title: "profileChanges",
      type: "show"
    }
  },
  {
    path: "/control-panel/users/user-permission-index/:id",
    name: "user-permission-show",
    component: userPermission,
    meta: {
      title: "userPermission",
      type: "show",
      section: "Cp_userRole",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/users/user-permission-index",
    name: "user-permission-index",
    component: userPermissions,
    meta: {
      title: "userPermissions",
      type: "index",
      section: "Cp_userRole",
      requireAuth: true
    }
  }
];
