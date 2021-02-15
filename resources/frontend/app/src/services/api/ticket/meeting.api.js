import axios from "@/plugins/api";
import qs from "qs";
// import i18n from "@/plugins/i18n";

// const locale = i18n.locale;

export default {
  async create(data) {
    const res = await axios.post("/api/Meeting", qs.stringify(data));
    return res;
  }
};
