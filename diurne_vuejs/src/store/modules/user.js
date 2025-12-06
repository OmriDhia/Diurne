import { TOKEN_STORAGE_NAME } from "../../composables/constants";

export default {
    state: {
        isAuthenticate: !!localStorage.getItem(TOKEN_STORAGE_NAME),
        user: null,
        isDesigner: false,
        isDesignerManager: false,
        isCommertial: false,
        isCommercialManager: false,
        isSuperAdmin: false
    },
    mutations: {
        setIsAuthenticate(state, payload) {
            state.isAuthenticate = payload;
        },
        setUser(state, payload) {
            state.user = payload;
        },
        setIsDesigner(state, payload) {
            state.isDesigner = payload;
        },
        setIsDesignerManager(state, payload) {
            state.isDesignerManager = payload;
        },
        setIsCommertial(state, payload) {
            state.isCommertial = payload;
        },
        setIsCommercialManager(state, payload) {
            state.isCommercialManager = payload;
        },
        setIsSuperAdmin(state, payload) {
            state.isSuperAdmin = payload;
        },
    },
    getters: {
        isAuthenticated(state) {
            return state.isAuthenticate;
        },
        isDesigner(state) {
            return state.isDesigner;
        },
        isDesignerManager(state) {
            return state.isDesignerManager;
        },
        isCommertial(state) {
            return state.isCommertial;
        },
        isCommercialManager(state) {
            return state.isCommercialManager;
        },
        isSuperAdmin(state) {
            return state.isSuperAdmin;
        },
        getUser(state) {
            return state.user;
        }
    },
}
