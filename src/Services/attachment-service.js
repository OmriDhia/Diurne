import axiosInstance from '../config/http';

export default {
    async uploadFile(file, type = 'image', onProgress = null) {
        try {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', type); 
            const url = '/api/upload/attachment';
            
            const response = await axiosInstance.post(url, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onUploadProgress: (progressEvent) => {
                    if (onProgress) {
                        const progress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                        onProgress(progress);
                    }
                },
            });
            return response.data.response;
        } catch (error) {
            throw new Error('Failed to upload file: ' + error.message);
        }
    },
};
