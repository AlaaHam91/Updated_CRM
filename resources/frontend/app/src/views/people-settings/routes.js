const importance = () => import("./importances/show");
const importances = () => import("./importances/index");
const language = () => import("./languages/show");
const languages = () => import("./languages/index");
const socialLink = () => import("./social-link/show");
const socialLinks = () => import("./social-link/index");
const contactSource = () => import("./contact-source/show");
const contactSources = () => import("./contact-source/index");
const document = () => import("./document/show");
const documents = () => import("./document/index");
const documentSource = () => import("./document-sources/show");
const documentSources = () => import("./document-sources/index");
const acquaintanceMethod = () => import("./acquaintance-method/show");
const acquaintanceMethods = () => import("./acquaintance-method/index");
const additionalShow = () => import("./additional/show.vue");
const additionalIndex = () => import("./additional/index.vue");

export default [
  {
    path: "/control-panel/people-settings/importance-index/:id",
    name: "importance-show",
    component: importance,
    meta: {
      title: "importance",
      type: "show",
      section: "Pg_importance",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/importance-index",
    name: "importance-index",
    component: importances,
    meta: {
      title: "importances",
      type: "index",
      section: "Pg_importance",
      requireAuth: true
    }
  },

  {
    path: "/control-panel/people-settings/language-index/:id",
    name: "language-show",
    component: language,
    meta: {
      title: "language",
      type: "show",
      section: "Pg_languages",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/language-index",
    name: "language-index",
    component: languages,
    meta: {
      title: "languages",
      type: "index",
      section: "Pg_languages",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/social-link-index/:id",
    name: "social-link-show",
    component: socialLink,
    meta: {
      title: "socialLink",
      type: "show",
      section: "Pg_social_media",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/social-link-index",
    name: "social-link-index",
    component: socialLinks,
    meta: {
      title: "socialLinks",
      type: "index",
      section: "Pg_social_media",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/contact-source-index/:id",
    name: "contact-source-show",
    component: contactSource,
    meta: {
      title: "contactSource",
      type: "show",
      section: "Pg_contact",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/contact-source-index",
    name: "contact-source-index",
    component: contactSources,
    meta: {
      title: "contactSources",
      type: "index",
      section: "Pg_contact",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/document-index/:id",
    name: "document-show",
    component: document,
    meta: {
      title: "document",
      type: "show",
      section: "Pg_document",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/document-index",
    name: "document-index",
    component: documents,
    meta: {
      title: "documents",
      type: "index",
      section: "Pg_document",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/document-source-index/:id",
    name: "document-source-show",
    component: documentSource,
    meta: {
      title: "documentSource",
      type: "show",
      section: "Pg_doc_sources",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/document-source-index",
    name: "document-source-index",
    component: documentSources,
    meta: {
      title: "documentSources",
      type: "index",
      section: "Pg_doc_sources",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/acquaintance-method-index/:id",
    name: "acquaintance-method-show",
    component: acquaintanceMethod,
    meta: {
      title: "acquaintanceMethod",
      type: "show",
      section: "Pg_acq_method",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/acquaintance-method-index",
    name: "acquaintance-method-index",
    component: acquaintanceMethods,
    meta: {
      title: "acquaintanceMethods",
      type: "index",
      section: "Pg_acq_method",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/additional-field-index/:id",
    name: "additional-show",
    component: additionalShow,
    meta: {
      title: "additionalFields",
      type: "show",
      section: "Pg_add_info",
      requireAuth: true
    }
  },
  {
    path: "/control-panel/people-settings/additional-field-index",
    name: "additional-index",
    component: additionalIndex,
    meta: {
      title: "additionalFields",
      type: "index",
      section: "Pg_add_info",
      requireAuth: true
    }
  }
];
