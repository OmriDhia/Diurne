export default {
    state: {
        layout: "app",
        page_class: "",
        loading: false,
        genders: [],
        addressTypes: [],
        countries: [],
        nomenclatures: [],
        languages: [],
        intermediaryTypes: [],
        diObject: {
            format: "",
            deadline: new Date(),
            transmitted_to_studio: false,
            transmition_date: null,
            unit_id: 0,
            contremarque_id: 0
        }
    },
    mutations: {
        setLayout(state, payload) {
            state.layout = payload;
        },
        setLoading(state, payload) {
            state.loading = payload;
        },
        setPageClass(state, payload) {
            state.page_class = payload;
        },
        setGenders(state, payload) {
            state.genders = payload;
        },
        setAddressTypes(state, payload) {
            state.addressTypes = payload;
        },
        setCountries(state, payload) {
            state.countries = payload;
        },
        setNomenclatures(state, payload) {
            state.nomenclatures = payload;
        },
        setLanguages(state, payload) {
            state.languages = payload;
        },
        setIntermediaryTypes(state, payload) {
            state.intermediaryTypes = payload;
        },
        setDiObject(state, payload) {
            state.diObject = payload;
        },
    },
    getters: {
        layout(state) {
            return state.layout;
        },
        loading(state) {
            return state.loading;
        },
        pageClass(state) {
            return state.page_class;
        },
        genders(state) {
            return state.genders;
        },
        addressTypes(state) {
            return state.addressTypes;
        },
        countries(state) {
            return state.countries;
        },
        nomenclatures(state) {
            return state.nomenclatures;
        },
        languages(state) {
            return state.languages;
        },
        intermediaryTypes(state) {
            return state.intermediaryTypes;
        },
        diObject(state) {
            return state.diObject;
        },
    },
}
