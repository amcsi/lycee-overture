import axios from "axios";

export default axios.create({
  baseURL: "https://blog.lycee-tcg.eu/wp-json/wp",
});
