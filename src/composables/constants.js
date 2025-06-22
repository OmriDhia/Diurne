export const FILE_URL = "https://diurne-api.webntricks.com";
export const CRYPTO_KEY = "QdSr3pKrmW";
export const TOKEN_STORAGE_NAME = "auth_token";
export const USER_INFO_STORAGE_NAME = "user_info";
export const CONTACT_SELECTION_STORAGE_NAME = "Selected_contact_menu";
export const FILTER_CONTACT_STORAGE_NAME = "filterContact";
export const FILTER_ORDER_PAYMENT_STORAGE_NAME = "filterOrderPayment";
export const FILTER_EVENT_STORAGE_NAME = "filterEvent";
export const FILTER_CONTREMARQUE_STORAGE_NAME = "filterContremarque";
export const FILTER_DEVIS_STORAGE_NAME = "filterDevis";
export const FILTER_SUIVI_DI_STORAGE_NAME = "filterSuiviDi";
export const FILTER_CLIENT_INVOICE_STORAGE_NAME = "filterClientInvoice";
export const FILTER_IMAGE_COMMANDE_STORAGE_NAME = "filterImageCommande";
export const DESIGNER_MANAGER_ROLE_NAME = "Designer manager";
export const DESIGNER_ROLE_NAME = "Designer";
export const COMMERCIAL_ROLE_NAME = "Commercial";
export const COMMERCIAL_MANAGER_ROLE_NAME = "Commercial manager";
export const SUPER_ADMIN_ROLE_NAME = "Super admin";

export const menuHomeColor = {
    contact: '#E3599E',
    contremarque: '#4260EB',
    devis: '#4260EB',
    image: '#4260EB',
    commande: '#48A878',
    facture: '#48A878',
    tapis: '#48A878',
    di: '#EBC31A',
    treasure: '#EBC31A'
}
export const intermediaryType ={
    agent: "Agent",
    perscripteur: "Prescripteur",
}
export const filterOrderPayment = {
    customer: null,
    commercial: null,
    contremarque: null,
    prescriptor: null,
    devis: null,
    commande: null,
    amount: null,
};
export const filterEvent = {
    firstname: null,
    lastname: null,
    rs: null,
    tva_ce: null,
    commercial: null,
    email: null,
    customerTypeId: null,
    hasInvalidCommercial: null,
    active: null,
    hasOnlyOneContact: true,
    hasNoProjectY: null,
    hasNoProjectN: null,
    hasNextStepY: null,
    hasNextStepN: null,
    eventDate_from: null,
    eventDate_to: null,
    next_reminder_deadline_from: null,
    next_reminder_deadline_to: null,
    subject: null,
    onlyLastEvent: null,
    pres: null,
    contact: null,
};
export const filterContact = {
    lastname: null,
    postCode: null,
    rs: null,
    city: null,
    firstname: null,
    tva_ce: null,
    commercial: null,
    webSite: null,
    country: null,
    pres: null,
    customerTypeId: null,
    wrongAdd: null,
    validAdd: null,
    hasInvalidCommercial: null,
    active: null,
    hasOnlyOneContact: true,
    mailingLanguageId: null,
    contactMailing: null,
    is_agent: null,
    is_prescripteur: null,
};
export const filterContremarque = {
    customer: null,
    contremarque: null,
    commercial: null,
    endDate: null,
    prescriptor: null,
    pendingProject: null,
    projectRelance: null,
    projectRelanceX: null,
    projectWithoutRelance: null,
    allProjects: null,
};
export const filterDevis = {
    customer: null,
    contremarque: null,
    commercial: null,
    devis: null,
};

export const filterSuiviDi = {
    customer: null,
    contremarque: null,
    diNumber: null,
    carpetStatus: null,
    contremarqueId: null,
};

export const filterClientInvoice = {
    customer: null,
    invoiceNumber: null,
    rn: null,
    date_from: null,
    date_to: null,
    contremarque: null,
};

