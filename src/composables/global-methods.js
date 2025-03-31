import moment from 'moment';
import i18n from '../i18n';
import { FILE_URL } from './constants';

export function generateUniqueId(prefix = 'input') {
    return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
}

export const checkImageExists = async (url) => {
    try {
        const response = await fetch(url, { method: "HEAD" });
        console.log(response);
        return response.ok;
    } catch {
        return false;
    }
};

export function formatErrorViolations(violations) {
    let obj = {};
    const err = violations.map( m => {
        obj = {...obj, [m.propertyPath]: m.title}
    });
    return obj;
}
export function handleObjectViolations(violations) {
    let obj = {};
    violations.forEach(m => {
        obj[m.propertyPath] = m.title; // Map propertyPath to title
    });
    return obj;
}

export function handleStringViolations(violations) {
    return { message: violations.join(", ") }; // Concatenates all error messages into a single string
}

export function formatErrorViolationsComposed(violations) {
    let obj = {};
    const err = violations.map((m) => {
        // Replace dot (.) with underscore (_) in the key
        const key = m.propertyPath.replace(/\./g, '_');
        obj = { ...obj, [key]: m.title };
    });

    return obj;
}

export const Helper = {
    FormatDate: (date, format = 'DD/MM/YYYY') => {
        const dateToFormat = moment(date);
        return dateToFormat.format(format);
    },
    FormatDateTime: (date, format = 'DD/MM/YYYY HH:mm:ss') => {
        return moment(date).format(format);
    },
    getPrice: (obj, path) => {
        return path.split('.').reduce((o, p) => o?.[p] ?? 0, obj);
    },
    FormatNumber: (number) => {
        let nb = 0;
        if (number) {
            nb = number;
        }
        const numberToFormat = parseFloat(nb);
        return numberToFormat.toFixed(2);
    },
    FormatPrice: (price) => {
        const formatter = new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR',
        });
        return formatter.format(price);
    },
    getImagePath: (attachment) => {
        if (attachment && attachment.path) {
            const baseUrl = attachment.path.replace('/var/www/html/api_diurne/public', FILE_URL);
            return baseUrl + '/' + attachment.file;
        }
        return '/assets/images/projet/no-image.png';
    },
    getImagePathNew: (path, name = "") => {
        const noImage = '/assets/images/No-Image-Placeholder.svg';
        if (path) {
            const baseUrl = path.replace('/var/www/html/api_diurne/public', FILE_URL);
            if (name) {
                return baseUrl + '/' + name;
            }
            return baseUrl;
        }
        return noImage;
    },
    hasDefinedValue: (obj, excludedKey = '') => {
        for (let key in obj) {
            if (obj[key] !== null && obj[key] !== undefined && obj[key] !== '' && key !== excludedKey) {
                return true;
            }
        }
        return false;
    },
    getStorage: (name) => {
        if (localStorage.getItem(name)) {
            return JSON.parse(localStorage.getItem(name));
        }
        return null;
    },
    setStorage: (name, value) => {
        if (value) {
            localStorage.setItem(name, JSON.stringify(value));
        }
    },
};
