import axiosInstance from '../config/http';

export default {
    async create(data) {
        try {
            const res = await axiosInstance.post('/api/provisionalCalendar', data);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    }
};
