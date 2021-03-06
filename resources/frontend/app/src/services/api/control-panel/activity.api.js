import axios from "@/plugins/api";

export default {
  async getAll(payload = null) {
    let url = "/api/Activity";
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
    const res = await axios.get(`/api/Activity/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Activity", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Activity/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Activity/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      description: res.description ? res.description.ar : null
    };
  },
  async first() {
    const res = await axios.get(`/api/ActivityQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/ActivityQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/ActivityQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/ActivityQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
