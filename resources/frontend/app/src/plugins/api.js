import axios from "axios";
import Vue from "vue";
import router from "@/router/index";
import store from "@/store/index";

// =====================================================
const baseURL = "";
const headers = {};

const instance = axios.create({
  baseURL: baseURL,
  timeout: 15000, //ms
  headers: headers
});

instance.interceptors.request.use(
  function(config) {
    store.commit("ui/startBar");
    // console.log('Request interceptors: ', config);

    return config;
  },
  function(error) {
    // console.log('Request error: ' , error);
    return Promise.reject(error);
  }
);

instance.interceptors.response.use(
  function(response) {
    store.commit("ui/stopBar");
    // console.log("Response interceptors : ", response);
    // console.log('Response status : ', response.data.status)
    // console.log('response headers : ', response.headers)

    if (response.data.result === "failed") {
      Vue.notify({
        text: "Error",
        type: "error"
      });
      return Promise.reject(response);
    }

    return Promise.resolve(response);
  },
  function(error) {
    console.log("Response error: ", error.response);
    if (error.response.status == 401) {
      if (router.history.current.name !== "signin")
        router.push({ name: "signout" });
    }
    return Promise.reject(error);
  }
);

export default instance;
