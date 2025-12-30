import axios from "axios";
import { getCookie } from "../utils/browser";

const api = axios.create({
  baseURL: window.vars.apiBaseUrl,
});

api.interceptors.request.use((config) => {
  // Forward xdebug/clockwork cookies to the API.
  const xdebugSessionValue = getCookie("XDEBUG_SESSION");
  if (!config.params) {
    config.params = {};
  }
  if (xdebugSessionValue) {
    config.params.XDEBUG_SESSION_START = xdebugSessionValue;
  }
  return config;
});

export default api;
