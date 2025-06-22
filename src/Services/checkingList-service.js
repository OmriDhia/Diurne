import axiosInstance from '../config/http';

export default {
    async getCheckingListsByOrder(orderId) {
        try {
            const res = await axiosInstance.get(`/api/checkingLists?orderId=${orderId}`);
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
            return res.data?.data;
        } catch (error) {
            console.error('Error creating checking list:', error);
            throw error;
        }
    }
};
