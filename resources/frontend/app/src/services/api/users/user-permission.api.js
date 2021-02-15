import axios from "@/plugins/api";
import qs from "qs";
import { cloneDeep } from "lodash";

let pages = [];

export default {
  //all pages
  async getAll(payload = null) {
    let url = "/api/UserPermission";
    if (payload) url += payload;
    const res = await axios.get(url);
    const result = res.data.content;
    for (const key in result) {
      pages.push({
        name: key,
        data: result[key]
      });
    }
    return pages;
  },
  //role permissions
  async get(id) {
    const res = await axios.get(`/api/UserPermission/${id}`);
    return this.loadForm(res.data.content);
  },

  async create(data) {
    const res = await axios.post("/api/UserPermission", qs.stringify(data));
    return res;
  },

  async update(id, data) {
    const res = await axios.put(
      `/api/UserPermission/${id}`,
      qs.stringify(data)
    );
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/UserPermission/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    let result = {
      id: res[0][0].id,
      name: res[0][0].name,
      nameL: res[0][0].latin_name,
      permissions: []
    };
    res[1].forEach(element => {
      result.permissions.push(element.permission_id);
    });
    result.pages = cloneDeep(pages);

    result.permissions.forEach(permission => {
      result.pages.forEach(page => {
        page.data.forEach(group => {
          if (group.id == permission) group["val"] = true;
        });
      });
    });

    return result;
  },
  async first() {
    const res = await axios.get(
      `/api/UserPermissionQuery/navigate?action=first`
    );
    return this.loadForm(res.data.content);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/UserPermissionQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content);
  },

  async next(id) {
    const res = await axios.get(
      `/api/UserPermissionQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content);
  },

  async last() {
    const res = await axios.get(
      `/api/UserPermissionQuery/navigate?action=last`
    );
    return this.loadForm(res.data.content);
  }
};
