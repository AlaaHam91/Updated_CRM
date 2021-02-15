import axios from "@/plugins/api";
import qs from "qs";
const ProgressPromise = require("progress-promise");

export default {
  storeFile(file) {
    return new ProgressPromise((resolve, reject, progress) => {
      if (!file) reject();
      const config = {
        onUploadProgress: progressEvent =>
          progress(
            Math.round((progressEvent.loaded * 100) / progressEvent.total)
          )
      };

      let form = new FormData();
      form.append("file", file);

      axios
        .post(`/api/StoreFile`, form, config)
        .then(res => {
          resolve(res.data.content);
        })
        .catch(err => reject(err));
    });
  },

  async deleteFile(name) {
    let data = {
      file_name: name
    };
    const res = await axios.post(`/api/DeleteFile`, qs.stringify(data));
    return res;
  },

  //get image file
  async getFile(name) {
    const res = await axios({
      method: "get",
      url: `/api/Files/${name}`,
      responseType: "blob"
    });

    return res;
  }
};
