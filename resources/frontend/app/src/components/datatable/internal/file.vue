<template>
  <div class="d-flex">
    <v-btn
      icon
      x-small
      class="d-inline"
      @click="dialog = true"
      :disabled="disabled"
    >
      <v-icon>mdi-paperclip</v-icon>
    </v-btn>
    <v-dialog v-model="dialog" width="500" scrollable>
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          v-text="$t('uploadFiles')"
        ></v-card-title>

        <v-card-text class="pt-8">
          <v-btn color="primary" v-text="$t('add')" @click="add"></v-btn>
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
                <tr v-for="(item, i) in remoteFilesFilterd" :key="i">
                  <td class="text-center">{{ item.name }}</td>
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

    <div class="d-inline">
      <v-chip
        v-for="(file, i) in remoteFiles"
        :key="i"
        close
        @click:close="removeFile(i)"
        small
        class="mx-1"
      >
        <v-icon>mdi-file</v-icon>
      </v-chip>
    </div>
    <input
      type="file"
      style="display: none;"
      :id="inputId"
      @change="onFileChange"
      :accept="accept"
      multiple
    />
  </div>
</template>

<script>
import fileApi from "@/services/api/files/file.api.js";

export default {
  name: "file-cell",
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
      if (this.remoteFiles[index].status == "None")
        this.remoteFiles[index].status = "Delete";
      else if (this.remoteFiles[index].status == "Add") {
        fileApi
          .deleteFile(this.remoteFiles[index].name)
          .then(() => this.remoteFiles.splice(index, 1));
      }
      this.emitFiles();
    },
    add() {
      document.getElementById(`${this.inputId}`).click();
    },
    generateInputId() {
      this.inputId = "fileInput" + Math.round(Math.random() * 1000000);
    },
    async uploadFiles() {
      if (this.localFiles.length > 0) {
        await this.localFiles.forEach(file => {
          this.isUploading = true;
          fileApi
            .storeFile(file)
            .progress(value => (this.uploadProgress = value))
            .then(res => this.remoteFiles.push({ name: res, status: "Add" }))
            .finally(() => {
              this.isUploading = false;
              this.uploadProgress = 0;
            });
        });
        this.emitFiles();
      }
    },
    emitFiles() {
      this.$emit("input", this.remoteFiles);
    }
  },

  computed: {
    remoteFilesFilterd() {
      return this.remoteFiles.filter(e => e.status !== "Delete");
    }
  },

  // watch: {
  //   value: {
  //     handler: function(val) {
  //       if (val) {
  //         val.forEach(element => {
  //           this.remoteFiles.push({ name: element, status: "None" });
  //         });
  //       }
  //     }
  //   }
  // },

  created() {
    this.value.forEach(element => {
      this.remoteFiles.push({ name: element, status: "None" });
    });
    this.generateInputId();
  }
};
</script>

<style></style>
