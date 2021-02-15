import axios from "@/plugins/api";

export default {
  async getAll(payload = null) {
    let url = "/api/EmployeeFiles";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content;
  },

  async getTree() {
    const res = await axios.get("/api/EmployeeFilesTree");
    let result = JSON.parse(
      JSON.stringify(res.data.content)
        .replace(/"text"/g, '"name"')
        .replace(/"nodes"/g, '"children"')
        .replace(/"id"/g, '"nodeId"')
    );
    let i = 1;
    result.forEach(item => {
      item.id = i;
      item.children.forEach(child => {
        i++;
        child.id = i;
      });
    });
    return result;
  },
  async get(data) {
    const res = await axios.post("/api/getAllData", data);
    return res.data.content;
  },
  async delete(id) {
    const res = await axios.post("/api/deleteAnyData", id);
    return res.data.content;
  },
  async download(url) {
    const res = await axios.get(`/api/downloadFileCp/${url}`);
    return res;
  },
  async sendFile(data) {
    const res = await axios.post("/api/sendFileData", data);
    return res.data.content;
  },
  async sendFolder(data) {
    const res = await axios.post("/api/sendFolderData", data);
    return res.data.content;
  },
  async sendUrl(data) {
    const res = await axios.post("/api/sendUrlData", data);
    return res.data.content;
  },
  async getFolders(data) {
    const res = await axios.post("/api/getAllFolders", data);
    return res.data.content;
  },
  async getFolderData(data) {
    const res = await axios.post("/api/getFolderData", data);
    return res.data.content;
  },
  async getRoot() {
    let data = { table: "myFiles" };

    const res = await axios.post("/api/getFolderData", data);
    return res.data;
  }
};
