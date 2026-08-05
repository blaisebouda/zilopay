import axios from "axios"

//const BASE_URL = "http://localhost:8000/api"
const BASE_URL = "https://zilopay.onrender.com/api"

localStorage.setItem("apiUrl", BASE_URL);
export const api = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  headers: {
    Accept: "application/json",
  },
})

export default api
