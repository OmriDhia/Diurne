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
    async downloadFile(attachment){
        try {
            const response = await axiosInstance.get(`/api/attachment/${attachment.id}`, {
                responseType: 'blob' // Important to handle the response as a blob
            });

            // Create a URL for the blob
            const blob = new Blob([response.data]);
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = attachment.file;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            // Release the object URL
            window.URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Error downloading the file:', error);
            window.showMessage("Erreur de téléchargement de fichier",'error')
        }
    },
};
