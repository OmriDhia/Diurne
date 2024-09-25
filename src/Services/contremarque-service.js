import axiosInstance from '../config/http';

export default {
    async getContremarqueById(contremarque_id){
        try {
            const res = await axiosInstance.get("/api/contremarque/" + contremarque_id);
            return res.data.response.contremarqueData
          } catch (error) {
            throw new Error('Échec de récupération de la contremarque');
          }
    },
    async getCustomerById(customer_id){
        try {
            const res = await axiosInstance.get("/api/customer/" + customer_id);
            return res.data.response.customerData;
        } catch (error) {
            throw new Error('Échec de récupération du client');
        }
    },
    async getcarpetDesign(contremarqueId,projectDiId){
        try {
            const res = await axiosInstance.get(`/api/contremarque/${contremarqueId}/projectDi/${projectDiId}/carpetDesignOrders`);
            return res.data.response.carpetDesignOrders;
        } catch (error) {
            throw new Error('Échec de récupération du design tapi');
        }
    },
    async getProjectDiById(id){
        try {
            const res = await axiosInstance.get(`/api/project-di/${id}`);
            return res.data.response;
        } catch (error) {
            throw new Error('Échec de récupération du di par id');
        }
    },
    async getMeasurements(){
        try {
            const res = await axiosInstance.get(`/api/measurements`);
            return res.data.response.measurements;
        } catch (error) {
            throw new Error('Échec de récupération des mesures');
        }
    },
    async getUnitOfMeasurements(){
        try {
            const res = await axiosInstance.get('/api/unitOfMeasurements');
            return res.data.response.units;
        } catch (error) {
            throw new Error('Échec de récupération unité de mesure');
        }
    },
    async getLocationsByContremarque(contremarqueId){
        try {
            const res = await axiosInstance.get(`/api/locationsByContremarque/${contremarqueId}`);
            return res.data.response.locations;;
        } catch (error) {
            throw new Error('Échec de récupération des emplacements');
        }
    },
    async getCarpetDesignImages(carpetDesignOrderId){
        try {
            const res = await axiosInstance.get(`/api/carpet-design-order/${carpetDesignOrderId}/images`);
            return res.data.response
        } catch (error) {
            throw new Error('Échec de récupération des images tapis');
        }
    },
};
