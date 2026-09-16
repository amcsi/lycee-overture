import axios from "axios";

const blogApi = axios.create({
  baseURL: "https://blog.lycee-tcg.eu/wp-json/wp",
});

// WordPress CORS rejects Laravel's default X-Requested-With header.
delete blogApi.defaults.headers.common["X-Requested-With"];

export default blogApi;
