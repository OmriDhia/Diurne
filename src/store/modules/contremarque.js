export default {
    state: {
        measurements: [],
        materials: [],
        designers: [],
        images: []
    },
    mutations: {
        setMeasurements(state, payload) {
            state.measurements = payload;
        },
        setMaterials(state, payload) {
            state.materials = payload;
        },
        setImages(state, payload) {
            state.images = payload;
        },
        setDesigners(state, payload) {
            state.designers = payload;
        },
    },
    getters: {
        measurements(state) {
            return state.measurements;
        },
        materials(state) {
            return state.materials;
        },
        images(state) {
            return state.images;
        },
        designers(state) {
            return state.designers;
        },
    },
}
