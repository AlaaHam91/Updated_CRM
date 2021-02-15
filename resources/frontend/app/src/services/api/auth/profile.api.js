import axios from "@/plugins/api";
// import qs from "qs";

export default {
  async get() {
    const res = await axios.get("/api/Profile");
    return res.data.content;
  },

  async update(form) {
    const res = await axios.post("/api/Profile", form);
    return res;
  }
};
