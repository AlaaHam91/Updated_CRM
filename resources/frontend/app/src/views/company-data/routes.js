const branch = () => import("./branch/show.vue");
const branches = () => import("./branch/index.vue");
const management = () => import("./management/show.vue");
const managements = () => import("./management/index.vue");
const department = () => import("./department/show.vue");
const departments = () => import("./department/index.vue");
const country = () => import("./country/show.vue");
const countries = () => import("./country/index.vue");
const hierarchy = () => import("./hierarchy/show.vue");
const hierarchies = () => import("./hierarchy/index.vue");
export default [
  {
    path: "/control-panel/company-data/branch-index/:id",
    name: "branch-show",
    component: branch,
    meta: {
      title: "branch",
      type: "show",
      section: "Cp_cpData_branch",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/branch-index",
    name: "branch-index",
    component: branches,
    meta: {
      title: "branches",
      type: "index",
      section: "Cp_cpData_branch",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/management-index/:id",
    name: "management-show",
    component: management,
    meta: {
      title: "management",
      type: "show",
      section: "Cp_cpData_management",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/management-index",
    name: "management-index",
    component: managements,
    meta: {
      title: "managements",
      type: "index",
      section: "Cp_cpData_management",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/department-index/:id",
    name: "department-show",
    component: department,
    meta: {
      title: "department",
      type: "show",
      section: "Cp_cpData_department",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/department-index",
    name: "department-index",
    component: departments,
    meta: {
      title: "departments",
      type: "index",
      section: "Cp_cpData_department",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/country-index/:id",
    name: "country-show",
    component: country,
    meta: {
      title: "country",
      type: "show",
      section: "Cp_coding_country",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/country-index",
    name: "country-index",
    component: countries,
    meta: {
      title: "countries",
      type: "index",
      section: "Cp_coding_country",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/hierarchy-index/:id",
    name: "hierarchy-show",
    component: hierarchy,
    meta: {
      title: "hierarchy",
      type: "show",
      section: "Cp_cpData_hierarchy",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/company-data/hierarchy-index",
    name: "hierarchy-index",
    component: hierarchies,
    meta: {
      title: "hierarchies",
      type: "index",
      section: "Cp_cpData_hierarchy",
      requireAuth: true
    }
  }
];
