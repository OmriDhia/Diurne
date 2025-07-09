import { create } from 'maska';
import axiosInstance from '../config/http';

export default {
    async create(data) {
        try {
            const res = await axiosInstance.post(`/api/customer-invoice-details`, data);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    },
    async update(id, data) {
        try {
            const res = await axiosInstance.put(`/api/customer-invoice-details/${id}`, data);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    },
    async delete(id) {
        try {
            const res = await axiosInstance.delete(`/api/customer-invoice-details/${id}`);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    },
    async getById(id) {
        try {
            const res = await axiosInstance.get(`/api/customer-invoice-details/${id}`);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    }
};
