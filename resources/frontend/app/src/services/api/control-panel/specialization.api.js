import axios from "@/plugins/api";

export default {
  async getAll(payload = null) {
    let url = "/api/Specialization";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name,
        nameL: item.latin_name
      };
    });
  }
};
