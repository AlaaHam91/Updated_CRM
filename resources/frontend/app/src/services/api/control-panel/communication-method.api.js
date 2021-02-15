import axios from "@/plugins/api";
import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/CommunicationMethod";
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
    const res = await axios.get(`/api/CommunicationMethod/${id}`);
    return res.data.content;
  },

  async create(data) {
    const res = await axios.post(
      "/api/CommunicationMethod",
      qs.stringify(data)
    );
    return res;
  },

  async update(id, data) {
    const res = await axios.put(
      `/api/CommunicationMethod/${id}`,
      qs.stringify(data)
    );
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/CommunicationMethod/${id}`);
    return res;
  },
  async getMobile() {
    const res = await axios.get("/api/CommunicationMethod/Mobile");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getPhone() {
    const res = await axios.get("/api/CommunicationMethod/Phone");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getFax() {
    const res = await axios.get("/api/CommunicationMethod/Fax");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getAddress() {
    const res = await axios.get("/api/CommunicationMethod/Address");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getEmail() {
    const res = await axios.get("/api/CommunicationMethod/Email");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getNote() {
    const res = await axios.get("/api/CommunicationMethod/Note");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getPosition() {
    const res = await axios.get("/api/CommunicationMethod/Position");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  },
  async getWebsite() {
    const res = await axios.get("/api/CommunicationMethod/Website");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  }
};

//cm types
// "section": "Mobile",
// "section": "Phone",
// "section": "Fax",
// "section": "Address",
// "section": "Email",
// "section": "Note",
// "section": "Position",
// "section": "Website",
