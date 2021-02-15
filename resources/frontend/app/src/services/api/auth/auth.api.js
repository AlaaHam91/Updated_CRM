import axios from "@/plugins/api";
import qs from "qs";

export default {
  async signin(email, password) {
    const res = await axios.post("/api/auth/login", {
      email: email,
      password: password
    });
    return res;
  },

  async signout() {
    const res = await axios.post("/api/auth/logout");
    return res.data.message;
  },

  async signup(data) {
    const res = await axios.post("/api/auth/register", qs.stringify(data));
    return res.data.message;
  },

  async profile() {
    const res = await axios.get("/api/Profile");
    return res.data.content;
  },

  async changePassword(data) {
    const res = await axios.post("/api/ChangePassword", qs.stringify(data));
    return res.data.content;
  }
};
