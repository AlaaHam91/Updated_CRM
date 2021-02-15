import axios from "@/plugins/api";
import qs from "qs";
import i18n from "./../../../plugins/i18n";

export default {
  async getAll(payload = null) {
    let url = "/api/Department";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        latin_name: item.name ? item.name.en : null,

        parent_name: item.parent_name
          ? i18n.locale == "en"
            ? item.parent_name.en
            : item.parent_name.ar
          : null
      };
    });
  },
  async get(id) {
    const res = await axios.get(`/api/Department/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Department", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Department/${id}`, qs.stringify(data));
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Department/${id}`);
    return res;
  },
  async getParents() {
    const res = await axios.get("/api/DepartmentParent");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null
      };
    });
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      type: res.type,
      parent: res.parent_id,
      place: res.place == 1 ? true : false
    };
  },
  async first() {
    const res = await axios.get(`/api/DepartmentQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/DepartmentQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/DepartmentQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/DepartmentQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