export const filterImageCommande = {
    client: null,
    rn: null,
    collection: null,
    contremarque: null,
    etatTapis: null,
    modele: null,
    commercial: null,
    atelier: null,
    commande: null,
    devis: null,
    prescripteur: null,
};

export const customerInstructionObject = {
    orderNumber: "",
    transmi_adv: "",
    customerComment: "",
    customerValidationDate: "",
    hasConstraints: false,
    hasValidateSample: false,
    hasFinitionInstruction: false,
    validatedSampleId: 0,
    finitionInstructionId: 0,
    constraintInstructionId: 0
};

export const particularCustomerGroupId = 1;
export const publicDiscountTypeId = 1;

export const designerStatusConst = [
    {id: 1, label: "Demarrage"},
    {id: 2, label: "Pause"},
    {id: 3, label: "Fin"}
];

export const carpetStatus = {
    nonTransmisId: 1,
    transmisId: 2,
    attribuId: 3,
    enCoursId: 4,
    enPauseId: 5,
    finiId: 6,
    annulStudioId: 7,
    annulCommercialId: 8,
    transmisAdvId: 9
};

export const statusNames = {
    1 : "Non transmis",
    2 : "Transmis",
    3 : "Attribué",
    4 : "En cours",
    5 : "En pause",
    6 : "Fini",
    7 : "Annulé studio",
    8 : "Annulé commercial",
    9 : "Transmis Adv",
};

export const devisDocxStyle = '@media print {' +
    '            .page-number:after {' +
    '                content: counter(page) " sur " counter(pages);' +
    '            }' +
    '            .no-page-break {' +
    '                page-break-inside: avoid;' +
    '            }' +
    '        }' +
    '        .page-header {' +
    '            position: fixed;' +
    '            top: 0;' +
    '            left: 0;' +
    '            right: 0;' +
    '            text-align: center;' +
    '            font-size: 20px;' +
    '            font-weight: bold;' +
    '            border: 2px solid black;' +
    '            padding: 10px;' +
    '            margin-bottom: 40px;' +
    '        }' +
    '        body {' +
    '            margin: auto;' +
    '            width: 90%;' +
    '            font-size: 12px;' +
    '        }' +
    '        h2 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: bold;' +
    '            text-decoration: none;' +
    '            font-size: 12px;' +
    '            ;' +
    '        }' +
    '        .p,' +
    '        p {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: normal;' +
    '            text-decoration: none;' +
    '            font-size: 12px;' +
    '            ;' +
    '            margin: 0pt;' +
    '        }' +
    '        .h1 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: bold;' +
    '            text-decoration: underline;' +
    '            font-size: 11.5pt;' +
    '        }' +
    '        .s1 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: normal;' +
    '            text-decoration: none;' +
    '            font-size: 9pt;' +
    '        }' +
    '        .s2 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: italic;' +
    '            font-weight: normal;' +
    '            text-decoration: underline;' +
    '            font-size: 12pt;' +
    '        }' +
    '        .s3 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: bold;' +
    '            text-decoration: none;' +
    '            font-size: 9pt;' +
    '        }' +
    '        .s4 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: bold;' +
    '            text-decoration: none;' +
    '            font-size: 12pt;' +
    '        }' +
    '        .s5 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: italic;' +
    '            font-weight: normal;' +
    '            text-decoration: none;' +
    '            font-size: 12pt;' +
    '        }' +
    '        .s6 {' +
    '            color: #585858;' +
    '            font-family: Garamond, serif;' +
    '            font-style: normal;' +
    '            font-weight: normal;' +
    '            text-decoration: underline;' +
    '            font-size: 12px;' +
    '            ;' +
    '        }' +
    '        table,' +
    '        tbody {' +
    '            width: 100%;' +
    '            vertical-align: top;' +
    '            overflow: visible;' +
    '        }' +
    '        @page {' +
    '            margin-top: 40mm;' +
    '        }' +
    '        .page-break {' +
    '            page-break-before: always;' +
    '        }';
