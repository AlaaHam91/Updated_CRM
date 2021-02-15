const dataTable = () => import("./data-table.vue");

export default [
  {
    path: "/component-examples/data-table",
    name: "data-table-example",
    component: dataTable,
    meta: {
      title: "user",
      type: "show",
      requireAuth: true
    }
  }
];
