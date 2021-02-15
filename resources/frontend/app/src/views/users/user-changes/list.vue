<template>
  <div>
    <v-sheet
      width="100%"
      color="#FFFFFF"
      class="d-flex flex-column align-start mb-4 pa-4"
      style="border-bottom: 1px solid lightgray;"
    >
      <div class="text-h5 ml-4" v-text="$t('userChanges')"></div>
      <v-breadcrumbs v-if="breadcrumbs" :items="breadcrumbs"></v-breadcrumbs>
    </v-sheet>
    <v-container>
      <v-card width="100%">
        <v-card-text>
          <v-btn-toggle v-model="type">
            <v-btn
              color="secondary"
              value="unFinished"
              v-text="$t('unFinishedChanges')"
              block
              outlined
            ></v-btn>
            <v-btn
              color="secondary"
              value="finished"
              v-text="$t('finishedChanges')"
              block
              outlined
            ></v-btn>
          </v-btn-toggle>
          <v-data-table
            v-show="type == 'unFinished'"
            :headers="headers"
            :items="unFinishItems"
            class="elevation-1"
            height="20rem"
            fixed-header
            :loading="loading"
            :loading-text="loadingText"
            @click:row="editItem"
          >
            <template v-slot:no-data>
              <div class="text-body-1" v-text="$t('noData')"></div>
            </template>
            <!-- <template v-slot:header="{ props: { headers } }">
              <thead>
                <tr>
                  <th v-for="(h, i) in headers" :key="i">
                    <search-filter
                      @set-filter="setFilter(i, $event)"
                    ></search-filter>
                  </th>
                </tr>
              </thead>
            </template> -->
          </v-data-table>
          <v-data-table
            v-show="type == 'finished'"
            :headers="headers"
            :items="finishItems"
            class="elevation-1"
            height="20rem"
            hide-default-footer
            fixed-header
            :loading="loading"
            :loading-text="loadingText"
          >
            <template v-slot:no-data>
              <div class="text-body-1" v-text="$t('noData')"></div>
            </template>
            <!-- <template v-slot:header="{ props: { headers } }">
              <thead>
                <tr>
                  <th v-for="(h, i) in headers" :key="i">
                    <search-filter
                      @set-filter="setFilter(i, $event)"
                    ></search-filter>
                  </th>
                </tr>
              </thead>
            </template> -->
          </v-data-table>
          <div class="d-flex justify-center mt-4">
            <slot name="footer"></slot>
          </div>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
//import searcFilter from "./../../../components/filter";
//import { cloneDeep } from "lodash";

