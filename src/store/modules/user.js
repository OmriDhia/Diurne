import { TOKEN_STORAGE_NAME } from "../../composables/constants";

export default {
    state: {
        isAuthenticate: !!localStorage.getItem(TOKEN_STORAGE_NAME),
        user: null 
    },
    mutations: {
        setIsAuthenticate(state, payload) {
            state.isAuthenticate = payload;
        },
        setUser(state, payload) {
            state.user = payload;
        },
    },
    getters: {
        isAuthenticated(state) {
            return state.isAuthenticate;
        },
    },
}
