import axios from "@/plugins/api";

export default {
  async getAll(payload = null) {
    let url = "/api/Document";
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
    const res = await axios.get(`/api/Document/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Document", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Document/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Document/${id}`);
    return res;
  },

  loadForm(res) {
    if (res === undefined) return {};

    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      isRequired: res.is_required,
      hasExpiry: res.has_expired_date
    };
  },
  async first() {
    const res = await axios.get(`/api/DocumentQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0][0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/DocumentQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0][0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/DocumentQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0][0]);
  },

  async last() {
    const res = await axios.get(`/api/DocumentQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0][0]);
  }
};
