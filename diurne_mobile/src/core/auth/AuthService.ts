import client from '../../api/client';
import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

const TOKEN_KEY = 'user_jwt_token';

export const AuthService = {
    async login(email: string, password: string) {
        try {
            const response = await client.post('/mobile/authenticate', { email, password });
            const { token, user } = response.data; // Assuming API returns { token, user: {...} } OR I need to fetch user separately?
            // Wait, my API /mobile/authenticate returns JUST { token }.
            // I need to decode token to get user ID or Email, OR fetch user details.
            // The AuthMobileAppController returns ['token' => $token, 'id' => $user->getId()].

            const userId = response.data.id;
            await this.setToken(token);

            // Fetch User Details to get Role/Permission
            // We assume there is an endpoint or we just return basic info for now.
            // But the store expects `user` object.
            // Let's fetch user object.

            const userResponse = await client.get(`/mobile/users/${userId}`, {
                headers: { Authorization: `Bearer ${token}` }
            });

            return { token, user: userResponse.data };
        } catch (error) {
            console.error(error);
            throw error;
        }
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
