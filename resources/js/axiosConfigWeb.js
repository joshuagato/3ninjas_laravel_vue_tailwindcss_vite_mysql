import axios from "axios";

// const baseURL = process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000';
// const baseURL = process.env.VITE_APP_API_BASE_URL || 'http://127.0.0.1:8000';
const baseURL = import.meta.env.VITE_APP_API_BASE_URL;

const axiosInstance = axios.create({
    baseURL,
});

export default axiosInstance;
