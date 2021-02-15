import axios from "@/plugins/api";
import qs from "qs";

export default {
  async getAll() {
    const res = await axios.get("/api/AdditionalInfo");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/AdditionalInfo/${id}`);
    return res.data.content;
  },

  async getFor(section) {
    const res = await axios.get(`/api/AdditionalInfoFor/${section}`);
    return res.data.content;
  },

  async first(id) {
    const res = await axios.get(
      `/api/AdditionalInfoQuery/navigate?action=first&id=${id}`
    );
    return res.data.content;
  },

  async previous(id) {
    const res = await axios.get(
      `/api/AdditionalInfoQuery/navigate?action=minusOne&id=${id}`
    );
    return res.data.content;
  },

  async next(id) {
    const res = await axios.get(
      `/api/AdditionalInfoQuery/navigate?action=plusOne&id=${id}`
    );
    return res.data.content;
  },

  async last(id) {
    const res = await axios.get(
      `/api/AdditionalInfoQuery/navigate?action=last&id=${id}`
    );
    return res.data.content;
  },

  async create(data) {
    const res = await axios.post(
      "/api/AdditionalInfoGroup",
      qs.stringify(data)
    );
    return res;
  },

  async delete(id) {
    let data = {
      "additional_infos[0][additional_field]": id,
      "additional_infos[0][status]": "Delete"
    };
    const res = await axios.post(
      "/api/AdditionalInfoGroup",
      qs.stringify(data)
    );
    return res;
  }
};
