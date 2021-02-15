const adminDivision = () => import("./administrative-division/show.vue");
const adminDivisions = () => import("./administrative-division/index.vue");
const requiredEducation = () => import("./required-education/show.vue");
const requiredEducations = () => import("./required-education/index.vue");
const finishType = () => import("./finish-types/show.vue");
const finishTypes = () => import("./finish-types/index.vue");
const requiredAction = () => import("./required-action/show.vue");
const requiredActions = () => import("./required-action/index.vue");
const activity = () => import("./activities/show");
const activities = () => import("./activities/index");
const project = () => import("./project/show");
const projects = () => import("./project/index");
const communicationMethod = () => import("./communication-methods/show");
const communicationMethods = () => import("./communication-methods/index");

export default [
  {
    path: "/control-panel/administrative-division-index/:id",
    name: "administrative-division-show",
    component: adminDivision,
    meta: {
      title: "administrativeDivision",
      type: "show",
      section: "Cp_coding_ad",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/administrative-division-index",
    name: "administrative-division-index",
    component: adminDivisions,
    meta: {
      title: "administrativeDivisions",
      _type: "index",
      get type() {
        return this._type;
      },
      set type(value) {
        this._type = value;
      },
      section: "Cp_coding_ad",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/required-education-index/:id",
    name: "required-education-show",
    component: requiredEducation,
    meta: {
      title: "requiredEducation",
      type: "show",
      section: "Pg_req_education",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/required-education-index",
    name: "required-education-index",
    component: requiredEducations,
    meta: {
      title: "requiredEducations",
      type: "index",
      section: "Pg_req_education",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/finish-type-index/:id",
    name: "finish-type-show",
    component: finishType,
    meta: {
      title: "finishType",
      type: "show",
      section: "Cp_other_finishType",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/finish-type-index",
    name: "finish-type-index",
    component: finishTypes,
    meta: {
      title: "finishTypes",
      type: "index",
      section: "Cp_other_finishType",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/required-action-index/:id",
    name: "required-action-show",
    component: requiredAction,
    meta: {
      title: "requiredAction",
      type: "show",
      section: "Cp_other_reqAction",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/required-action-index",
    name: "required-action-index",
    component: requiredActions,
    meta: {
      title: "requiredActions",
      type: "index",
      section: "Cp_other_reqAction",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/activity-index/:id",
    name: "activity-show",
    component: activity,
    meta: {
      title: "activity",
      type: "show",
      section: "Pg_activities",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/activity-index",
    name: "activity-index",
    component: activities,
    meta: {
      title: "activities",
      type: "index",
      section: "Pg_activities",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/project-index/:id",
    name: "project-show",
    component: project,
    meta: {
      title: "project",
      type: "show",
      section: "Cp_other_project",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/project-index",
    name: "project-index",
    component: projects,
    meta: {
      title: "projects",
      type: "index",
      section: "Cp_other_project",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/communication-method-index/:id",
    name: "communication-method-show",
    component: communicationMethod,
    meta: {
      title: "communicationMethod",
      type: "show",
      section: "Pg_comm_methods",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/communication-method-index",
    name: "communication-method-index",
    component: communicationMethods,
    meta: {
      title: "communicationMethods",
      type: "index",
      section: "Pg_comm_methods",
      requireAuth: true
    }
  }
];
