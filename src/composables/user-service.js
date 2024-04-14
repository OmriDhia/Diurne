import axios from 'axios';
import { API_URL } from '../config/config';
import { TOKEN_STORAGE_NAME, USER_INFO_STORAGE_NAME } from './constants';
import store from '../store';
import {jwtDecode} from 'jwt-decode';
import axiosInstance from '../config/http';
import cryptoLocalStorage from './crypto-localStorage';

const LOGIN_URL = `${API_URL}/api/authenticate`;
const PASS_RECOVER_URL = `${API_URL}/forgot-password/`;
const PASS_RESET_URL = `${API_URL}/forgot-password/`;

export default {
    setToken(token) {
        return localStorage.setItem(TOKEN_STORAGE_NAME, token);
    },
    getToken() {
        return localStorage.getItem(TOKEN_STORAGE_NAME);
    },
    isAuthenticated() {
        return store.getters.isAuthenticated;
    },
    async doLogin(credentials) {
        try {
            const  res = await axios.post(LOGIN_URL, credentials);
            return res.data.response;
          } catch (error) {
            throw new Error('Échec de la connexion. Veuillez vérifier vos identifiants.');
            //throw new Error(error.response.data.detail);
          }
    },
    async doLogout(){
        localStorage.removeItem(TOKEN_STORAGE_NAME);
        localStorage.removeItem(USER_INFO_STORAGE_NAME);
        store.commit('setIsAuthenticate',false);
        window.location.reload();
    },
    async getUserInfoApi(userId){
        try {
            const  res = await axiosInstance.get(`/api/user/${userId}`)
            return res.data.response
          } catch (error) {
            throw new Error('Échec de récupération des infos utilisateur');
          }
    },
    getUserInfo(){
        return cryptoLocalStorage.retrieveDecryptedData(USER_INFO_STORAGE_NAME)
    },
    setUserInfo(data){
        cryptoLocalStorage.storeEncryptedData(data, USER_INFO_STORAGE_NAME)
    },
    getUserInfoFromToken(){
        const token = this.getToken();
        try {
            const decodedToken = jwtDecode(token);
            return decodedToken;
          } catch (error) {
            console.error('Erreur lors du décryptage du token :', error);
            return null;
          }
    },
    async passRecover(credentials){
        try {
            const  res = await axios.post(PASS_RECOVER_URL, credentials);
            return res.data.response;
          } catch (error) {
            throw new Error('Échec de la réinitialisation de mot de passe. Veuillez vérifier votre Email.');
          }
    },
    async passReset(token, credentials){
        try {
            const  res = await axios.post(PASS_RESET_URL + token , credentials);
            return res.data.response;
          } catch (error) {
            if(error.response.data.detail){
                throw new Error(error.response.data.detail);
            }else{
                throw new Error('Échec de la réinitialisation de mot de passe.');
            }
          }
    }
};
