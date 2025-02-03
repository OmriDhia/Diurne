import axios from 'axios';
import { API_URL } from '../config/config';
import {TOKEN_STORAGE_NAME} from '../composables/constants';
import userService from '../Services/user-service';

// Create Axios instance
const axiosInstance = axios.create({
  baseURL: API_URL, // Set your API base URL here
});

// Add request interceptor
axiosInstance.interceptors.request.use(
  (config) => {
    // Retrieve token from localStorage or wherever it's stored
    const token = localStorage.getItem(TOKEN_STORAGE_NAME); // Assuming token is stored in localStorage

    // If token exists, add Authorization header with Bearer token
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

axiosInstance.interceptors.response.use(
    (response) => {
      // If the response is successful (status code between 200 and 299), return the response directly
      return response;
    },
    (error) => {
      /*if (error.response && error.response.status === 401 && error.response.data.message === "Invalid JWT Token" ) {
        userService.doLogout();
      }*/
  
      // Forward the error to the calling code
      return Promise.reject(error);
    }
  );

export default axiosInstance;
