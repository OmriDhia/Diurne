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
            // Try to load user from local DB (works offline)
            const user = await AuthService.getCurrentUser();
            
            if (user && user.token) {
                // Verify/Refresh in background if online (optional, or implementing a sync queue later)
                set({ token: user.token, user, isLoading: false });
                
                // Optional: Attempt to refresh data if online
                // AuthService.refreshUser(user.remote_id).then(updated => {
                //    if(updated) set({ user: updated });
                // });
            } else {
                 // Fallback to just token check or clear
                 const token = await AuthService.getToken();
                 if(token) {
                     // We have token but no user in DB? Edge case. 
                     // Maybe try to fetch user if online?
                     set({ token, isLoading: false }); 
                 } else {
                     set({ token: null, user: null, isLoading: false });
                 }
            }
        } catch (e) {
            set({ token: null, user: null, isLoading: false });
        }
    },
}));
