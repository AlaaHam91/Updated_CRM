import axios from "@/plugins/api";
import qs from "qs";

export default {
  async getAll(payload = null) {
    let url = "/api/RequiredAction";
    if (payload) url += payload;
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        name: item.name ? item.name.ar : null,
        nameL: item.name ? item.name.en : null,
        latin_name: item.name ? item.name.en : null
      };
    });
  },

  loadForm(res) {
    if (res === undefined) return {};

    let result = res.data.content["0"];
    return {
      id: result.id,
      name: result.name ? result.name.ar : null,
      nameL: result.name ? result.name.en : null,
      departments: res.data.content["departments"],
      defaultTime: result.default_time,
      closeType: result.close_type,
      requiredDeliveryDate: result.required_delivery_date == 0 ? false : true,
      implementationNo: result.implementation_no == 0 ? false : true,
      confirmProject: result.confirm_project == 0 ? false : true,
      progressRate: result.progress_rate,
      finishType: result.finish_type,
      variableField: result.variable_field,
      correctValue: result.correct_value == 0 ? false : true
    };
  },

  async get(id) {
    const res = await axios.get(`/api/RequiredAction/${id}`);
    return this.loadForm(res);
    // let result = res.data.content["0"];
    // return {
    //   name: result.name ? result.name.ar : null,
    //   nameL: result.name ? result.name.en : null,
    //   departments: res.data.content["departments"],
    //   defaultTime: result.default_time,
    //   closeType: result.close_type,
    //   requiredDeliveryDate: result.required_delivery_date == 0 ? false : true,
    //   implementationNo: result.implementation_no == 0 ? false : true,
    //   confirmProject: result.confirm_project == 0 ? false : true,
    //   progressRate: result.progress_rate,
    //   finishType: result.finish_type,
    //   variableField: result.variable_field,
    //   correctValue: result.correct_value == 0 ? false : true
    // };
  },

  async create(data) {
    const res = await axios.post("/api/RequiredAction", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(
      `/api/RequiredAction/${id}`,
      qs.stringify(data)
    );
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/RequiredAction/${id}`);
    return res;
  },

  async first() {
    const res = await axios.get(
      `/api/RequiredActionQuery/navigate?action=first`
    );
    return this.loadForm(res);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/RequiredActionQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res);
  },

  async next(id) {
    const res = await axios.get(
      `/api/RequiredActionQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res);
  },

  async last() {
    const res = await axios.get(
      `/api/RequiredActionQuery/navigate?action=last`
    );
    return this.loadForm(res);
  }
};
