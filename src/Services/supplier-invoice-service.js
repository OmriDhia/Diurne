import axiosInstance from '../config/http';

export default {
    async create(data) {
        try {
            const res = await axiosInstance.post('/api/supplierInvoices', data);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    },
    async getById(id) {
        try {
            const res = await axiosInstance.get(`/api/supplierInvoices/${id}`);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    },
    async update(id, data) {
        try {
            const res = await axiosInstance.put(`/api/supplierInvoices/${id}`, data);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    }
};

