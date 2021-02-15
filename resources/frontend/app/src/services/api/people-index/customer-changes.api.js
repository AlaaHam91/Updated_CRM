import axios from "@/plugins/api";

export default {
  async getUnFinishedChanges() {
    const res = await axios.get("/api/getCustomerUnfinishedChanges");
    return res.data.content.map(item => ({
      ...item
    }));
  },
  async getFinishedChanges() {
    const res = await axios.get("/api/getCustomerFinishedChanges");
    return res.data.content.map(item => ({
      ...item
    }));
  },
  async SendRequest(data) {
    const res = await axios.post(
      "/api/sendAcceptOrDeclineCustomerRequest",
      data
    );
    return res;
  }
};
