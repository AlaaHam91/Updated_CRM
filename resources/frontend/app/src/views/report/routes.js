const calendarReport = () => import("./calendar/index");

export default [
  {
    path: "/report/calendar-report",
    name: "calnedar-report",
    component: calendarReport,
    meta: {
      title: "calendarReport",
      type: "index",
      section: "T_reports",
      requireAuth: true
    }
  }
];
