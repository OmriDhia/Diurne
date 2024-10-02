import axiosInstance from "../../config/http";

let isFetchingColors = false;
let isFetchingMaterials = false;

export default {
    state: {
        measurements: [],
        materials: [],
        materialsData: [],
        designers: [],
        images: [],
        colors: []
    },
    mutations: {
        setMeasurements(state, payload) {
            state.measurements = payload;
        },
        setMaterials(state, payload) {
            state.materials = payload;
        },
        setMaterialsData(state, payload) {
            state.materialsData = payload;
        },
        setColors(state, payload) {
            state.colors = payload;
        },
        setImages(state, payload) {
            state.images = payload;
        },
        setDesigners(state, payload) {
            state.designers = payload;
        },
    },
    actions: {
        async fetchColors({ commit, state }) {

            if (state.colors.length > 0 || isFetchingColors) {
                return;
            }
            
            isFetchingColors = true;

            try {
                const res = await axiosInstance.get('/api/color');
                commit('setColors', res.data.response);
            } catch (error) {
                console.error('Erreur lors de la récupération des couleurs:', error);
            } finally {
                isFetchingColors = false; 
            }
        },
        async fetchMaterials({ commit, state }) {
            try {
                if (state.materialsData.length > 0 || isFetchingMaterials) {
                    return;
                }

                isFetchingMaterials = true;
                
                const res = await axiosInstance.get('/api/materials');
                commit('setMaterialsData', res.data.response); // Enregistrer les matériaux dans l'état
            
            } catch (error) {
                console.error('Failed to fetch materials:', error);
            } finally {
                isFetchingMaterials = false;
            }
        }
    },
    getters: {
        measurements(state) {
            return state.measurements;
        },
        materials(state) {
            return state.materials;
        },
        materialsData(state) {
            return state.materialsData;
        },
        colors(state) {
            return state.colors;
        },
        images(state) {
            return state.images;
        },
        designers(state) {
            return state.designers;
        },
    },
}
