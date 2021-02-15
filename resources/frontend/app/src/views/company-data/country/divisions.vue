<template>
  <div>
    <div
      v-if="loading"
      class="d-flex justify-center align-center"
      style="height: 5rem;"
    >
      <v-progress-circular indeterminate></v-progress-circular>
    </div>
    <datatable
      v-if="headers.length > 0"
      :headers="headers"
      :items="items"
      @storeModule="setStoreModule"
      height="10rem"
    />
    <div style="min-height: 2rem; width: 100%;">
      <div class="red--text text-body-2" v-for="e in errorMessages" :key="e">
        {{ e }}
      </div>
    </div>
  </div>
</template>

<script>
import datatable from "./../../../components/datatable/datatable";
import devisionApi from "@/services/api/control-panel/administrative-division.api.js";
import { cloneDeep } from "lodash";

export default {
  name: "divisions",
  props: {
    items: { type: Array, required: true },
    errorMessages: Array
  },
  components: {
    datatable
  },

  data() {
    return {
      headers: [],
      loading: false,
      module: null
    };
  },
  computed: {
    levelCol() {
      if (!this.module) return [];
      let items = this.$store.getters[`${this.module}/items`];
      let levelItems = [];
      items.forEach(element => {
        levelItems.push({
          id: element.id,
          val: element.id
        });
      });
      return levelItems;
    }
  },
  methods: {
    setStoreModule(name) {
      this.$emit("module", name);
      this.module = name;
    },
    async loadDivisionsTypes() {
      this.loading = true;
      let headers = [
        {
          text: "levelId",
          value: "adId",
          type: "number",
          readonly: true,
          visible: false,
          width: "50px"
        },
        {
          text: this.$t("level"),
          value: "level",
          type: "number",
          readonly: true,
          visible: true,
          width: "100px"
        },
        {
          text: this.$t("isCoding"),
          value: "isCoding",
          type: "checkbox",
          readonly: false,
          visible: true,
          width: "100px"
        },
        {
          text: this.$t("codingType"),
          value: "codingType",
          type: "select",
          readonly: false,
          visible: true,
          width: "150px",
          options: [
            { value: 1, label: this.$t("required") },
            { value: 0, label: this.$t("notRequired") }
          ],
          rules: [
            {
              type: "requiredIf",
              other: "isCoding",
              message: this.$t("required")
            }
          ]
        },
        {
          text: this.$t("type"),
          value: "type",
          type: "select",
          unique: true,
          uniqueMessage: "Column should be unique values",
          readonly: false,
          visible: true,
          width: "150px",
          itemText: this.$i18n.locale == "en" ? "nameL" : "name",
          itemValue: "id",
          options: [],
          rules: [{ type: "required", message: this.$t("required") }]
        }
      ];
      await devisionApi
        .getDivisionTypes()
        .then(res => {
          headers[4].options = res;
          this.headers = cloneDeep(headers);
        })
        .catch(() => {})
        .finally(() => (this.loading = false));
    },
    // getNewItemIndex(arr1, arr2) {
    //   for (let i = 0; i < arr1.length; i++) {
    //     if (arr1[i].val && arr2[i] == undefined) {
    //       return i;
    //     }
    //   }
    // },
    // async setLevel(id, val) {
    //   await this.$store.dispatch(`${this.module}/editItem`, {
    //     id: id,
    //     level: val
    //   });
    // },
    autoInc() {
      if (this.module) this.$store.commit(`${this.module}/autoInc`, "level");
    }
  },
  watch: {
    levelCol() {
      // let index = this.getNewItemIndex(newVal, oldVal);
      // if (this.levelCol[index])
      //   this.setLevel(newVal[index].id, newVal[index].val);
      this.autoInc();
    }
  },
  created() {
    //console.log("ttttt");
    this.loadDivisionsTypes();
  }
};
</script>

<style></style>
