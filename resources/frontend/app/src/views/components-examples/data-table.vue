<template>
  <div>
    <div class="mx-6">
      <br /><br />
      <div class="text-h5">
        Data table
      </div>
      <div class="text-body-1">
        Data table is table component with CRUD operations that can do (show
        items, add item, delete item). Data table will work with conjunction of
        dedicated store module.
      </div>
      <br /><br />
      <div class="text-h6 ">
        Configuring data table
      </div>
      <div class="text-body-1">
        <ol>
          <li>Import data-table to your component/page</li>
          <li>
            Bind headers array (array of objects). Each obejcet should have:
            text (the title to be shown in the table), value, readonly (to set
            column as readonly) visible (to control column visiblity), width
            (pixel). Type can be one of the following:
            <ul>
              <li>string</li>
              <li>number</li>
              <li>date</li>
              <li>search</li>
              <li>select</li>
              <li>checkbox</li>
              <li>time</li>
              <li>location</li>
              <li>multiselect</li>
              <li>combobox</li>
              <li>uploadFiles</li>
              <li>btnLink</li>
              <li>duration</li>
            </ul>
          </li>
          <li>
            (Optional): you can set headers at any point using store mutation
            <code>
              this.$store.commit(`${this.storeModule}/headers`, { headers:
              newHeaders });
            </code>
          </li>
          <li>
            Bind items array. Table will insert binded items during created
            hook. Further updates to items will not have effect on data-table.
            Please note every object should have all keys of headers.
          </li>
          <li>
            (Optional): Set component options. prop of
            <code>add</code> (true/false) to show/hide add btn. prop of
            <code>delete</code> (true/false) to show/hide delete btn.
          </li>
          <li>
            (Optional): Set <code> module-prefix </code> prop to set a name
            prefix for the table module
          </li>
          <li>
            Table will emit <code>storeModule</code> event after created hook.
            Be sure to capture that value to use it latter for further
            operations.
          </li>
          <li>
            (Optional): Set a header to have unique values. This can be done
            using
            <code>unique: true</code> in the header, also add
            <code>uniqueMessage: "message"</code>. The uniqueness of the column
            will be checked during validation.
          </li>
        </ol>
      </div>
      <br /><br />
      <div class="text-h6">
        Validation
      </div>
      <div class="text-body-1">
        Validation can be acheived through rules property in each header
        (Validation is optional). Rules is an array of objects where each object
        should have the following properties: type: message: error message to
        show to user. Validation will work in the following way:
      </div>
      <div class="text-body-1">Rule types:</div>
      <ul>
        <li>
          required:
          <code>{ type: "required", message: "Required field") }</code>
        </li>
        <li>
          required either one of two:
          <code>
            { type: "requiredEither", other: "debit", message: "Required this or
            that, please enter one at lease" }
          </code>
        </li>
        <li>
          required if:
          <code>
            { type: "requiredIf", other: "active", message: "Required because
            you set active field" }
          </code>
        </li>
        <li>
          email: <code>{ type: "required", message: "Not valid Email" }</code>
        </li>
        <li>
          regex:
          <code>
            { type: "regex", value: /([A-Z])\w+/g , message: "Phone format"}
          </code>
        </li>
      </ul>
      <div class="text-body-1">Validate operations:</div>
      <ul>
        <li>
          Turn on validation: invoke store mutation touchValidation
          <code>this.$store.commit(`${this.storeModule}/touchValidation`)</code>
        </li>
        <li>
          Get state: if component has errors or not (true/false) with help of
          store getter hasErrors
          <code>this.$store.getters[`${this.storeModule}/hasErrors`]</code>
        </li>
        <li>
          Reset validation: using resetValidation store action
          <code>
            this.$store.dispatch(`${this.storeModule}/resetValidation`)
          </code>
        </li>
      </ul>
      <br /><br />
      <div class="text-h6  ">
        Operations
      </div>
      <div class="text-body-1">
        Operations can be done with help of store actions/mutations.
      </div>
      <div class="text-body-1">
        <ul>
          <li>
            Get table data: invoke store getter items
            <code>this.$store.getters[`${this.storeModule}/items`]</code>
          </li>
          <li>
            Add an item programmatically: invoke store action addItem
            <code>
              this.$store.dispatch(`${this.storeModule}/addItem`, { ... })
            </code>
          </li>
          <li>
            Update an item programmatically: invoke store action editItem
            <code>
              this.$store.dispatch(`${this.storeModule}/editItem`, { ... })
            </code>
          </li>
          <li>
            Delete item programmatically: invoke store action deleteItem
            <code>
              this.$store.dispatch(`${this.storeModule}/deleteItem`, id)
            </code>
          </li>
          <li>
            Delete all items: invoke store action clearData
            <code>this.$store.dispatch(`${this.storeModule}/clearData`)</code>
          </li>
          <li>
            Replace all items: invoke store action items
            <code>
              this.$store.dispatch(`${this.storeModule}/items`, newItems)
            </code>
          </li>
          <li>
            (Optional): Set a header to have computed options (for select type
            only). This can be done by setting property
            <code>dedicatedOptions: "cityOptions"</code> for exmaple in the
            header. Every item should have the proprty
            <code>cityOptions: []</code>. You can latter (based on data
            changes), populate the cityOptions array with items using store
            mutation:
            <code>
              this.$store.commit(`${this.storeModule}/dedicatedOptions`, {
              index: itemIndex, optionsName: "cityOptions", options: options });
            </code>
          </li>
          <li>
            Auto-increment header: add auto-increment values to any header using
            store mutation
            <code>
              this.$store.commit(`${this.storeModule}/autoInc`, "number")
            </code>
          </li>
        </ul>
      </div>
    </div>
    <hr class="mt-16 mx-8" style="opacity: 0.5;" />
    <!-- data-table -->
    <datatable
      :headers="headers"
      :items="items"
      @storeModule="setStoreModule"
      height="20rem"
      :add="canAdd"
      :delete="canDelete"
    />
    <hr class="mx-8" style="opacity: 0.5;" />
    <div class="text-h5 mx-5">Playground</div>
    <div class="d-flex justify-space-around my-2">
      <v-switch label="can add" v-model="canAdd"></v-switch>
      <v-switch label="can delete" v-model="canDelete"></v-switch>
    </div>
    <hr class="mx-8" style="opacity: 0.5;" />
    <div class="d-flex justify-space-around my-2">
      <v-btn small @click="getStore">Get storeModule console</v-btn>
      <v-btn small @click="save">Get items array in console</v-btn>
      <v-btn small color="warning" @click="clearData">clearData</v-btn>
    </div>
    <hr class="mx-8" style="opacity: 0.5;" />
    <div class="d-flex justify-space-around my-2">
      <v-btn small color="success" @click="addItem">addItem</v-btn>
      <v-btn small @click="editItem">editItem id (1)</v-btn>
      <v-btn small color="error" @click="deleteItem">deleteItem id (1)</v-btn>
      <v-btn small @click="autoInc">Auto inc (number) header</v-btn>
    </div>
    <hr class="mx-8" style="opacity: 0.5;" />
    <div class="d-flex justify-space-around my-2">
      <v-btn small @click="touch">Touch</v-btn>
      <v-btn small color="warning" @click="reset">Reset</v-btn>
    </div>
    <hr class="mx-8" style="opacity: 0.5;" />
  </div>
</template>

<script>
import datatable from "./../../components/datatable/datatable";
import languageApi from "@/services/api/people-settings/language.api.js";

export default {
  name: "data-table-example",
  components: {
    datatable
  },
  data() {
    return {
      canAdd: true,
      canDelete: true,
      isChanging: false,
      storeModule: null,
      headers: [
        {
          text: "Credit",
          value: "credit",
          type: "number",
          readonly: false,
          visible: true,
          width: "75px",
          rules: [
            {
              type: "requiredEither",
              other: "debit",
              message: "Required either credit or debit"
            }
          ]
        },
        {
          text: "Debit",
          value: "debit",
          type: "number",
          readonly: false,
          visible: true,
          width: "75px",
          rules: [
            {
              type: "requiredEither",
              other: "credit",
              message: "Required either credit or debit"
            }
          ]
        },
        {
          text: "Language",
          value: "language",
          type: "search",
          readonly: false,
          visible: true,
          width: "150px",
          headers: [
            { text: this.$t("name"), value: "name" },
            { text: this.$t("nameL"), value: "nameL" }
          ],
          options: [],
          itemText: "nameL",
          itemValue: "id"
        },
        {
          text: "Note",
          value: "note",
          type: "string",
          readonly: false,
          visible: true,
          width: "200px",
          rules: [{ type: "required", message: this.$t("required") }]
        },
        {
          text: "Phone",
          value: "phone",
          type: "string",
          readonly: false,
          visible: true,
          width: "100px",
          rules: [
            {
              type: "regex",
              value: /(\+|0{2})?([0-9 ]+){7,}/,
              message: "Not valid phone: e.g: 009 1234567"
            }
          ]
        },
        {
          text: "Email",
          value: "email",
          type: "string",
          unique: true,
          uniqueMessage: "Column should be unique values",
          readonly: false,
          visible: true,
          width: "150px",
          rules: [{ type: "email", message: this.$t("emailNotValid") }]
        },
        {
          text: "Price",
          value: "price",
          type: "number",
          readonly: false,
          visible: true,
          width: "100px"
        },
        {
          text: "Date",
          value: "date",
          type: "date",
          readonly: false,
          visible: true,
          width: "200px"
        },
        {
          text: "Currency",
          value: "currency",
          type: "select",
          readonly: false,
          visible: true,
          width: "100px",
          options: [
            { value: "sad", label: "SAD" },
            { value: "usd", label: "USD" }
          ]
          // itemText: "label",
          // itemValue: "value"
        },
        {
          text: "Active",
          value: "active",
          type: "checkbox",
          readonly: false,
          visible: true,
          width: "100px"
        },
        {
          text: "Reason",
          value: "reason",
          type: "string",
          readonly: false,
          visible: true,
          width: "200px",
          rules: [
            {
              type: "requiredIf",
              other: "active",
              message: "Required because you set active field"
            }
          ]
        },
        {
          text: "Time",
          value: "time",
          type: "time",
          readonly: false,
          visible: true,
          width: "100px"
        },
        {
          text: "Location",
          value: "location",
          width: "200px",
          type: "location",
          readonly: false,
          visible: true
        },
        {
          text: "Social media",
          value: "socialMedia",
          type: "multiselect",
          readonly: false,
          visible: true,
          width: "200px",
          itemText: "label",
          itemValue: "value",
          options: [
            { value: "Linkedin", label: "Linkedin" },
            { value: "Facebook", label: "Facebook" },
            { value: "Snapchat", label: "Snapchat" }
          ]
        },
        {
          text: "Activity",
          value: "activity",
          type: "combobox",
          readonly: false,
          visible: true,
          width: "300px",
          itemText: "label",
          itemValue: "value",
          options: [
            { value: 1, label: "Software developer" },
            { value: 2, label: "UI/UX" },
            { value: 3, label: "Tester" }
          ]
        },
        {
          text: "Country",
          value: "country",
          type: "select",
          readonly: false,
          visible: true,
          width: "200px",
          options: [
            { value: "uae", label: "UAE" },
            { value: "sy", label: "SY" }
          ]
        },
        {
          text: "City",
          value: "city",
          type: "select",
          readonly: false,
          visible: true,
          width: "200px",
          dedicatedOptions: "cityOptions"
        },
        {
          text: "Upload files",
          value: "upload",
          type: "uploadFiles",
          readonly: false,
          visible: true,
          width: "200px"
        },
        {
          text: "Go to home",
          value: "link",
          type: "btnLink",
          linkLabel: "Home",
          readonly: false,
          visible: true,
          width: "200px"
        },
        {
          text: "Number",
          value: "number",
          type: "string",
          readonly: true,
          visible: true,
          width: "150px"
        },
        {
          text: "Expecte duration",
          value: "expectedDuration",
          type: "duration",
          readonly: true,
          visible: true,
          width: "150px"
        }
      ],
      items: [
        {
          credit: 0,
          debit: 0,
          language: null,
          note: "Incididunt irure dolore officia commodo minim dolore non.",
          phone: null,
          email: null,
          price: null,
          date: "2020-09-04",
          currency: "usd",
          active: true,
          reason: null,
          time: null,
          location: null,
          socialMedia: null,
          activity: [{ value: 2, label: "UI/UX" }],
          country: { value: "uae", label: "UAE" },
          city: null,
          cityOptions: [],
          upload: [],
          link: { name: "home" },
          number: null,
          expectedDuration: "10:20"
        },
        {
          credit: 0,
          debit: 0,
          language: null,
          note:
            "Deserunt voluptate nulla et fugiat labore ex adipisicing eu dolor sint mollit..",
          phone: null,
          email: null,
          price: null,
          date: "2020-09-04",
          currency: "usd",
          active: false,
          reason: null,
          time: null,
          location: null,
          socialMedia: null,
          activity: [],
          country: null,
          city: null,
          cityOptions: [],
          upload: [],
          link: { name: "home" },
          number: null,
          expectedDuration: "5:4"
        }
      ]
    };
  },
  methods: {
    setStoreModule(name) {
      this.storeModule = name;
    },
    getStore() {
      console.log(this.storeModule);
    },
    save() {
      //get data from store
      let data = this.$store.getters[`${this.storeModule}/items`];
      console.log(data);
    },
    touch() {
      this.$store.commit(`${this.storeModule}/touchValidation`);
      console.log(
        "hasErrors ? ",
        this.$store.getters[`${this.storeModule}/hasErrors`]
      );
    },
    reset() {
      this.$store.dispatch(`${this.storeModule}/resetValidation`);
    },
    addItem() {
      let item = {
        language: null,
        note: "new item added programmatically",
        currency: "usd",
        price: 1000,
        date: {
          g: null,
          h: null
        },
        active: true,
        time: null,
        email: null,
        phone: null,
        credit: 0,
        debit: 0
      };
      this.$store.dispatch(`${this.storeModule}/addItem`, item);
    },
    editItem() {
      let items = this.$store.getters[`${this.storeModule}/items`];
      if (items.length > 0) {
        let item = {
          id: 1,
          language: null,
          note: "edited here",
          currency: "usd",
          price: 10,
          date: {
            g: null,
            h: null
          },
          active: true,
          time: null,
          email: null,
          phone: null
        };
        this.$store.dispatch(`${this.storeModule}/editItem`, item);
      }
    },
    deleteItem() {
      console.log("del func");
      this.$store.dispatch(`${this.storeModule}/deleteItem`, 1);
    },
    getNewItemIndex(arr1, arr2) {
      for (let i = 0; i < arr1.length; i++)
        if (arr1[i].val !== arr2[i].val) return i;
    },
    async setCredit(id, val) {
      this.isChanging = true;
      await this.$store.dispatch(`${this.storeModule}/editItem`, {
        id: id,
        credit: val
      });
      this.isChanging = false;
    },
    async setDebit(id, val) {
      this.isChanging = true;
      await this.$store.dispatch(`${this.storeModule}/editItem`, {
        id: id,
        debit: val
      });
      this.isChanging = false;
    },
    clearData() {
      this.$store.dispatch(`${this.storeModule}/clearData`);
    },
    autoInc() {
      this.$store.commit(`${this.storeModule}/autoInc`, "number");
    },
    loadLangItems() {
      languageApi.getAll().then(res => {
        this.headers[2].options = res;
        this.$store.commit(`${this.storeModule}/headers`, {
          headers: this.headers
        });
      });
    }
  },

  computed: {
    debitCol() {
      if (!this.storeModule) return [];
      let items = this.$store.getters[`${this.storeModule}/items`];
      let debitItems = [];
      items.forEach(element => {
        debitItems.push({ id: element.id, val: element.debit });
      });
      return debitItems;
    },
    creditCol() {
      if (!this.storeModule) return [];
      let items = this.$store.getters[`${this.storeModule}/items`];
      let creditItems = [];
      items.forEach(element => {
        creditItems.push({ id: element.id, val: element.credit });
      });
      return creditItems;
    },
    countryCol() {
      if (!this.storeModule) return [];
      let items = this.$store.getters[`${this.storeModule}/items`];
      let countryItems = [];
      items.forEach(element => {
        countryItems.push({ id: element.id, val: element.country });
      });
      return countryItems;
    }
  },

  watch: {
    debitCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length || this.isChanging) return;

      let index = this.getNewItemIndex(newVal, oldVal);
      if (index > -1)
        if (this.creditCol[index].val > 0) this.setCredit(newVal[index].id, 0);
    },
    creditCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length || this.isChanging) return;

      let index = this.getNewItemIndex(newVal, oldVal);
      if (index > -1)
        if (this.debitCol[index].val > 0) {
          this.setDebit(newVal[index].id, 0);
        }
    },
    countryCol(newVal, oldVal) {
      if (newVal.length !== oldVal.length) return;

      //get item index
      let index = this.getNewItemIndex(newVal, oldVal);

      if (this.countryCol[index]) {
        let options = [];

        //check parent field value
        switch (this.countryCol[index].val) {
          case "uae":
            options = [
              { value: "abu dhabi", label: "Abu dhabi" },
              { value: "Dubai", label: "Dubai" }
            ];
            break;
          case "sy":
            options = [
              { value: "damas", label: "Damascus" },
              { value: "homs", label: "Homs" }
            ];
            break;
          default:
            break;
        }
        //set dedicated options to child
        this.$store.commit(`${this.storeModule}/dedicatedOptions`, {
          index: index,
          optionsName: "cityOptions",
          options: options
        });
      }
    }
  },

  created() {
    this.loadLangItems();
  }
};
</script>

<style></style>
