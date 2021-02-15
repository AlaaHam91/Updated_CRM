import axios from "@/plugins/api";
import qs from "qs";
import i18n from "@/plugins/i18n";

const locale = i18n.locale;

export default {
  async getAll(params = null) {
    let url = "/api/Tickets/DailyReports";
    if (params) url += params;
    const res = await axios.get(url);
    return res.data.message.map((item, i) => {
      return {
        ...item,
        id: i,
        company: locale == "en" ? item.company.latin_name : item.company.name,
        requestBy:
          locale == "en" ? item.request_by.latin_name : item.request_by.name,
        employee:
          locale == "en" ? item.employee.latin_name : item.employee.name,
        executionStatus:
          locale == "en"
            ? item.execution_status.latin_name
            : item.execution_status.name,
        executionCase:
          locale == "en"
            ? item.execution_case.latin_name
            : item.execution_case.name
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
