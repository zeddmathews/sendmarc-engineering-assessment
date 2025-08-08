import axios from 'axios';
const baseURL = import.meta.env.VITE_API_URL || '';
const http = axios.create({
  baseURL,
  headers: {
    Accept: 'application/json',
  },
  withCredentials: true,
});

export default http;
