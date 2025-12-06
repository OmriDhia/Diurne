import axiosInstance from '../config/http';

export default {
    async getInvoiceTypes() {
        try {
            const res = await axiosInstance.get('/api/invoiceTypes');
            return res.data.response || [];
        } catch (error) {
            console.error('Failed to fetch invoice types:', error);
            throw error;
        }
    }
};
