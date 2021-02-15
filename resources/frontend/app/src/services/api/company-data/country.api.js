import axios from "@/plugins/api";
import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/Country";
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
    const res = await axios.get(`/api/Country/${id}`);
    return this.loadForm(res.data.content);
  },

  async create(data) {
    const res = await axios.post("/api/Country", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Country/${id}`, qs.stringify(data));
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Country/${id}`);
    return res;
  },

  async getPrefixes(id) {
    const res = await axios.get(`/api/Country/prefix/${id}`);
    return res.data.content.map(item => {
      return {
        id: item.id,
        prefix: item.prefix
      };
    });
  },
  loadForm(result) {
    if (result === undefined) return {};
    return {
      id: result[0].id,
      name: result[0].name ? result[0].name.ar : null,
      nameL: result[0].name ? result[0].name.en : null,
      code: result[0].code,
      isDefaultCountry: result[0].default_country == 1 ? true : false,
      isActive: result[0].is_active == 1 ? true : false,
      divisionItems: result["adLevels"].map(element => {
        return element.map(item => {
          return {
            level: item.level,
            isCoding: item.is_coding == 1 ? true : false,
            codingType: item.is_coding_required,
            type: item.ad_type_id,
            adId: item.id
          };
        });
      })[0]
    };
  },
  async first() {
    const res = await axios.get(`/api/CountryQuery/navigate?action=first`);
    return this.loadForm(res.data.content);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/CountryQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content);
  },

  async next(id) {
    const res = await axios.get(
      `/api/CountryQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content);
  },

  async last() {
    const res = await axios.get(`/api/CountryQuery/navigate?action=last`);
    return this.loadForm(res.data.content);
  }
};
