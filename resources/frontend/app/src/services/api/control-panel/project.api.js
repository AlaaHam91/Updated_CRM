import axios from "@/plugins/api";
import i18n from "../../../plugins/i18n";
import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/Project";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name.ar,
        nameL: item.name.en,
        latin_name: item.name ? item.name.en : null,
        email: item.email,
        job: item.job
          ? i18n.locale !== "en"
            ? item.job.name
            : item.job.latin_name
          : null,
        manager: item.manager
          ? i18n.locale !== "en"
            ? item.manager.name
            : item.manager.latin_name
          : null,
        branch: item.banch_name
          ? i18n.locale !== "en"
            ? item.banch_name.ar
            : item.banch_name.en
          : null,
        department: item.department_name
          ? i18n.locale !== "en"
            ? item.department_name.ar
            : item.department_name.en
          : null,
        customer: item.customer_name
          ? i18n.locale !== "en"
            ? item.customer_name.ar
            : item.customer_name.en
          : null,
        employee: item.employee_name
          ? i18n.locale !== "en"
            ? item.employee_name.ar
            : item.employee_name.en
          : null
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/Project/${id}`);
    return res.data.content[0];
  },

  async create(data) {
    const res = await axios.post("/api/Project", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Project/${id}`, qs.stringify(data));
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Project/${id}`);
    return res;
  },

  async first() {
    const res = await axios.get(`/api/ProjectQuery/navigate?action=first`);
    return res.data.content[0][0];
  },

  async previous(id) {
    const res = await axios.get(
      `/api/ProjectQuery/navigate?action=minusOne&id=${id}`
    );
    return res.data.content[0][0];
  },

  async next(id) {
    const res = await axios.get(
      `/api/ProjectQuery/navigate?action=plusOne&id=${id}`
    );
    return res.data.content[0][0];
  },

  async last() {
    const res = await axios.get(`/api/ProjectQuery/navigate?action=last`);
    return res.data.content[0][0];
  }
};
