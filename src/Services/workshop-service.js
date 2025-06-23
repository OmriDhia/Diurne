import axiosInstance from '../config/http';

export default {
    async createWorkshopInformation(data) {
        const res = await axiosInstance.post('/api/workshopInformation', data);
        return res.data;
    },
    async updateWorkshopInformation(id, data) {
        const res = await axiosInstance.put(`/api/workshopInformation/${id}`, data);
        return res.data;
    },
    async createWorkshopOrder(data) {
        const res = await axiosInstance.post('/api/workshopOrders', data);
        return res.data;
    },
    async generateRN(manufacturerId, imageCommandId) {
        const res = await axiosInstance.post('/api/carpet', {
            manufacturerId,
            imageCommandId,
        });
        return res.data;
    },
    async getManufacturers({page = 0, itemsPerPage = 10} = {}) {
        const res = await axiosInstance.get('/api/manufacturer', {
            params: {page, itemsPerPage},
        });
        return res.data;
    },
};
