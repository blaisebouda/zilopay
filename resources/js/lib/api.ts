import axios from "axios";

const BASE_URL = import.meta.env.VITE_API_URL;

export const api = axios.create({
  baseURL: BASE_URL,
  timeout: 10000,
  headers: {
    Accept: "application/json",
    Authorization: localStorage.getItem("bearer-token"),
  },
});

// Request interceptor to update token dynamically
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("bearer-token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Redirect to login page
      localStorage.setItem("attemptedUrl", window.location.href || "");
      window.location.href = "/login";
    }
    return Promise.reject(error);
  },
);

export default api;
