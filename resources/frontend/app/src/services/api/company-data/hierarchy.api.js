import axios from "@/plugins/api";
import i18n from "./../../../plugins/i18n";

export default {
  async getAll(payload = null) {
    let url = "/api/Hierarchy";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        latin_name: item.name ? item.name.en : null,
        parentPoint: item.parent_name
          ? i18n.locale == "en"
            ? item.parent_name.ar
            : item.parent_name.en
          : null
      };
    });
  },

  async getTree() {
    const res = await axios.get("/api/HierarchyTree");
    let result = JSON.parse(
      JSON.stringify(res.data.content[0]).replace(/"text"/g, '"name"')
    );
    return result;
  },
  async get(id) {
    const res = await axios.get(`/api/Hierarchy/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Hierarchy", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Hierarchy/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Hierarchy/${id}`);
    return res;
  },

  loadForm(res) {
    if (res === undefined) return {};
    let hierarchy = [];
    hierarchy.push(res.parent_id);
    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      type: res.type,
      hierarchy
    };
  },
  async first() {
    const res = await axios.get(`/api/HierarchyQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/HierarchyQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/HierarchyQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/HierarchyQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
