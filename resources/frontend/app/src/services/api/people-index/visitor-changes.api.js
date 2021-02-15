import axios from "@/plugins/api";

export default {
  async getUnFinishedChanges(payload = null) {
    let url = "/api/getVisitorUnfinishedChanges";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content;
  },
  async getFinishedChanges(payload = null) {
    let url = "/api/getVisitorFinishedChanges";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content;
  },

  async SendRequest(data) {
    const res = await axios.post(
      "/api/sendAcceptOrDeclineVisitorRequest",
      data
    );
    return res;
  }
};
