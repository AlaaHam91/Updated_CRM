import axios from "@/plugins/api";

export default {
  async getAll() {
    const res = await axios.get("/api/CompanyPeople");
    return res.data.content;
  }
};
