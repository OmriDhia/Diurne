import moment from "moment";

export function generateUniqueId(prefix = 'input') {
    return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
}

export function formatErrorViolations(violations) {
    let obj = {}
    const err = violations.map( m => {
        obj = {...obj, [m.propertyPath]: m.title}
    });
    return obj;
}

export const Helper = {
    FormatDate: (date, format="YYYY-MM-DD") => {
        const dateToFormat = moment(date);
        return dateToFormat.format(format);
    },
    FormatPrice: (price) => {
        const formatter = new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR',
        });
        return formatter.format(price)
    }
}
