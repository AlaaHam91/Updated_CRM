import axios from "@/plugins/api";
import { reject } from "promise";
import qs from "qs";

export default {
  async getAll(url) {
    const res = await axios.get("/api/Tickets/" + url);
    return res.data.message[0];
  },

  async get(id) {
    const res = await axios.get(`/api/Tickets/${id}`);
    return res.data.message;
  },

  async create(data) {
    const res = await axios.post("/api/Tickets/ByClient", qs.stringify(data));
    return res;
  },

  async createByWizard(data) {
    const res = await axios.post("/api/Tickets/ByWizard", qs.stringify(data));
    return res;
  },

  async activateTicket(data, id) {
    const res = await axios.post(
      `/api/Tickets/${id}/ActivationCode`,
      qs.stringify(data)
    );
    return res;
  },

  async assign(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Assign`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async schedule(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Schedule`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async redirect(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Redirect`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async finish(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Finish`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async escalate(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Escalate`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async share(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Share`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async report(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/WriteDailyReport`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async note(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/AddNote`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async email(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/SendEmail`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async sms(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/SendSMS`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async closeTicket(id, data) {
    const res = await axios
      .post(`/api/Tickets/${id}/Close`, qs.stringify(data))
      .catch(err => reject(err.data.message));
    return res;
  },

  async search(params) {
    let url = `/api/Tickets/SearchTicket`;
    if (params) url += params;
    const res = await axios.get(url).catch(err => reject(err.data.message));
    return res.data.message;
  },

  async getAllowedOperations(id) {
    const res = await axios.get(`/api/Tickets/${id}/Permission`);
    return res.data;
  }
};
