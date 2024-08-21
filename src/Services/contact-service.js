import axiosInstance from '../config/http';

export default {
    async getContactsByCustomerId(customer_id){
        try {
            const res = await axiosInstance.get(`/api/customer/${customer_id}/contacts` + contremarque_id);
            return res.data.response.contacts
          } catch (error) {
            throw new Error('Échec de récupération de la contacts client');
          }
    },
};
