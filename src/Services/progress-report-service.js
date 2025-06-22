import axiosInstance from '../config/http';

export default {
    async getStatuses() {
        try {
            const res = await axiosInstance.get('/api/progressReportStatus');
            return res.data.response;
        } catch (error) {
            throw error;
        }
    },
    async create(data) {
        try {
            const res = await axiosInstance.post('/api/progressReport', data);
            return res.data.response;
        } catch (error) {
            throw error;
        }
    }
};
