import axios from 'axios';
import * as SecureStore from 'expo-secure-store';

// Android Emulator localhost: 10.0.2.2
// If using specific port like 8001 for backend-web:
// Android Emulator localhost: 10.0.2.2
// If using specific port like 8001 for backend-web:
const baseURL = process.env.EXPO_PUBLIC_API_URL || 'http://10.0.2.2:8001/api';


const client = axios.create({
    baseURL,
    headers: {
        'Content-Type': 'application/json',
    },
});

client.interceptors.request.use(async (config) => {
    const token = await SecureStore.getItemAsync('user_jwt_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default client;
