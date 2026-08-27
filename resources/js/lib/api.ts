import axios from "axios"

//const BASE_URL = "https://zilopay.onrender.com"
const BASE_URL = "http://localhost:8000"

export const baseApi = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Inertia': 'true',
  },
})

const api = axios.create({
  baseURL: BASE_URL + '/api',
  timeout: 10000,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
})

export default api
