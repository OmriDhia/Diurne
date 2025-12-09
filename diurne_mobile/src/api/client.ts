import axios from 'axios';
import * as SecureStore from 'expo-secure-store';

// Android Emulator localhost: 10.0.2.2
// If using specific port like 8001 for backend-web:
const baseURL = 'http://10.0.2.2:8001/api';

const client = axios.create({
    baseURL,
    headers: {
        'Content-Type': 'application/json',
    },
});

client.interceptors.request.use(async (config) => {
    const token = await SecureStore.getItemAsync('userToken');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default client;
