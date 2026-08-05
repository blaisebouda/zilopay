import axios from "axios"

const BASE_URL = "http://localhost:8000/api"

export const baseApi = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  headers: {
    Accept: "application/json",
  },
})


export default api
