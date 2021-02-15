const company = () => import("./company/show.vue");
const companies = () => import("./company/index.vue");
const acquaintance = () => import("./acquaintance/show.vue");
const acquaintanceIndex = () => import("./acquaintance/index.vue");
const phonebook = () => import("./phonebook/show.vue");
const phonebookIndex = () => import("./phonebook/index.vue");
const contact = () => import("./contact/show.vue");
const contactIndex = () => import("./contact/index.vue");
const potentialAgent = () => import("./potential-agent/show.vue");
const potentialAgentIndex = () => import("./potential-agent/index.vue");
const agent = () => import("./agent/show.vue");
const agentIndex = () => import("./agent/index.vue");
const visitorChanges = () => import("./visitor-changes/index.vue");
const customerChanges = () => import("./customer-changes/index.vue");

export default [
  {
    path: "/people-index/company-index/:id",
    name: "company-show",
    component: company,
    meta: {
      title: "company",
      type: "show",
      section: "Pg_company",
      requireAuth: true
    }
  },
  {
    path: "/people-index/company-index",
    name: "company-index",
    component: companies,
    meta: {
      title: "companies",
      type: "index",
      section: "Pg_company",
      requireAuth: true
    }
  },
  {
    path: "/people-index/acquaintance-index/:id",
    name: "acquaintance-show",
    component: acquaintance,
    meta: {
      title: "acquaintance",
      type: "show",
      section: "Pg_acquaintance",
      requireAuth: true
    }
  },
  {
    path: "/people-index/acquaintance-index",
    name: "acquaintance-index",
    component: acquaintanceIndex,
    meta: {
      title: "acquaintance",
      type: "index",
      section: "Pg_acquaintance",
      requireAuth: true
    }
  },
  {
    path: "/people-index/phonebook-index/:id",
    name: "phonebook-show",
    component: phonebook,
    meta: {
      title: "phonebook",
      type: "show",
      section: "Pg_phone_book",
      requireAuth: true
    }
  },
  {
    path: "/people-index/phonebook-index",
    name: "phonebook-index",
    component: phonebookIndex,
    meta: {
      title: "phonebook",
      type: "index",
      section: "Pg_phone_book",
      requireAuth: true
    }
  },
  {
    path: "/people-index/contact-index/:id",
    name: "contact-show",
    component: contact,
    meta: {
      title: "contact",
      type: "show",
      section: "Pg_contact",
      requireAuth: true
    }
  },
  {
    path: "/people-index/contact-index",
    name: "contact-index",
    component: contactIndex,
    meta: {
      title: "contacts",
      type: "index",
      section: "Pg_contact",
      requireAuth: true
    }
  },
  {
    path: "/people-index/potential-agent-index/:id",
    name: "potential-agent-show",
    component: potentialAgent,
    meta: {
      title: "potentialAgent",
      type: "show",
      section: "Pg_poten_agent",
      requireAuth: true
    }
  },
  {
    path: "/people-index/potential-agent-index",
    name: "potential-agent-index",
    component: potentialAgentIndex,
    meta: {
      title: "potentialAgents",
      type: "index",
      section: "Pg_poten_agent",
      requireAuth: true
    }
  },
  {
    path: "/people-index/agent-index/:id",
    name: "agent-show",
    component: agent,
    meta: {
      title: "agent",
      type: "show",
      section: "Pg_agent",
      requireAuth: true
    }
  },
  {
    path: "/people-index/agent-index",
    name: "agent-index",
    component: agentIndex,
    meta: {
      title: "agents",
      type: "index",
      section: "Pg_agent",
      requireAuth: true
    }
  },
  {
    path: "/people-index/visitor-changes",
    name: "visitor-changes-index",
    component: visitorChanges,
    meta: {
      title: "visitorChanges",
      type: "index",
      section: "Cp_visit_visitorChange",
      requireAuth: true
    }
  },
  {
    path: "/people-index/customer-changes",
    name: "customer-changes-index",
    component: customerChanges,
    meta: {
      title: "customerChanges",
      type: "index",
      section: "Cp_cust_customerChange",
      requireAuth: true
    }
  }
];
