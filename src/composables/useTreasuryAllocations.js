import axiosInstance from '../config/http';

/**
 * Maps a payment detail item to an allocation object, handling both quote and invoice logic.
 * @param {Object} item - The payment detail item from the backend.
 * @param {Object} context - Context with refs and helpers (contremarqueIdRef, Helper, DEFAULT_RN_PREFIX, DEFAULT_DISTRIBUTION)
 * @returns {Promise<Object>} - The mapped allocation object.
 */
export async function mapAllocationFromPaymentDetail(item, { contremarqueIdRef, Helper, DEFAULT_RN_PREFIX, DEFAULT_DISTRIBUTION }) {
    if (item.quote) {
        try {
            const quoteResponse = await axiosInstance.get(`/api/quote/${item.quote}`);
            const quoteData = quoteResponse.data.response;
            if (contremarqueIdRef) contremarqueIdRef.value = quoteData?.quoteData?.contremarqueId || 0;
            return {
                id: item.id,
                quoteId: item.quote,
                quoteDetailId: item.quoteDetail,
                orderId: item.orderId, // pour les cas mixtes
                orderInvoiceId: item.orderInvoiceId,
                projetId: item.projetId,
                emplacementId: item.emplacementId,
                carpetSpecification: quoteData?.quoteData?.quoteDetails?.find(d => d.id === item.quoteDetail)?.carpetSpecification || null,
                location: quoteData?.quoteData?.quoteDetails?.find(d => d.id === item.quoteDetail)?.location || null,
                devis: quoteData?.quoteData?.reference || '',
                commande_ref: item.commandNumber || '',
                rn: item.rn || DEFAULT_RN_PREFIX + Math.random().toString(36).substring(2, 7).toUpperCase(),
                facture: item.facture || '',
                distribution: Helper.FormatNumber(item.distribution || parseFloat(DEFAULT_DISTRIBUTION)),
                allocatedAmountTtc: Helper.FormatNumber(item.allocatedAmountTtc || 0),
                totalAmountTtc: Helper.FormatNumber(item.totalAmountTtc || 0),
                remainingAmountTtc: Helper.FormatNumber(item.remainingAmountTtc || 0),
                allocatedAmountHt: Helper.FormatNumber(item.allocatedAmountHt || 0),
                tva: Helper.FormatNumber(item.tva || 0),
                cleared: item.cleared || false,
                type: 'quote',
                areaSquareMeter: item.areaSquareMeter || 0,
                areaSquareFeet: item.areaSquareFeet || 0
            };
        } catch (error) {
            console.error(`Erreur lors du chargement du devis ${item.quote}:`, error);
            return {
                ...item,
                type: 'quote',
                devis: 'Devis non chargé',
                carpetSpecification: null,
                location: null
            };
        }
    }
    // Correction ici : toujours renseigner orderId pour les allocations de type 'order'
    try {
        const invoiceResponse = await axiosInstance.get(`/api/customerInvoices/${item.customerInvoice || item.customerInvoiceId || item.orderId}`);
        const invoiceData = invoiceResponse.data.response;
        const invoiceDetail = (invoiceData.customerInvoiceDetails || []).find(d => d.id == (item.customerInvoiceDetail || item.orderInvoiceId));
        // Correction ici : on va chercher location et carpetSpecification
        const quoteDetail = invoiceDetail?.carpetOrderData?.QuoteDetail;
        const allocation = {
            ...item,
            orderId: item.customerInvoice || item.customerInvoiceId || item.orderId,
            orderInvoiceId: item.customerInvoiceDetail || item.orderInvoiceId,
            type: 'order',
            commande_ref: invoiceDetail?.refCommand || '',
            devis: invoiceDetail?.refQuote || '',
            facture: invoiceData.invoice_number || '',
            location: quoteDetail?.location || null,
            carpetSpecification: quoteDetail?.carpetSpecification || null,
        };
        return allocation;
    } catch (error) {
        console.error(`Erreur lors du chargement de la facture ${item.customerInvoice}:`, error);
        return {
            ...item,
            orderId: item.customerInvoice || item.customerInvoiceId || item.orderId, // Correction principale
            orderInvoiceId: item.customerInvoiceDetail || item.orderInvoiceId,
            type: 'order',
            commande_ref: '',
            devis: '',
            facture: '',
        };
    }
}

