import axios from "@/plugins/api";
import i18n from "@/plugins/i18n";
import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/User";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name,
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
        branch: item.branch
          ? i18n.locale !== "en"
            ? item.branch.name
            : item.branch.latin_name
          : null,
        department: item.department
          ? i18n.locale !== "en"
            ? item.department.name
            : item.department.latin_name
          : null
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/User/${id}`);
    return res.data.content[0];
  },

  async create(data) {
    const res = await axios.post("/api/User", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/User/${id}`, qs.stringify(data));
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/User/${id}`);
    return res;
  },
  async image(image, id) {
    let form = new FormData();
    form.append("image", image);
    const res = await axios.post(`/api/User/${id}/UploadImage`, form);
    return res;
  },

  async first() {
    const res = await axios.get(`/api/UserQuery/navigate?action=first`);
    return res.data.content[0];
  },

  async previous(id) {
    const res = await axios.get(
      `/api/UserQuery/navigate?action=minusOne&id=${id}`
    );
    return res.data.content[0];
  },

  async next(id) {
    const res = await axios.get(
      `/api/UserQuery/navigate?action=plusOne&id=${id}`
    );
    return res.data.content[0];
  },

  async last() {
    const res = await axios.get(`/api/UserQuery/navigate?action=last`);
    return res.data.content[0];
  }
};
