import axiosInstance from "../../config/http";

let isFetchingColors = false;
let isFetchingMaterials = false;
let isFetchingImageTypes = false;
let isFetchingAttachmentTypes = false;

export default {
    state: {
        measurements: [],
        materials: [],
        materialsData: [],
        designers: [],
        images: [],
        colors: [],
        imageTypes: [],
        attachmentTypes: [],
        defaultTypeImageId: 0,
        isFinStatus: false,
        isNonTrasmisStatus: false,
        carpetDesignOrderStatus: 0,
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
        setImageTypes(state, payload) {
            state.imageTypes = payload;
        },
        setAttachmentTypes(state, payload) {
            state.attachmentTypes = payload;
        },
        setDefaultTypeImageId(state, payload) {
            state.defaultTypeImageId = payload;
        },
        setIsFinStatus(state, payload) {
            state.isFinStatus = payload;
        },
        setIsNonTrasmisStatus(state, payload) {
            state.isNonTrasmisStatus = payload;
        },
        setCarpetDesignOrderStatus(state, payload) {
            state.carpetDesignOrderStatus = payload;
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
                commit('setColors', res.data.response.data);
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
                commit('setMaterialsData', res.data.response.data); // Enregistrer les matériaux dans l'état
            
            } catch (error) {
                console.error('Failed to fetch materials:', error);
            } finally {
                isFetchingMaterials = false;
            }
        },
        async fetchImageTypes({ commit, state }) {
            try {
                if (state.imageTypes.length > 0 || isFetchingImageTypes) {
                    return;
                }

                isFetchingImageTypes = true;
                
                const res = await axiosInstance.get('/api/image-types');
                commit('setImageTypes', res.data.response.data);
            
            } catch (error) {
                console.error('Failed to fetch image types:', error);
            } finally {
                isFetchingImageTypes = false;
            }
        },
        async fetchAttachmentTypes({ commit, state }) {
            try {
                if (state.attachmentTypes.length > 0 || isFetchingAttachmentTypes) {
                    return;
                }

                isFetchingAttachmentTypes = true;
                
                const res = await axiosInstance.get('/api/attachment-types');
                commit('setAttachmentTypes', res.data.response);
                const id = res.data.response.find(f => f.name === 'Image');
                commit('setDefaultTypeImageId', id.id);
                // console.log(id.id,res);
                
            
            } catch (error) {
                console.error('Failed to fetch attachment Types:', error);
            } finally {
                isFetchingAttachmentTypes = false;
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
        imageTypes(state) {
            return state.imageTypes;
        },
        attachmentTypes(state) {
            return state.attachmentTypes;
        },
        defaultTypeImageId(state) {
            return state.defaultTypeImageId;
        },
        isFinStatus(state) {
            return state.isFinStatus;
        },
        isNonTrasmisStatus(state) {
            return state.isNonTrasmisStatus;
        },
        carpetDesignOrderStatus(state) {
            return state.carpetDesignOrderStatus;
        },
    },
}
