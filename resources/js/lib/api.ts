import axios from "axios"

const BASE_URL = "https://zilopay.onrender.com"

export const baseApi = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'X-Inertia': 'true',
  },
})

const api = axios.create({
  baseURL: BASE_URL + '/api',
  timeout: 10000,
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'X-Inertia': 'true',
  },
})


export default api
