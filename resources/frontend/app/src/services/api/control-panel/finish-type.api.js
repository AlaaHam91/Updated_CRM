import axios from "@/plugins/api";
//import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/FinishType";
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
    const res = await axios.get(`/api/FinishType/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    let form = new FormData();
    form.append("name", data.name);
    form.append("latin_name", data.nameL);
    const res = await axios.post("/api/FinishType", form);
    return res;
  },

  async update(id, data) {
    let form = {
      name: data.name,
      latin_name: data.nameL
    };

    const res = await axios.put(`/api/FinishType/${id}`, form);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/FinishType/${id}`);
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
    const res = await axios.get(`/api/FinishTypeQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/FinishTypeQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/FinishTypeQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/FinishTypeQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
