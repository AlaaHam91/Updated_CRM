import axios from "@/plugins/api";
import qs from "qs";

export default {
  async getEmployeeDepartmentRole(userId) {
    const res = await axios.get(`/api/EmployeeDepartmentRole/${userId}`);
    return res.data.content.map(item => ({
      ...item,
      branches: item.branches,
      name: item.department.name ? item.department.name.ar : null,
      nameL: item.department.name ? item.department.name.en : null,
      latin_name: item.department.name ? item.department.name.en : null,
      id: item.department.id
    }));
  },
  async getEmployeeBranchRole(userId) {
    const res = await axios.get(`/api/EmployeeBranchRole/${userId}`);
    return res.data.content.map(item => ({
      ...item,
      departments: item.departments,
      name: item.branch.name ? item.branch.name.ar : null,
      nameL: item.branch.name ? item.branch.name.en : null
    }));
  },
  async create(data) {
    const res = await axios.post(
      "/api/EmployeeDepartmentRole",
      qs.stringify(data)
    );
    return res;
  }
};
