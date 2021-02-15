import axios from "@/plugins/api";
import i18n from "@/plugins/i18n.js";

export default {
  async getAll(payload = null) {
    let url = "/api/SourceIndex";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name,
        parent: i18n.locale == "en" ? item.latin_name : item.name
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/SourceIndex/${id}`);
    return this.loadForm(res.data.content);
  },

  async getMain() {
    const res = await axios.get("/api/SourceIndex/Main");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },

  async getBranch() {
    const res = await axios.get("/api/SourceIndex/Branch");
    return res.data.content;
  },

  async create(data) {
    const res = await axios.post("/api/SourceIndex", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/SourceIndex/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/SourceIndex/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name,
      nameL: res.latin_name,
      type: res.type,
      parent: res.parent_id
    };
  },
  async first() {
    const res = await axios.get(`/api/SourceIndexQuery/navigate?action=first`);
    return this.loadForm(res.data.content);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/SourceIndexQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content);
  },

  async next(id) {
    const res = await axios.get(
      `/api/SourceIndexQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content);
  },

  async last() {
    const res = await axios.get(`/api/SourceIndexQuery/navigate?action=last`);
    return this.loadForm(res.data.content);
  }
};
