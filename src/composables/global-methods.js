import moment from "moment";
import i18n from "../i18n";
import { FILE_URL} from "./constants";

export function generateUniqueId(prefix = 'input') {
    return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
}

export function formatErrorViolations(violations) {
    let obj = {};
    const err = violations.map( m => {
        obj = {...obj, [m.propertyPath]: m.title}
    });
    
    /*let messages = [];
    Object.entries(obj).forEach(([key, value]) => {
        messages.push(i18n.global.t(key) + ': ' + i18n.global.t(value))
    });
    
    window.showMessage(messages.join("<br/>"), 'error');*/
    
    return obj;
}

export const Helper = {
    FormatDate: (date, format="DD/MM/YYYY") => {
        const dateToFormat = moment(date);
        return dateToFormat.format(format);
    },
    FormatPrice: (price) => {
        const formatter = new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR',
        });
        return formatter.format(price)
    },
    getImagePath: (attachment) => {
        if(attachment && attachment.path){
            const baseUrl = attachment.path.replace('/var/www/html/api_diurne/public',FILE_URL)
            return baseUrl + '/' + attachment.file; 
        }
        return "/assets/images/projet/no-image.png";
    },
    hasDefinedValue: (obj, excludedKey = "") => {
        for (let key in obj) {
            if (obj[key] !== null && obj[key] !== undefined && obj[key] !== '' && key !== excludedKey) {
                return true;
            }
        }
        return false;
    },
    getStorage: (name)=> {
        if(localStorage.getItem(name)){
            return JSON.parse(localStorage.getItem(name))
        }
        return null;
    },
    setStorage: (name, value)=> {
        if(value){
            localStorage.setItem(name, JSON.stringify(value))
        }
    },
};
