<template>
  <div>
    <div v-if="fields.length > 0">
      <v-row no-gutters v-for="(item, i) in $v.fields.$each.$iter" :key="i">
        <v-col cols="12">
          <div
            class="text-body-2 text-no-wrap black--text mb-2"
            v-text="
              $i18n.locale == 'en'
                ? item.$model.type.latin_name
                : item.$model.type.name
            "
          ></div>
          <v-text-field
            v-if="item.$model.type.type == 'Text'"
            v-model="item.val.$model"
            outlined
            dense
            placeholder="Text Field"
          ></v-text-field>
          <v-text-field
            v-if="item.$model.type.type == 'Password'"
            type="password"
            v-model="item.val.$model"
            outlined
            dense
            placeholder="Password"
          ></v-text-field>
          <v-text-field
            v-if="item.$model.type.type == 'Email'"
            v-model="item.val.$model"
            outlined
            dense
            placeholder="Email"
          ></v-text-field>
          <quill-editor
            v-if="item.$model.type.type == 'RichEditor'"
            v-model="item.val.$model"
          />
          <v-menu
            v-if="item.$model.type.type == 'Date'"
            v-model="dateMenu"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="item.val.$model"
                outlined
                dense
                readonly
                v-bind="attrs"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              :locale="$i18n.locale"
              v-model="item.val.$model"
              @input="dateMenu = false"
            ></v-date-picker>
          </v-menu>
          <v-menu
            v-if="item.$model.type.type == 'Time'"
            ref="timeMenu"
            v-model="timeMenu"
            :close-on-content-click="false"
            :nudge-right="40"
            :return-value.sync="time"
            transition="scale-transition"
            offset-y
            max-width="290px"
            min-width="290px"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="item.val.$model"
                outlined
                dense
                readonly
                v-bind="attrs"
                v-on="on"
                hide-details
              ></v-text-field>
            </template>
            <v-time-picker
              v-if="timeMenu"
              v-model="item.val.$model"
              full-width
              format="24hr"
              @click:minute="$refs.timeMenu.save(item.val.$model)"
            ></v-time-picker>
          </v-menu>

          <v-text-field
            v-if="item.$model.type.type == 'Number'"
            v-model="item.val.$model"
            @input="$v.$touch"
            type="number"
            outlined
            dense
          ></v-text-field>
          <v-select
            v-if="item.$model.type.type == 'Select'"
            v-model="item.val.$model"
            :items="item.$model.type.config.options"
            :item-text="$i18n.locale == 'en' ? 'latin_name' : 'name'"
            item-value="id"
            outlined
            dense
          ></v-select>
          <v-checkbox
            v-model="item.val.$model"
            v-if="item.$model.type.type == 'CheckGroup'"
          ></v-checkbox>
          <v-radio-group
            v-if="item.$model.type.type == 'RadioGroup'"
            v-model="item.val.$model"
          >
            <v-radio label="RadioGroup"></v-radio>
          </v-radio-group>
          <v-file-input
            v-if="item.$model.type.type == 'FileUploader'"
            v-model="item.val.$model"
            outlined
            dense
            prepend-icon="mdi-paperclip"
          ></v-file-input>
          <small class="mr-4 red--text" v-if="!item.val.isOk">
            {{ $t("required") }}
          </small>
        </v-col>
      </v-row>
    </div>
  </div>
</template>

<script>
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

import {} from "vuelidate/lib/validators";
const isOk = (value, field) => {
  let isRequired =
    field.type.access[0].access_type.required == "Required" ? true : false;
  if (isRequired && !value) return false;
  else if (!isRequired) return true;
};

export default {
  name: "user-fields-viewer",

  components: {
    quillEditor
  },

  props: {
    types: { type: Array, required: true },
    value: undefined,
    touch: { type: Boolean, default: false, required: true }
  },

  data() {
    return {
      fields: [],
      dateMenu: false,
      timeMenu: false
    };
  },

  validations: {
    fields: {
      $each: {
        val: {
          isOk
        }
      }
    }
  },

  created() {
    if (this.types.length > 0) {
      this.types.forEach(e => {
        this.fields.push(e);
      });

      if (this.value.length > 0) {
        this.value.forEach(element => {
          let index = this.fields.findIndex(f => f.type.id == element.type.id);
          if (index > -1) this.fields[index].val = element.val;
        });
      }
    }
  },

  watch: {
    touch: {
      handler: function(val) {
        if (val) this.$v.$touch();
        else this.$v.$reset();
      },
      immediate: true
    },
    fields: {
      handler: function(val) {
        this.$emit("input", val);
      },
      deep: true
    }
  }
};
</script>

<style></style>
