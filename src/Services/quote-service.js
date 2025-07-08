import axiosInstance from '../config/http';

export default {
    async getQuoteById(quote_id) {
        try {
            const res = await axiosInstance.get(`/api/quote/${quote_id}`);
            return res.data.response.quoteData;
        } catch (error) {
            throw new Error('Échec de récupération de devis');
        }
    },
    async getAllQuoteById(quote_id) {
        try {
            const res = await axiosInstance.get(`/api/quote/${quote_id}`);
            return res.data.response;
        } catch (error) {
            throw new Error('Échec de récupération de devis');
        }
    },
    async getCarpetOrderDetailByIdQuoteById(quoteDetailId) {
        try {
            const res = await axiosInstance.get(`/api/carpetOrdersDetail/${quoteDetailId}`);
            return res.data;
        } catch (error) {
            throw new Error('Échec de récupération de carpet Orders');
        }
    },
    async calculateQuote(quote_id, data) {
        try {
            const res = await axiosInstance.post(`/api/calculate/quote/${quote_id}`, data);
            return res.data.response.quoteData;
        } catch (error) {
            throw new Error('Échec de calcule de devis');
        }
    },
    async getQuoteDetailsById(id) {
        try {
            const res = await axiosInstance.get(`/api/quoteDetail/${id}`);
            return res.data.response.quoteDetailData;
        } catch (error) {
            throw new Error('Échec de récupération de détail devis');
        }
    },
    async getQuoteHtml(id) {
        try {
            const res = await axiosInstance.get(`/api/quote/download/${id}`);
            return res.data;
        } catch (error) {
            throw new Error('Échec de récupération de html devis');
        }
    }
};
