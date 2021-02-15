import axios from "@/plugins/api";
//import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/Role";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async get(id) {
    const res = await axios.get(`/api/Role/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Role", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Role/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Role/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name,
      nameL: res.display_name
    };
  },
  async first() {
    const res = await axios.get(`/api/RoleQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/RoleQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/RoleQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/RoleQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
