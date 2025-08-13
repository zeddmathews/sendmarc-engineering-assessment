import axios from 'axios';

const apiBaseURL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api';

const http = axios.create({
    baseURL: apiBaseURL,
    headers: {
        Accept: 'application/json',
    },
    xsrfCookieName: 'XSRF-TOKEN',
    xsrfHeaderName: 'X-XSRF-TOKEN',
});

export const csrfHttp = axios.create({
    baseURL: apiBaseURL.replace(/\/api\/?$/, ''),
    headers: {
        Accept: 'application/json',
    },
    withCredentials: true,
    withXSRFToken: true,
});

export default http;
