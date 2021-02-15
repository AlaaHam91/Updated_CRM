import axios from "@/plugins/api";

export default {
  async getAll(payload = null) {
    let url = "/api/Job";
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

  async getAllForGuest() {
    let url = "/api/Guest/Jobs";
    const res = await axios.get(url);
    return res.data.content.map(item => {
      return {
        ...item,
        nameL: item.latin_name
      };
    });
  },

  async get(id) {
    const res = await axios.get(`/api/Job/${id}`);
    return this.loadForm(res.data.content[0]);
  },

  async create(data) {
    const res = await axios.post("/api/Job", data);
    return res;
  },

  async update(id, data) {
    const res = await axios.put(`/api/Job/${id}`, data);
    return res;
  },

  async delete(id) {
    const res = await axios.delete(`/api/Job/${id}`);
    return res;
  },
  loadForm(res) {
    if (res === undefined) return {};
    return {
      id: res.id,
      name: res.name ? res.name.ar : null,
      nameL: res.name ? res.name.en : null,
      description: res.job_description ? res.job_description.ar : null,
      descriptionL: res.job_description ? res.job_description.en : null,
      code: res.code,
      salary: res.salary,
      level: res.job_level ? res.job_level.ar : null,
      levelL: res.job_level ? res.job_level.en : null,
      employmentType: res.employment_type ? res.employment_type.ar : null,
      employmentTypeL: res.employment_type ? res.employment_type.en : null,
      requiredEducation: res.required_education_id,
      nationality: res.preferred_nationality_id,
      skills: res.job_skills ? res.job_skills.ar : null,
      skillsL: res.job_skills ? res.job_skills.en : null,
      type: res.job_type_id,
      phoneBookAvailable: res.is_phone_book_available == 1 ? true : false,
      requiredJobTitle: res.is_required_job_title == 1 ? true : false
    };
  },
  async first() {
    const res = await axios.get(`/api/JobQuery/navigate?action=first`);
    return this.loadForm(res.data.content[0]);
  },

  async previous(id) {
    const res = await axios.get(
      `/api/JobQuery/navigate?action=minusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async next(id) {
    const res = await axios.get(
      `/api/JobQuery/navigate?action=plusOne&id=${id}`
    );
    return this.loadForm(res.data.content[0]);
  },

  async last() {
    const res = await axios.get(`/api/JobQuery/navigate?action=last`);
    return this.loadForm(res.data.content[0]);
  }
};
