import axios from 'axios';

export default axios.create({
  baseURL: window.vars.apiBaseUrl,
});
