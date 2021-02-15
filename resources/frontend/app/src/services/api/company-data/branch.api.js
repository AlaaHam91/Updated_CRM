import axios from "@/plugins/api";
//import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/Branch";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        latin_name: item.name ? item.name.en : null
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/Branch/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Branch", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Branch/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Branch/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      id: res.id
    };
  },
  async first() {
    const res = await axios.get(`/api/BranchQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/BranchQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/BranchQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/BranchQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
