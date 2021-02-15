import axios from "@/plugins/api";

export default {
  async getAll(payload = null) {
    let url = "/api/Language";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        latin_name: item.name ? item.name.en : null,
        notes: item.notes ? item.notes.ar : null,
        id: item.id
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/Language/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Language", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Language/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Language/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      note: res.notes ? res.notes.ar : null,
      isFavorite: res.is_favorite
    };
  },
  async first() {
    const res = await axios.get(`/api/LanguageQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/LanguageQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/LanguageQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/LanguageQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
