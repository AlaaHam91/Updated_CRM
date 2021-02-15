import axios from "@/plugins/api";
import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/Acquaintance";
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
    const res = await axios.get(`/api/Acquaintance/${id}`);
    return res.data.content;
  },

  async first(id) {
    const res = await axios.get(
      `/api/AcquaintanceQuery/navigate?action=first&id=${id}`
    );
    return res.data.content;
  },

  async previous(id) {
    const res = await axios.get(
      `/api/AcquaintanceQuery/navigate?action=minusOne&id=${id}`
    );
    return res.data.content;
  },

  async next(id) {
    const res = await axios.get(
      `/api/AcquaintanceQuery/navigate?action=plusOne&id=${id}`
    );
    return res.data.content;
  },

  async last(id) {
    const res = await axios.get(
      `/api/AcquaintanceQuery/navigate?action=last&id=${id}`
    );
    return res.data.content;
  },

  async create(data) {
    const res = await axios.post("/api/Acquaintance", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Acquaintance/${id}`, qs.stringify(data));
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Acquaintance/${id}`);
    return res;
  },

  async image(image, id) {
    let form = new FormData();
    form.append("image", image);
    const res = await axios.post(`/api/Acquaintance/${id}/UploadImage`, form);
    return res;
  }
};
