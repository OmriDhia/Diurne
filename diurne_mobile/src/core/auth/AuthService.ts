import client from '../../api/client';
import * as SecureStore from 'expo-secure-store';
import { saveUser, clearUser, getCurrentUser } from '../database/Database';
import { Platform } from 'react-native';

const TOKEN_KEY = 'user_jwt_token';

export const AuthService = {
    async login(email: string, password: string) {
        try {
            const response = await client.post('/mobile/authenticate', { email, password });
            console.log('LOGIN RESPONSE:', JSON.stringify(response.data, null, 2));
            const apiResponse = response.data;
            const { token, user_id } = apiResponse.response;

            await this.setToken(token);

            // Fetch User Details to get Role/Permission
            const userResponse = await client.get(`/mobile/users/${user_id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });

            const userData = userResponse.data.response;
            
            // Sync to Local DB
            await saveUser(userData, token);

            return { token, user: userData };
        } catch (error) {
            console.error(error);
            throw error;
        }
    },

    async logout() {
        await SecureStore.deleteItemAsync(TOKEN_KEY);
        await clearUser();
    },

    async getCurrentUser() {
        return await getCurrentUser();
    },

    async refreshUser(userId: number) {
        try {
            const token = await this.getToken();
            if (!token) return null;

            const userResponse = await client.get(`/mobile/users/${userId}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            const userData = userResponse.data.response;
            await saveUser(userData, token);
            return userData;
        } catch (e) {
            console.warn('Failed to refresh user from API, using local data', e);
            return await this.getCurrentUser();
        }
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
