import axios from 'axios';

const api = axios.create({
  baseURL: window.vars.apiBaseUrl,
});

export default api;
