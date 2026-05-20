import axios from "axios"

const BASE_URL = "http://localhost:8000/api"

export const api = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  headers: {
    Accept: "application/json",
  },
})

// Request interceptor to update token dynamically
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("bearer-token")
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Redirect to login page
      localStorage.setItem("attemptedUrl", window.location.href || "")
      window.location.href = "/login"
    }
    return Promise.reject(error)
  }
)

export default api
