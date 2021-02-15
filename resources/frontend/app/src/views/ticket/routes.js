const ticketByClient = () => import("./ticket/create/by-client.vue");
const ticketByWizard = () => import("./ticket/create/by-wizard.vue");
const ticketByProject = () => import("./ticket/create/by-project.vue");
const dailyReportShow = () => import("./write-report/daily/show.vue");
const dailyReportIndex = () => import("./write-report/daily/index.vue");
const dailyReportWizard = () =>
  import("./write-report/daily-by-wizard/show.vue");
const dailyReportWizardIndex = () =>
  import("./write-report/daily-by-wizard/index.vue");
const ticket = () => import("./ticket/view/show.vue");
const ticketIndex = () => import("./ticket/view/index.vue");
const ticketSearch = () => import("./search/show.vue");
const meeting = () => import("./meeting/show");

export default [
  {
    path: "/tickets/new-ticket-by-client",
    name: "new-ticket-by-client",
    component: ticketByClient,
    meta: {
      title: "newTicketByClient",
      type: "navItem",
      section: "Ticket_by_client",
      requireAuth: true
    }
  },
  {
    path: "/tickets/new-ticket-by-wizard",
    name: "new-ticket-by-wizard",
    component: ticketByWizard,
    meta: {
      title: "newTicketByWizard",
      type: "navItem",
      section: "Ticket_by_wizard",
      requireAuth: true
    }
  },
  {
    path: "/tickets/new-ticket-by-project",
    name: "new-ticket-by-project",
    component: ticketByProject,
    meta: {
      title: "newTicketByProject",
      type: "navItem",
      section: "Ticket_by_project",
      requireAuth: true
    }
  },
  {
    path: "/tickets/daily-report",
    name: "daily-report-show",
    component: dailyReportShow,
    meta: {
      title: "dailyReport",
      type: "show",
      section: "Ticket_daily_report",
      requireAuth: true
    }
  },
  {
    path: "/tickets/daily-report-index",
    name: "daily-report-index",
    component: dailyReportIndex,
    meta: {
      title: "writeDailyReport",
      type: "navItem",
      section: "Ticket_daily_report",
      requireAuth: true
    }
  },
  {
    path: "/tickets/daily-report-by-wizard",
    name: "daily-report-wizard",
    component: dailyReportWizard,
    meta: {
      title: "dailyReport",
      type: "show",
      section: "Ticket_daily_report",
      requireAuth: true
    }
  },
  {
    path: "/tickets/daily-report-by-wizard-index",
    name: "daily-report-wizard-index",
    component: dailyReportWizardIndex,
    meta: {
      title: "writeDailyReportsWizard",
      type: "navItem",
      section: "Ticket_daily_report",
      requireAuth: true
    }
  },
  {
    path: "/tickets/ticket/:id",
    name: "ticket-show",
    component: ticket,
    meta: {
      title: "ticket",
      type: "show",
      section: "Tickets",
      requireAuth: true
    }
  },
  {
    path: "/tickets/index",
    name: "ticket-index",
    component: ticketIndex,
    meta: {
      title: "tickets",
      type: "navItem",
      section: "Tickets",
      requireAuth: true
    }
  },
  {
    path: "/tickets/search",
    name: "ticket-search",
    component: ticketSearch,
    meta: {
      title: "search",
      type: "navItem",
      section: "Tickets",
      requireAuth: true
    }
  },
  {
    path: "/tickets/create-meeting",
    name: "create-meeting",
    component: meeting,
    meta: {
      title: "meetingCreate",
      type: "navItem",
      section: "Meeting",
      requireAuth: true
    }
  }
];
