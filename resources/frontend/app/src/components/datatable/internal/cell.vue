<template>
  <div style="width: 100%;">
    <div v-if="type == 'string'">
      <input
        class="cellInput"
        type="text"
        v-model="value"
        autocomplete="off"
        spellcheck="false"
        :readonly="readonly"
      />
    </div>
    <div v-if="type == 'number'">
      <input
        class="cellInput"
        type="number"
        :min="min"
        :step="step"
        placeholder="0.00"
        v-model="value"
        autocomplete="off"
        spellcheck="false"
        :readonly="readonly"
      />
    </div>
    <div v-if="type == 'date'">
      <date v-model="value" :readonly="readonly" />
    </div>
    <div v-if="type == 'select'">
      <select class="cellInput" v-model="value" :disabled="readonly">
        <option
          v-for="(item, i) in selectOptions"
          :key="i"
          :value="itemValue ? item[itemValue] : item.value"
          v-text="itemText ? item[itemText] : item.label"
        ></option>
      </select>
    </div>
    <div v-if="type == 'checkbox'" class="d-flex justify-center">
      <input v-model="value" type="checkbox" :disabled="readonly" />
    </div>
    <div v-if="type == 'time'" class="text-center">
      <input
        type="time"
        v-model="value"
        class="cellInput"
        :readonly="readonly"
      />
    </div>
    <div v-if="type == 'search'">
      <search
        v-model="value"
        :headers="searchHeaders"
        :items="selectOptions"
        :item-text="itemText"
        :item-value="itemValue"
        :readonly="readonly"
      />
    </div>
    <div v-if="type == 'location'">
      <location-input v-model="value" :readonly="readonly"></location-input>
    </div>
    <div v-if="type == 'multiselect'">
      <v-autocomplete
        v-model="value"
        :items="selectOptions"
        dense
        multiple
        hide-details
        :item-text="itemText"
        :item-value="itemValue"
        :allow-overflow="false"
        hide-selected
        chips
        small-chips
        clearable
        class="custom"
      >
        <template v-slot:selection="data">
          <v-chip class="ma-1" v-text="data.item[itemText]"> </v-chip>
        </template>
      </v-autocomplete>
    </div>
    <div v-if="type == 'combobox'">
      <v-combobox
        v-model="value"
        :items="selectOptions"
        :item-text="itemText"
        :item-value="itemValue"
        multiple
        small-chips
        clearable
        :allow-overflow="false"
        hide-details
        dense
        hide-selected
      ></v-combobox>
    </div>
    <div v-if="type == 'uploadFiles'">
      <file-input v-model="value"></file-input>
    </div>
    <div v-if="type == 'btnLink'" class="d-flex justify-center">
      <v-btn
        small
        depressed
        color="grey darken-1"
        class="white--text"
        :to="value"
        v-text="linkLabel"
      ></v-btn>
    </div>
    <div v-if="type == 'duration'" class="d-flex justify-center">
      <duration style="width: 100%;" v-model="value"></duration>
    </div>
  </div>
</template>

<script>
import date from "./date";
import search from "./search";
import location from "./location";
import fileInput from "./file";
import duration from "./duration";

export default {
  name: "cell",
  components: {
    date,
    search,
    "location-input": location,
    fileInput,
    duration
  },
  props: {
    storeModule: { type: String, required: true },
    headerId: { type: Number, required: true },
    itemId: { type: Number, required: true },
    type: { type: String, default: "string" },
    readonly: { type: Boolean, default: false },
    rules: { type: Array },
    dedicatedOptionsName: String,
    min: String,
    step: { type: String, default: "0.01" },
    linkLabel: undefined
  },
  data() {
    return {};
  },
  computed: {
    value: {
      set(value) {
        this.$store.commit(`${this.storeModule}/cell`, {
          headerId: this.headerId,
          itemId: this.itemId,
          value: value,
          type: this.type
        });
      },
      get() {
        return this.$store.getters[`${this.storeModule}/cell`]({
          headerId: this.headerId,
          itemId: this.itemId
        });
      }
    },
    selectOptions() {
      if (this.dedicatedOptionsName)
        return this.$store.getters[`${this.storeModule}/dedicatedOptions`]({
          id: this.itemId,
          options: this.dedicatedOptionsName
        });

      return this.$store.getters[`${this.storeModule}/headers`].find(
        x => x.id == this.headerId
      ).options;
    },
    searchHeaders() {
      return this.$store.getters[`${this.storeModule}/headers`].find(
        x => x.id == this.headerId
      ).headers;
    },
    searchApi() {
      return this.$store.getters[`${this.storeModule}/headers`].find(
        x => x.id == this.headerId
      ).api;
    },
    searchQuery() {
      return this.$store.getters[`${this.storeModule}/headers`].find(
        x => x.id == this.headerId
      ).query;
    },
    itemValue() {
      return this.$store.getters[`${this.storeModule}/headers`].find(
        x => x.id == this.headerId
      ).itemValue;
    },
    itemText() {
      return this.$store.getters[`${this.storeModule}/headers`].find(
        x => x.id == this.headerId
      ).itemText;
    }
  }
};
</script>

<style>
.cellInput {
  outline: none;
  border: 0;
  background-color: transparent;
  width: 100%;
  padding: 0px 5px;
  font-size: 14px;
  -webkit-appearance: auto !important;
}

.custom.v-input > .v-input__control > .v-input__slot:before {
  border-style: none;
}

.custom.v-input > .v-input__control > .v-input__slot:after {
  border-style: none;
}
</style>
