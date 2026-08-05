import axios from "axios"

//const BASE_URL = "http://localhost:8000/api"
const BASE_URL = "https://zilopay.onrender.com/api"

localStorage.setItem("apiUrl", BASE_URL);
export const api = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'X-Inertia': 'true',
  },
})

export default api
