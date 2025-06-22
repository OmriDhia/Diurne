import axiosInstance from '../config/http';

export default {
    async getCheckingListsByOrder(orderId) {
        try {
            const res = await axiosInstance.get(`/api/checkingLists?orderId=${orderId}`);
            return res.data?.data || []; // Adjust based on actual API response
        } catch (error) {
            console.error('Error fetching checking lists:', error);
            throw error; // Throw the original error for better debugging
        }
    },

    async createCheckingList(orderId, payload = {}) {
        try {
            const defaultPayload = {
                workshopOrderId: orderId,
                authorId: 1, // Should come from auth/user system
                date: new Date().toISOString().split('T')[0],
                dateEndProd: '',
                comment: 'qsd'
            };
            const finalPayload = { ...defaultPayload, ...payload };

            const res = await axiosInstance.post('/api/checkingLists', finalPayload);
            return res.data?.data; // Adjust based on actual API response
        } catch (error) {
            console.error('Error creating checking list:', error);
            throw error;
        }
    }
};
