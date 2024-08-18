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
};
