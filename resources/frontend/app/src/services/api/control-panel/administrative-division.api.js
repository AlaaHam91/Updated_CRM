import axios from "@/plugins/api";
import i18n from "./../../../plugins/i18n";

export default {
  async getAll(payload = null) {
    let url = "/api/AdminstrativeDivision";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        latin_name: item.name ? item.name.en : null,
        country: item.country_name
          ? i18n.locale == "en"
            ? item.country_name.en
            : item.country_name.ar
          : null,
        level: item.ad_level_name,
        parent: item.parent_name
          ? i18n.locale == "en"
            ? item.parent_name.en
            : item.parent_name.ar
          : null
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/AdminstrativeDivision/${id}`);
    return res.data.content[0];
  },

  async create(data) {
    const res = await axios.post("/api/AdminstrativeDivision", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/AdminstrativeDivision/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/AdminstrativeDivision/${id}`);
    return res;
  },

  async getByCountry(countryId) {
    const res = await axios.get(
      `/api/AdminstrativeDivisionByCountry/${countryId}`
    );
    return res.data.content[0].map(item => {
      return {
        ...item,
        name: item.text ? item.text.ar : null,
        nameL: item.text ? item.text.en : null
      };
    });
  },
  async getParents(adLevelId) {
    const res = await axios.get(
      `/api/AdminstrativeDivisionParent/${adLevelId}`
    );
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null
      };
    });
  },
  async getLevelsByCountry(countryId) {
    const res = await axios.get(
      `/api/AdminstrativeDivisionLevelByCountry/${countryId}`
    );
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.level,
        isCoding: item.is_coding == 1 ? true : true,
        isParent: item.level == 1 ? false : true
      };
    });
  },
  async getPrefixes(id) {
    const res = await axios.get(`/api/AdminstrativeDivision/prefix/${id}`);

    return res.data.content.map(item => {
      return {
        id: item.id,
        prefix: item.prefix
      };
    });
  },
  async getDivisionTypes() {
    const res = await axios.get("/api/AdminstrativeDivisionType");
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        value: item.id
      };
    });
  },
  async first() {
    const res = await axios.get(
      `/api/AdminstrativeDivisionQuery/navigate?action=first`
    );
    return res.data.content[0];
  },

  async previous(id) {
    const res = await axios.get(
      `/api/AdminstrativeDivisionQuery/navigate?action=minusOne&id=${id}`
    );
    return res.data.content[0];
  },

  async next(id) {
    const res = await axios.get(
      `/api/AdminstrativeDivisionQuery/navigate?action=plusOne&id=${id}`
    );
    return res.data.content[0];
  },

  async last() {
    const res = await axios.get(
      `/api/AdminstrativeDivisionQuery/navigate?action=last`
    );
    return res.data.content[0];
  }
};
