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
          @click:close="removeFile(data.index)"
          :key="JSON.stringify(data.index)"
          v-bind="data.attrs"
          small
          class="mx-1"
        >
          <v-icon>mdi-file</v-icon>
        </v-chip>
      </template>
    </v-combobox>
    <v-btn color="" v-text="$t('add')" class="ms-2" @click="dialog = true">
    </v-btn>

    <input
      type="file"
      style="display: none;"
      :id="inputId"
      @change="onFileChange"
      :accept="accept"
      multiple
    />

    <v-dialog v-model="dialog" width="500" scrollable>
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

          <v-simple-table
            fixed-header
            height="200px"
            v-if="remoteFiles.length > 0"
          >
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center" v-text="$t('name')"></th>
                  <th class="text-center" v-text="$t('actions')"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in remoteFiles" :key="i">
                  <td>{{ item }}</td>
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
          <v-btn
            color="primary"
            text
            @click="dialog = false"
            v-text="$t('close')"
          >
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import fileApi from "@/services/api/files/file.api.js";

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
      remoteFiles: []
    };
  },

  methods: {
    onFileChange(e) {
      this.localFiles = Array.from(e.target.files);
      this.uploadFiles();
    },
    removeFile(index) {
      fileApi
        .deleteFile(this.remoteFiles[index])
        .then(() => {
          this.remoteFiles.splice(index, 1);
          this.$emit("input", this.remoteFiles);
        })
        .catch(() => {
          //
        });
    },
    add() {
      document.getElementById(`${this.inputId}`).click();
    },
    generateInputId() {
      this.inputId = "fileInput" + Math.round(Math.random() * 1000000);
    },
    async uploadFiles() {
      if (this.localFiles.length > 0)
        await this.localFiles.forEach(file => {
          this.isUploading = true;
          fileApi
            .storeFile(file)
            .progress(value => (this.uploadProgress = value))
            .then(res => this.remoteFiles.push(res))
            .finally(() => {
              this.isUploading = false;
              this.uploadProgress = 0;
              this.$emit("input", this.remoteFiles);
            });
        });
    }
  },

  created() {
    this.generateInputId();
    if (this.value) this.remoteFiles = this.value.slice(0);
  }
};
</script>

<style></style>