/**
 * Create an allocation object for a quote.
 */
export function createQuoteAllocation(quoteFullObject, quoteDetail, { Helper, DEFAULT_RN_PREFIX, DEFAULT_DISTRIBUTION }) {
    return {
        quoteId: quoteFullObject.quote_id,
        quoteDetailId: quoteDetail.id,
        orderId: null,
        orderInvoiceId: null,
        projetId: quoteDetail.location?.location_id || null,
        emplacementId: quoteDetail.location?.location_id || quoteFullObject.deliveryAddress?.id || null,
        carpetSpecification: quoteDetail?.carpetSpecification || null,
        location: quoteDetail?.location || null,
        devis: quoteFullObject.reference || '',
        commande_ref: quoteDetail.reference || '',
        rn: DEFAULT_RN_PREFIX + Math.random().toString(36).substring(2, 7).toUpperCase(),
        facture: '',
        distribution: DEFAULT_DISTRIBUTION,
        allocatedAmountTtc: 0,
        totalAmountTtc: parseFloat(quoteDetail.prices?.['prix-propose-avant-remise-complementaire']?.totalPriceTtc) || 0,
        remainingAmountTtc: parseFloat(quoteDetail.prices?.['prix-propose-avant-remise-complementaire']?.totalPriceTtc) || 0,
        allocatedAmountHt: 0,
        tva: 0,
        cleared: false,
        type: 'quote',
        areaSquareMeter: quoteDetail.areaSquareMeter || 0,
        areaSquareFeet: quoteDetail.areaSquareFeet || 0
    };
}

/**
 * Create an allocation object for an invoice.
 */
export function createInvoiceAllocation(invoiceFullObject, invoiceDetail, { Helper, DEFAULT_RN_PREFIX, DEFAULT_DISTRIBUTION }) {
    // Correction ici : on va chercher location et carpetSpecification
    const quoteDetail = invoiceDetail?.carpetOrderData?.QuoteDetail;
    return {
        quoteId: null,
        quoteDetailId: null,
        orderId: invoiceFullObject.id,
        orderInvoiceId: invoiceDetail.id,
        projetId: null, // set if you have project info
        emplacementId: null, // set if you have emplacement info
        carpetSpecification: quoteDetail?.carpetSpecification || null,
        location: quoteDetail?.location || null,
        devis: invoiceDetail.refQuote || '',
        commande_ref: invoiceDetail.refCommand || '',
        rn: invoiceDetail.rn || (DEFAULT_RN_PREFIX + Math.random().toString(36).substring(2, 7).toUpperCase()),
        facture: invoiceFullObject.invoice_number || '',
        distribution: DEFAULT_DISTRIBUTION,
        allocatedAmountTtc: 0,
        totalAmountTtc: parseFloat(invoiceDetail.ttc) || 0,
        remainingAmountTtc: parseFloat(invoiceDetail.ttc) || 0,
        allocatedAmountHt: 0,
        tva: 0,
        cleared: invoiceDetail.cleared || false,
        type: 'order',
        areaSquareMeter: parseFloat(invoiceDetail.m2) || 0,
        areaSquareFeet: parseFloat(invoiceDetail.sqft) || 0
    };
}

/**
 * Update allocation for a new item (quote or invoice) with calculated fields.
 */
export function updateAllocationForNewItem(allocation, { Helper, selectedQuoteObject, paymentData }) {
    const taxRate = allocation.type === 'quote'
        ? (selectedQuoteObject?.value?.taxRule?.taxRate || 0.20)
        : (paymentData?.value?.taxRule?.taxRate || 0.20);

    const allocatedTtc = (allocation.totalAmountTtc * parseFloat(allocation.distribution) / 100).toFixed(2);
    allocation.allocatedAmountTtc = Helper.FormatNumber(allocatedTtc);
    const allocatedHt = allocatedTtc / (1 + taxRate);
    allocation.allocatedAmountHt = Helper.FormatNumber(allocatedHt);
    allocation.tva = Helper.FormatNumber(allocatedTtc - allocatedHt);
    allocation.remainingAmountTtc = Helper.FormatNumber(allocation.totalAmountTtc - allocatedTtc);
} 