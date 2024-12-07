import axiosInstance from '../config/http';

export default {
    async getQuoteById(quote_id){
        try {
            const res = await axiosInstance.get(`/api/quote/${quote_id}`);
            return res.data.response.quoteData
          } catch (error) {
            throw new Error('Échec de récupération de devis');
          }
    },
};
