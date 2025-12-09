import axios from 'axios';
import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

const API_URL = 'https://api.diurne.com'; // Replace with actual dev/prod URL
const TOKEN_KEY = 'user_jwt_token';

export const AuthService = {
    async login(email: string, password: string) {
        // Mock login with roles for demo
        await new Promise(resolve => setTimeout(resolve, 800)); // Simulate network

        let name = 'Utilisateur';
        let role = 'USER';

        if (email.includes('admin')) {
            name = 'Admin User';
            role = 'ADMIN';
        } else if (email.includes('atelier')) {
            name = 'Chef Atelier';
            role = 'WORKSHOP';
        } else if (email.includes('stage')) {
            name = 'Stagiaire';
            role = 'INTERN';
        }

        const token = 'mock_jwt_token_' + Math.random();
        await this.setToken(token);
        return { token, user: { email, name, role } };
    },

    async logout() {
        await SecureStore.deleteItemAsync(TOKEN_KEY);
    },

    async getToken() {
        try {
            if (Platform.OS === 'web') return localStorage.getItem(TOKEN_KEY);
            return await SecureStore.getItemAsync(TOKEN_KEY);
        } catch (e) {
            return null;
        }
    },

    async setToken(token: string) {
        if (Platform.OS === 'web') {
            localStorage.setItem(TOKEN_KEY, token);
        } else {
            await SecureStore.setItemAsync(TOKEN_KEY, token);
        }
    }
};
