import { create } from 'zustand';
import { AuthService } from '../../../core/auth/AuthService';

interface AuthState {
    token: string | null;
    user: any | null;
    isLoading: boolean;
    signIn: (email: string, password: string) => Promise<void>;
    signOut: () => Promise<void>;
    restoreToken: () => Promise<void>;
}

export const useAuthStore = create<AuthState>((set) => ({
    token: null,
    user: null,
    isLoading: true,
    signIn: async (email, password) => {
        try {
            const data = await AuthService.login(email, password);
            set({ token: data.token, user: data.user, isLoading: false });
        } catch (e) {
            console.error('Login failed', e);
            throw e;
        }
    },
    signOut: async () => {
        await AuthService.logout();
        set({ token: null, user: null });
    },
    restoreToken: async () => {
        set({ isLoading: true });
        try {
            const token = await AuthService.getToken();
            // Optionally validate token with API here
            set({ token, isLoading: false });
        } catch (e) {
            set({ token: null, isLoading: false });
        }
    },
}));
