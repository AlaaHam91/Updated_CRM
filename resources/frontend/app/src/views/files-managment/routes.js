const employeeFiles = () => import("./employee/show.vue");
const employeesFiles = () => import("./employee/index.vue");
const customerFiles = () => import("./customer/show.vue");

export default [
  {
    path: "/control-panel/file-managment/employee-files-index",
    name: "employees-files-index",
    component: employeesFiles,
    meta: {
      title: "employeesFiles",
      type: "index",
      section: "Cp_fM_EmployeeFile",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/file-managment/employee-files-index/:id",
    name: "employee-files-show",
    component: employeeFiles,
    meta: {
      title: "employeeFiles",
      type: "show",
      section: "Cp_fM_EmployeeFile",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/file-managment/customer-files-index",
    name: "customer-files",
    component: customerFiles,
    meta: {
      title: "customerFiles",
      type: "index",
      section: "Cp_fM_CustomerFile"
    }
  }
];
