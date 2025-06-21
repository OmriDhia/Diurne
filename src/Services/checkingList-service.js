import axiosInstance from '../config/http';

export default {
    async getCheckingListsByOrder(orderId){
        try {
            const res = await axiosInstance.get(`/api/checkingList/${orderId}`);
            return res.data.response?.checkingLists || [];
        } catch (error) {
            console.error('Error fetching checking lists:', error);
            throw new Error('Erreur lors de la récupération des checking lists');
        }
    },
    async createCheckingList(orderId){
        try {
            const res = await axiosInstance.post('/api/checkingList/create', { orderId });
            return res.data.response?.checkingList;
        } catch (error) {
            console.error('Error creating checking list:', error);
            throw new Error('Erreur lors de la création de la checking list');
        }
    }
};
