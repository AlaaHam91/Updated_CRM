<template>
  <v-navigation-drawer
    color="#263238"
    :mini-variant.sync="drawer"
    permanent
    :right="isRight"
    clipped
    app
    dark
  >
    <v-list dense nav>
      <v-list-group
        :prepend-icon="item.icon"
        v-for="(item, i) in items"
        :key="i + 'i'"
        active-class="side-nav-active"
      >
        <template v-slot:activator>
          <v-list-item-title v-text="$t(item.title)"></v-list-item-title>
        </template>

        <v-list-item
          v-for="subItem in item.items"
          :key="subItem.title"
          :to="{ name: subItem.to }"
          active-class="side-nav-active"
        >
          <v-list-item-icon>
            <v-icon color="grey lighten-1"> </v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title v-text="$t(subItem.title)"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-group>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
/* eslint-disable no-unused-vars */
import peopleIndexRoutes from "./../views/people-index/routes";
import peopleSettingsRoutes from "./../views/people-settings/routes";
import controlPanelRoutes from "./../views/control-panel/routes";
import usersRoutes from "./../views/users/routes";
import companyDataRoutes from "./../views/company-data/routes";
import filesManagmentRoutes from "./../views/files-managment/routes";
import ticketRoutes from "./../views/ticket/routes";
import reportRoutes from "./../views/report/routes";

export default {
  data() {
    return {
      crmSystem: [
        {
          name: "tickets",
          title: "tickets",
          icon: "mdi-tag-text-outline",
          items: []
        },
        {
          name: "people-index",
          title: "index",
          icon: "mdi-account-multiple",
          items: []
        },
        {
          name: "report",
          title: "reports",
          icon: "mdi-file-chart-outline",
          items: []
        }
        // {
        //   title: "Components-examples",
        //   icon: "mdi-dots-horizontal-circle",
        //   items: [{ title: "data-table", to: "data-table-example" }]
        // }
      ],
      controlPanel: [
        {
          name: "people settings",
          title: "indexSettings",
          icon: "mdi-account-settings-outline",
          items: []
        },

        {
          name: "control panel",
          title: "controlPanel",
          icon: "mdi-cogs",
          items: []
        },
        {
          name: "users",
          title: "users",
          icon: "mdi-account-multiple-outline",
          items: []
        },
        {
          name: "company data",
          title: "companyData",
          icon: "mdi-file-cog-outline",
          items: []
        },
        {
          name: "files managment",
          title: "filesManagment",
          icon: "mdi-file-cabinet",
          items: []
        }
      ]
    };
  },

  computed: {
    drawer: {
      get() {
        return this.$store.state.ui.drawerOpen;
      },
      set() {
        this.$store.commit("ui/toggleDrawer");
      }
    },
    isRight() {
      return this.$vuetify.rtl;
    },
    items() {
      return this.route.startsWith("/control-panel")
        ? this.controlPanel
        : this.crmSystem;
    },
    route() {
      return this.$route.fullPath;
    }
  },

  methods: {
    async mapArr(array, addType) {
      let result = [];
      for (let i = 0; i < array.length; i++)
        if (array[i].meta.type == addType && array[i].meta.section == "public")
          result.push({
            title: array[i].meta.title,
            to: array[i].name
          });
        else if (
          array[i].meta.type == addType &&
          (await this.$can(array[i].meta.section, "Open"))
        )
          result.push({
            title: array[i].meta.title,
            to: array[i].name
          });

      return result;
    },
    async addItems() {
      for (let i = 0; i < this.crmSystem.length; i++)
        switch (this.crmSystem[i].name) {
          case "people-index":
            this.crmSystem[i].items = await this.mapArr(
              peopleIndexRoutes,
              "index"
            );
            break;
          case "tickets":
            this.crmSystem[i].items = await this.mapArr(
              ticketRoutes,
              "navItem"
            );
            break;
          case "report":
            this.crmSystem[i].items = await this.mapArr(reportRoutes, "index");
            break;
          default:
            break;
        }
      for (let i = 0; i < this.controlPanel.length; i++)
        switch (this.controlPanel[i].name) {
          case "people settings":
            this.controlPanel[i].items = await this.mapArr(
              peopleSettingsRoutes,
              "index"
            );
            break;
          case "control panel":
            this.controlPanel[i].items = await this.mapArr(
              controlPanelRoutes,
              "index"
            );
            break;
          case "users":
            this.controlPanel[i].items = await this.mapArr(
              usersRoutes,
              "index"
            );
            break;
          case "company data":
            this.controlPanel[i].items = await this.mapArr(
              companyDataRoutes,
              "index"
            );
            break;
          case "files managment":
            this.controlPanel[i].items = await this.mapArr(
              filesManagmentRoutes,
              "index"
            );
            break;
          default:
            break;
        }
    }
  },

  created() {
    this.addItems();
  }
};
</script>

<style>
.side-nav-active {
  background-color: #26a69a;
  color: white !important;
}
</style>
