import axios from "@/plugins/api";
//import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/JobType";
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
    const res = await axios.get(`/api/JobType/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/JobType", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/JobType/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/JobType/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null
    };
  },
  async first() {
    const res = await axios.get(`/api/JobTypeQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/JobTypeQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/JobTypeQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/JobTypeQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
