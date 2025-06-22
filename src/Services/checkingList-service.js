import axiosInstance from '../config/http';

export default {
    async getCheckingListsByOrder(orderId) {
        try {
            const res = await axiosInstance.get(`/api/checkingLists?workshopOrderId=${orderId}`);
            return res.data?.response || [];
        } catch (error) {
            console.error('Error fetching checking lists:', error);
            throw error;
        }
    },

    async createCheckingList(orderId, payload = {}) {
        try {
            const defaultPayload = {
                workshopOrderId: orderId,
                authorId: 1, // Should come from auth/user system
                date: new Date().toISOString().split('T')[0],
                dateEndProd: '',
                comment: 'New checking list'
            };
            const finalPayload = { ...defaultPayload, ...payload };

            const res = await axiosInstance.post('/api/checkingLists', finalPayload);
            return res.data?.response;
        } catch (error) {
            console.error('Error creating checking list:', error);
            throw error;
        }
    },

    async getCheckingListById(id) {
        try {
            const res = await axiosInstance.get(`/api/checkingLists/${id}`);
            return res.data?.response || {};
        } catch (error) {
            console.error('Error fetching checking list:', error);
            throw error;
        }
    },

    async updateCheckingList(id, payload) {
        try {
            const res = await axiosInstance.put(`/api/checkingLists/${id}`, payload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating checking list:', error);
            throw error;
        }
    },

    async updateShapeValidation(id, payload) {
        try {
            const res = await axiosInstance.put(`/api/shapeValidations/${id}`, payload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating shape validation:', error);
            throw error;
        }
    },

    async updateQualityCheck(id, payload) {
        try {
            const res = await axiosInstance.put(`/api/qualityChecks/${id}`, payload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating quality check:', error);
            throw error;
        }
    },

    async updateQualityRespect(id, payload) {
        try {
            const res = await axiosInstance.put(`/api/qualityRespects/${id}`, payload);
            return res.data?.data;
        } catch (error) {
            console.error('Error updating quality respect:', error);
            throw error;
        }
    }
};