export default {
  name: "changes-index",

  components: {
    //  "search-filter": searcFilter
  },
  props: {
    breadcrumbs: { type: Array, required: true },
    api: {
      type: String,
      required: true
    },
    hasLanguage: { type: Boolean, default: true },
    can: { type: Boolean, default: false },
    returnPath: { type: String, required: true }
  },
  data() {
    return {
      finishItems: [],
      unFinishItems: [],
      loading: false,
      pageData: {},
      type: "unFinished",
      loadingText: this.$t("loadingText")
    };
  },
  computed: {
    headers() {
      return this.type == "unFinished"
        ? [
            {
              text: this.$t("name"),
              value: "old.name",
              align: "center",
              sortable: true,
              width: "200px"
            },
            {
              text: this.$t("nameL"),
              value: "old.latin_name",
              align: "center",
              sortable: true,
              width: "200px"
            },
            {
              text: this.$t("updateDate"),
              value: "new.updated_at",
              align: "center",
              sortable: true,
              width: "200px"
            }
          ]
        : [
            {
              text: this.$t("name"),
              value: "name",
              align: "center",
              sortable: true,
              width: "200px"
            },
            {
              text: this.$t("nameL"),
              value: "latin_name",
              align: "center",
              sortable: true,
              width: "200px"
            },
            {
              text: this.$t("updateDate"),
              value: "updated_at",
              align: "center",
              sortable: true,
              width: "200px"
            }
          ];
    }
  },
  methods: {
    editItem(item) {
      let c = this.unFinishItems.find(element => {
        return element.new.id == item.new.id;
      });
      this.pageData = {
        ...c,
        oName: c.old.name,
        oNameL: c.old.latin_name,
        oImage: c.old.image,
        oEmail: c.old.email,
        oUserName: c.old.user_name,
        oLanguage: c.old.preferred_language,
        nName: c.new.name,
        nNameL: c.new.latin_name,
        nImage: c.new.image,
        nEmail: c.new.email,
        nUserName: c.new.user_name,
        nLanguage: c.new.preferred_language,
        oAddressItems: [],
        oMobileItems: [],
        oPhoneItems: [],
        oFaxItems: [],
        oEmailItems: [],
        oLocationItems: [],
        oNoteItems: [],
        nAddressItems: [],
        nMobileItems: [],
        nPhoneItems: [],
        nFaxItems: [],
        nEmailItems: [],
        nLocationItems: [],
        nNoteItems: []
      };
      this.initData();
      this.$router.push({
        name: "show-changes",
        params: {
          pageData: this.pageData,
          api: this.api,
          breadcrumbs: this.breadcrumbs,
          hasLanguage: this.hasLanguage,
          can: this.can,
          returnPath: this.returnPath
        }
      });
    },
    load(payload = null) {
      this.loading = true;
      let api = require(`@/services/api/${this.api}`);

      api.default

        .getFinishedChanges(payload)
        .then(res => {
          this.finishItems = res;
        })
        .catch(() => {})
        .finally(() => (this.loading = false));

      api.default

        .getUnFinishedChanges(payload)
        .then(res => {
          this.unFinishItems = res;
        })
        .catch(() => {})
        .finally(() => (this.loading = false));
    },
    initData() {
      if (this.pageData.old.contact_infos)
        this.pageData.old.contact_infos.forEach(item => {
          switch (item.type) {
            case "Address":
              this.pageData.oAddressItems.push({
                cm_address: item.cm_address,
                a_d: item.a_d,
                address: item.address_value,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Mobile":
              this.pageData.oMobileItems.push({
                cm_mobile: item.cm_mobile,
                country: item.country,
                mobile: item.value,
                socialMedia: item.social_media,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Phone":
              this.pageData.oPhoneItems.push({
                cm_phone: item.cm_phone,
                a_d: item.a_d,
                country: item.country,
                phone: item.value,
                extension: item.transfer_no,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Fax":
              this.pageData.oFaxItems.push({
                cm_fax: item.cm_fax,
                a_d: item.a_d,
                country: item.country,
                fax: item.value,
                extension: item.transfer_no,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Email":
              this.pageData.oEmailItems.push({
                cm_email: item.cm_email,
                email: item.value,
                socialMedia: item.social_media,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Position":
              this.pageData.oLocationItems.push({
                cm_position: item.cm_position,
                location: {
                  lat: item.gps_location_x,
                  lng: item.gps_location_y
                },
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Note":
              this.pageData.oNoteItems.push({
                cm_note: item.cm_note,
                note: item.note_value,
                notice: item.declaration,
                contact_info: item.contact_info,
                status: "none"
              });
              break;

            default:
              break;
          }
        });
      if (this.pageData.new.contact_infos)
        this.pageData.new.contact_infos.forEach(item => {
          switch (item.type) {
            case "Address":
              this.pageData.nAddressItems.push({
                cm_address: item.cm_address,
                a_d: item.a_d,
                address: item.address_value,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Mobile":
              this.pageData.nMobileItems.push({
                cm_mobile: item.cm_mobile,
                country: item.country,
                mobile: item.value,
                socialMedia: item.social_media,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Phone":
              this.pageData.nPhoneItems.push({
                cm_phone: item.cm_phone,
                a_d: item.a_d,
                country: item.country,
                phone: item.value,
                extension: item.transfer_no,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Fax":
              this.pageData.nFaxItems.push({
                cm_fax: item.cm_fax,
                a_d: item.a_d,
                country: item.country,
                fax: item.value,
                extension: item.transfer_no,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Email":
              this.pageData.nEmailItems.push({
                cm_email: item.cm_email,
                email: item.value,
                socialMedia: item.social_media,
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Position":
              this.pageData.nLlocationItems.push({
                cm_position: item.cm_position,
                location: {
                  lat: item.gps_location_x,
                  lng: item.gps_location_y
                },
                contact_info: item.contact_info,
                status: "none"
              });
              break;
            case "Note":
              this.pageData.nNoteItems.push({
                cm_note: item.cm_note,
                note: item.note_value,
                notice: item.declaration,
                contact_info: item.contact_info,
                status: "none"
              });
              break;

            default:
              break;
          }
        });
    }
    // setFilter(index, $event) {
    //   this.headers[index]["filter"] = {};
    //   this.headers[index]["filter"] = $event ? cloneDeep($event) : undefined;

    //   let url = "?";
    //   this.headers.forEach(header => {
    //     if (header.filter)
    //       if (header.filter.value && header.filter.operator)
    //         url += `${header.value}=${header.filter.value}&${header.value}_filter=${header.filter.operator}`;
    //   });
    //   this.load(url.length > 2 ? url : null);
    // }
  },

  created() {
    this.load();
  }
};
</script>

<style>
.v-card__text .v-btn-toggle .v-btn {
  margin-left: 50px !important;
}
</style>
