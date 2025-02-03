import CryptoJS from 'crypto-js';
import { CRYPTO_KEY } from './constants';

export default {
    encryptData(data) {
      const encryptedData = CryptoJS.AES.encrypt(JSON.stringify(data), CRYPTO_KEY).toString();
      return encryptedData;
    },
    decryptData(encryptedData) {
      const decryptedBytes = CryptoJS.AES.decrypt(encryptedData, CRYPTO_KEY);
      const decryptedData = JSON.parse(decryptedBytes.toString(CryptoJS.enc.Utf8));
      return decryptedData;
    },
    storeEncryptedData(data, storageKey) {
      const encryptedData = this.encryptData(data, CRYPTO_KEY);
      localStorage.setItem(storageKey, encryptedData);
    },
    retrieveDecryptedData(storageKey) {
      const encryptedData = localStorage.getItem(storageKey);
      if (encryptedData) {
        return this.decryptData(encryptedData, CRYPTO_KEY);
      }
      return null;
    }
};
