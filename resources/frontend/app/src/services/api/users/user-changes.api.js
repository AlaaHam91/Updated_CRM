import axios from "@/plugins/api";

export default {
  async getUnFinishedChanges(payload = null) {
    let url = "/api/getEmployeeUnfinishedChanges";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content;
  },
  async getFinishedChanges(payload = null) {
    let url = "/api/getEmployeeFinishedChanges";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content;
  },

  async SendRequest(data) {
    const res = await axios.post(
      "/api/sendAcceptOrDeclineEmployeeRequest",
      data
    );
    return res;
  }
};
