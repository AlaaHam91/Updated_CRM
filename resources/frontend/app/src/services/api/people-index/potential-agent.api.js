import axios from "@/plugins/api";
import qs from "qs";
import i18n from "@/plugins/i18n.js";

export default {
  async getAll(payload = null) {
    let url = "/api/PotentialAgent";
    if (payload) url += payload;

    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name : null,
        nameL: item.latin_name ? item.latin_name : null
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/PotentialAgent/${id}`);
    return res.data.content[0];
  },

  async first(id) {
    const res = await axios.get(
      `/api/PotentialAgentQuery/navigate?action=first&id=${id}`
    );
    return res.data.content[0];
  },

  async previous(id) {
    const res = await axios.get(
      `/api/PotentialAgentQuery/navigate?action=minusOne&id=${id}`
    );
    return res.data.content[0];
  },

  async next(id) {
    const res = await axios.get(
      `/api/PotentialAgentQuery/navigate?action=plusOne&id=${id}`
    );
    return res.data.content[0];
  },

  async last(id) {
    const res = await axios.get(
      `/api/PotentialAgentQuery/navigate?action=last&id=${id}`
    );
    return res.data.content[0];
  },

  async create(data) {
    const res = await axios.post("/api/PotentialAgent", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(
      `/api/PotentialAgent/${id}`,
      qs.stringify(data)
    );
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/PotentialAgent/${id}`);
    return res;
  },

  async image(image, id) {
    let form = new FormData();
    form.append("image", image);
    const res = await axios.post(`/api/PotentialAgent/${id}/UploadImage`, form);
    return res;
  },

  async getTickets(id) {
    const res = await axios.get(`/api/PotentialAgent/${id}/Tickets`);
    return res.data.message.map(item => {
      return {
        ...item,
        company:
          i18n.locale == "en" ? item.company.latin_name : item.company.name,
        requestBy:
          i18n.locale == "en"
            ? item.request_by.latin_name
            : item.request_by.name,
        department:
          i18n.locale == "en"
            ? item.department.latin_name
            : item.department.name,
        branch: i18n.locale == "en" ? item.branch.latin_name : item.branch.name,
        ordertype:
          i18n.locale == "en"
            ? item.order_type.latin_name
            : item.order_type.name,
        title: item.title,
        date: item.date,
        time: item.time
      };
    });
  },
  async getFolders(id) {
    const res = await axios.get(`/api/PotentialAgent/${id}/Archive`);
    return res.data.content;
  },

  async getFilesByFolderID(id, folderId) {
    const res = await axios.get(
      `/api/PotentialAgent/${id}/Archive/${folderId}`
    );
    return res.data.content;
  },
  async getRoot(id) {
    const res = await axios.get(`/api/PotentialAgent/${id}/Archive/root`);
    return res.data.content;
  },

  async addItem(id, data) {
    const res = await axios.post(`/api/PotentialAgent/${id}/Archive`, data);
    return res.data.content;
  },

  async updateItem(id, itemId, data) {
    const res = await axios.put(
      `/api/PotentialAgent/${id}/Archive/${itemId}`,
      data
    );
    return res.data.content;
  },

  async deleteFile(id, fileId) {
    const res = await axios.delete(
      `/api/PotentialAgent/${id}/Archive/${fileId}`
    );
    return res.data.content;
  }
};
