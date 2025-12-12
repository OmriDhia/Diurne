import axios from 'axios';
import { AuthService } from '../auth/AuthService';

// TODO: Move to Env variable
const BASE_URL = process.env.EXPO_PUBLIC_API_URL || 'https://api.diurne.com/api';

export const apiClient = axios.create({
    baseURL: BASE_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

apiClient.interceptors.request.use(async (config) => {
    const token = await AuthService.getToken();
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (!error.response) {
            // Network Error - Here we can trigger Offline Queue logic if needed
            console.warn('Network unreachable, maybe offline?');
        }
        return Promise.reject(error);
    }
);
