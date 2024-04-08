import axios from 'axios';
import { API_URL } from '../config/config';
import { TOKEN_STORAGE_NAME } from './constants';
import store from '../store';
import {jwtDecode} from 'jwt-decode';

const LOGIN_URL = `${API_URL}/api/authenticate`;

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
          }
    },
    async doLogout(){
        localStorage.removeItem(TOKEN_STORAGE_NAME);
        store.commit('setIsAuthenticate',false);
        window.location.reload();
    },
    getUserInfo(){
        const token = this.getToken();
        try {
            const decodedToken = jwtDecode(token);
            return decodedToken;
          } catch (error) {
            console.error('Erreur lors du décryptage du token :', error);
            return null;
          }
    }
};
