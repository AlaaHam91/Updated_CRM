<template>
  <v-row no-gutters>
    <v-col md="9" class="d-flex align-center">
      <v-tooltip top open-delay="1500">
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            prepend-inner-icon="mdi-magnify"
            outlined
            dense
            v-model="value"
            @keyup.enter="setFilter"
            @click:clear="clearFilter"
            @click:prepend-inner="setFilter"
            clearable
            single-line
            hide-details
            v-bind="attrs"
            v-on="on"
            autocomplete="off"
            spellcheck="off"
          ></v-text-field>
        </template>
        <span v-text="$t('searchTip')"></span>
      </v-tooltip>
    </v-col>
    <v-col md="3" class="d-flex align-center">
      <v-select
        :items="items"
        outlined
        dense
        :item-text="$i18n.locale == 'en' ? 'labelEn' : 'labelAr'"
        item-value="value"
        v-model="operator"
        @input="setFilter"
        class="ms-1"
        hide-details
      >
        <template v-slot:selection="{ item }">
          <span
            class="d-flex justify-center font-weight-regular"
            style="width: 100%;"
          >
            {{ item.display }}
          </span>
        </template>
      </v-select>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "search-filter",

  data() {
    return {
      value: null,
      operator: "EqualTo",
      items: [
        {
          labelEn: "==  Equal",
          labelAr: "==  مساوي",
          display: "==",
          value: "EqualTo"
        },
        {
          labelEn: "?  Contain",
          labelAr: "?  يحوي",
          display: "?",
          value: "Contain"
        },
        {
          labelEn: "^  Start with",
          labelAr: "^  يبدأ",
          display: "^",
          value: "StartWith"
        },
        {
          labelEn: "$  End with",
          labelAr: "$  ينتهي",
          display: "$",
          value: "EndWith"
        },
        {
          labelEn: "==  Not equal",
          labelAr: "==  لا يساوي",
          display: "==",
          value: "NotEqualTo"
        },
        {
          labelEn: "<  Less",
          labelAr: "<  أصغر",
          display: "<",
          value: "Less"
        },
        {
          labelEn: "<=  Less or Equal",
          labelAr: "<=  أصغر أو يساوي",
          display: "<=",
          value: "LessOrEqual"
        },
        {
          labelEn: ">  Greater",
          labelAr: "أكبر >",
          display: "<",
          value: "Greater"
        },
        {
          labelEn: ">=  Greater or Equal",
          labelAr: ">=  أكبر أو يساوي",
          display: ">=",
          value: "GreaterOrEqual"
        },
        {
          labelEn: "!^  Not start with",
          labelAr: "!^  لا يبدأ",
          display: "!^",
          value: "NotStartWith"
        }
      ]
    };
  },

  methods: {
    setFilter() {
      if (this.operator && this.value)
        this.$emit("set-filter", {
          value: this.value,
          operator: this.operator
        });
      else this.$emit("set-filter", null);
    },
    clearFilter() {
      this.$emit("set-filter", null);
    }
  }
};
</script>

<style scoped></style>
