import moment from 'moment';
import { FILE_URL } from './constants';

export function generateUniqueId(prefix = 'input') {
    return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
}

export const checkImageExists = async (url) => {
    try {
        const response = await fetch(url, { method: 'HEAD' });
        console.log(response);
        return response.ok;
    } catch {
        return false;
    }
};

export function formatErrorViolations(violations) {
    let obj = {};
    violations.forEach(m => {
        obj = { ...obj, [m.propertyPath]: m.title };
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
    return { message: violations.join(', ') }; // Concatenates all error messages into a single string
}

export function formatErrorViolationsComposed(violations) {
    let obj = {};
    violations.forEach((m) => {
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
            currency: 'EUR'
        });
        return formatter.format(price);
    },
    getImagePath: (attachment) => {
        if (attachment && attachment.path) {
            try {
                const fileName = attachment.file ? encodeURIComponent(attachment.file) : '';
                const path = String(attachment.path);
                // If path already contains the API host, use it
                if (/^https?:\/\//i.test(path)) {
                    return path.replace(/\/$/, '') + (fileName ? '/' + fileName : '');
                }
                // If path contains /public, use the portion after /public as the public URL
                let publicRelative = '';
                if (path.includes('/public')) {
                    publicRelative = path.split('/public').pop(); // includes leading /
                } else if (path.includes('/var/www')) {
                    // fallback: remove server filesystem prefix up to /var/www.../public
                    const idx = path.indexOf('/uploads');
                    publicRelative = idx !== -1 ? path.substring(idx) : '';
                }
                const base = String(FILE_URL || '').replace(/\/$/, '');
                if (publicRelative) {
                    return base + publicRelative + (fileName ? '/' + fileName : '');
                }
                // Final fallback: join base + path + filename
                return base + '/' + path.replace(/^\//, '') + (fileName ? '/' + fileName : '');
            } catch (e) {
                return '/assets/images/No-Image-Placeholder.svg';
            }
        }
        return '/assets/images/No-Image-Placeholder.svg';
    },
    getImagePathNew: (path, name = '') => {
        const noImage = '/assets/images/No-Image-Placeholder.svg';
        try {
            if (!path) return noImage;
            const p = String(path);
            // If it's already a full url
            if (/^https?:\/\//i.test(p) || /^\/\//.test(p)) {
                const base = p.replace(/\/$/, '');
                return name ? base + '/' + encodeURIComponent(name) : base;
            }
            // If contains /public, use portion after /public
            let publicRelative = '';
            if (p.includes('/public')) {
                publicRelative = p.split('/public').pop();
            } else if (p.includes('/uploads')) {
                const idx = p.indexOf('/uploads');
                publicRelative = idx !== -1 ? p.substring(idx) : p;
            } else if (p.startsWith('/')) {
                publicRelative = p;
            }

            const base = String(FILE_URL || '').replace(/\/$/, '');
            if (publicRelative) {
                return base + publicRelative + (name ? '/' + encodeURIComponent(name) : '');
            }

            // fallback: assume path is relative to base
            return base + '/' + p.replace(/^\//, '') + (name ? '/' + encodeURIComponent(name) : '');
        } catch (e) {
            return '/assets/images/No-Image-Placeholder.svg';
        }
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
    removeStorage: (name) => {
        try {
            localStorage.removeItem(name);
        } catch (e) {
            // ignore
        }
    }
};
