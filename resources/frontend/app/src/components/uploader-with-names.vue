<template>
  <div class="d-flex">
    <v-combobox
      prepend-icon="mdi-paperclip"
      append-icon=""
      @click:prepend="dialog = true"
      @click="dialog = true"
      v-model="remoteFiles"
      multiple
      dense
      outlined
      chips
      readonly
    >
      <template v-slot:selection="data">
        <v-chip
          close
          :key="JSON.stringify(data.item)"
          v-bind="data.attrs"
          small
          class="mx-1"
        >
          <v-icon>mdi-file</v-icon>
        </v-chip>
      </template>
    </v-combobox>
    <v-btn color="" v-text="$t('add')" class="ml-2" @click="dialog = true">
    </v-btn>

    <input
      type="file"
      style="display: none;"
      :id="inputId"
      @change="onFileChange"
      :accept="accept"
      multiple
    />

    <v-dialog v-model="dialog" width="500" scrollable persistent>
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          v-text="$t('uploadFiles')"
        ></v-card-title>

        <v-card-text class="pt-8">
          <v-btn
            :disabled="disabled"
            color="primary"
            v-text="$t('add')"
            @click="add"
          ></v-btn>
          <v-progress-linear
            class="my-4"
            v-if="isUploading"
            :value="uploadProgress"
          ></v-progress-linear>

          <v-simple-table fixed-header height="200px" v-if="files.length > 0">
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center" v-text="$t('name')"></th>
                  <th class="text-center" v-text="$t('alias')"></th>
                  <th class="text-center" v-text="$t('actions')"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in $v.files.$each.$iter" :key="i">
                  <td>{{ item.$model.name }}</td>
                  <td>
                    <v-text-field
                      v-model="item.$model.alias"
                      outlined
                      dense
                      autocomplete="off"
                      hide-details
                    ></v-text-field>
                    <div
                      class="red--text text-body-2"
                      v-if="!item.alias.required"
                      v-text="$t('required')"
                    ></div>
                  </td>

                  <td class="text-center">
                    <v-btn small color="error" icon @click="removeFile(i)">
                      <v-icon>mdi-close</v-icon>
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="close" v-text="$t('close')">
          </v-btn>

          <v-btn
            color="primary"
            v-show="files.length > 0"
            text
            @click="save"
            v-text="$t('save')"
          >
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import fileApi from "@/services/api/files/file.api.js";
import { required } from "vuelidate/lib/validators";
import { cloneDeep } from "lodash";

export default {
  name: "uploader",
  props: {
    accept: String,
    disabled: Boolean,
    value: undefined
  },
  data() {
    return {
      dialog: false,
      isUploading: false,
      uploadProgress: 0,
      inputId: null,
      localFiles: [],
      remoteFiles: [],
      files: []
    };
  },
  validations: {
    files: {
      $each: {
        alias: { required }
      }
    }
  },

  methods: {
    onFileChange(e) {
      this.localFiles = Array.from(e.target.files);
      this.uploadFiles();
    },
    removeFile(index) {
      this.remoteFiles.splice(index, 1);
      this.files.splice(index, 1);
      this.$emit("input", this.remoteFiles);
    },
    add() {
      document.getElementById(`${this.inputId}`).click();
    },
    generateInputId() {
      this.inputId = "fileInput" + Math.round(Math.random() * 1000000);
    },
    save() {
      this.$v.$touch();
      if (this.$v.$invalid) return;
      this.remoteFiles = cloneDeep(this.files);
      this.$emit("input", this.remoteFiles);
      this.dialog = false;
    },
    close() {
      this.files = [];
      this.dialog = false;
    },
    async uploadFiles() {
      if (this.localFiles.length > 0)
        await this.localFiles.forEach(file => {
          this.isUploading = true;
          fileApi
            .storeFile(file)
            .progress(value => (this.uploadProgress = value))
            .then(res => this.files.push({ name: res }))
            .catch(() => {})
            .finally(() => {
              this.isUploading = false;
              this.uploadProgress = 0;
            });
        });
    }
  },

  created() {
    this.generateInputId();
    if (this.value) this.remoteFiles = this.value.slice(0);
    this.files = cloneDeep(this.remoteFiles);
  }
};
</script>

<style></style>
