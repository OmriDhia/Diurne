export default {
    state: {
        layout: "app",
        page_class: "",
        genders: [],
        addressTypes: [],
        countries: [],
        nomenclatures: [],
        languages: [],
    },
    mutations: {
        setLayout(state, payload) {
            state.layout = payload;
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
    },
    getters: {
        layout(state) {
            return state.layout;
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
    },
}
