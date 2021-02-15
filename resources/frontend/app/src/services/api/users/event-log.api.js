import axios from "@/plugins/api";
import i18n from "@/plugins/i18n";

export default {
  async getAll(payload = null) {
    let url = "/api/EventLog";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        user:
          item.user !== null
            ? i18n.locale === "en"
              ? item.user.name
              : item.user.latin_name
            : null
      };
    });
  }
};
