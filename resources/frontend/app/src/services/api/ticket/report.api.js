import axios from "@/plugins/api";
import qs from "qs";
import i18n from "@/plugins/i18n";

const locale = i18n.locale;

export default {
  async getAll(params = null) {
    let url = "/api/Calender";
    if (params) url += params;
    const res = await axios.get(url);
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

  async get(id) {
    const res = await axios.get(`/api/Tickets/DailyReports/${id}`);
    return res.data.content;
  },

  async create(data) {
    const res = await axios.post(
      "/api/Tickets/DailyReports",
      qs.stringify(data)
    );
    return res;
  },

  async todoList(params) {
    let url = `/api/Tickets/ToDoList`;
    if (params) url += params;

    const res = await axios.get(url);
    return res.data.message.map(item => {
      return {
        ...item,
        company: locale == "en" ? item.company.latin_name : item.company.name,
        requestBy:
          locale == "en" ? item.request_by.latin_name : item.request_by.name
      };
    });
  }
};
