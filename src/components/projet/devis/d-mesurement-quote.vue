<template>
    <div class="pe-3">
        <div class="row align-items-center justify-content-between p-0 pt-2">
            <template v-for="(measurement, index) in measurements" :key="index">
                <div class="col-md-4">
                    <template v-for="(unit, uIndex) in measurement.unit" :key="uIndex">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-black">{{ measurement.name }} en {{ unit.abbreviation }}:</div>
                            <div class="col-md-6">
                                <input class="form-control text-center" v-model="unit.value" :disabled="disabled" @change="handleChange(unit.abbreviation)" />
                                <!-- Show error message if available -->
                                <div v-if="showErrors && getErrorMessages(measurement.name, unit.abbreviation)" class="invalid-feedback">
                                    {{ getErrorMessages(measurement.name, unit.abbreviation) }}
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <div class="col-md-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-black">Surface en m²:</div>
                    <div class="col-md-6">
                        <input class="form-control text-center" v-model="sufaceM2" :disabled="true" />
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 text-black">Surface en sqft:</div>
                    <div class="col-md-6">
                        <input class="form-control text-center" v-model="sufaceSqft" :disabled="true" />
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 text-black">Poids (kg):</div>
                    <div class="col-md-6">
                        <input class="form-control text-center" v-model="weight" @change="handleWeight" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center pt-4 d-flex">
            <div class="col-auto">
                <button id="clickConvertCalculation" :disabled="calculationLoader" class="btn btn-custom ps-4 pe-4" @click="calculateInchesFeet">
                    <vue-feather type="sun" animation="spin" v-if="calculationLoader" class="me-2"></vue-feather> Calculer et Convertir
                </button>
            </div>
            <div class="col-auto">
                <div v-if="showErrors && error.message" class="invalid-feedback" style="font-size: 15px;">
                    {{error.message }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useStore } from 'vuex';
    import VueFeather from 'vue-feather';
    import dInput from '../../base/d-input.vue';
    import { computed, onMounted, ref, watch } from 'vue';
    import { Helper, formatErrorViolations, handleObjectViolations, handleStringViolations } from '../../../composables/global-methods';
    import contremarqueService from '../../../Services/contremarque-service';

    const props = defineProps({
        dimensionsProps: {
            type: Object,
        },
        quoteDetailId: {
            type: Number,
            default: 0,
        },
        firstLoad: {
            type: Boolean,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        calculateHt: {
            type: Boolean,
            default: false,
        },
        totalHt: {
            type: Number,
            default: 0,
        },
        globalWeight: {
            type: String,
            default: '',
        },
        currencyId: {
            type: Number,
            default: 0,
        },
        areaSquareFeet: {
            type: Number,
        },
        areaSquareMeter: {
            type: Number,
        },
    });
    const emit = defineEmits(['changePrices', 'changeWeight']);
    const store = useStore();
    const measurements = ref([]);
    const sufaceM2 = ref('');
    const sufaceSqft = ref('');
    const weight = ref('');
    const calculationLoader = ref(false);
    const data = ref({
        largCm: 0,
        lngCm: 0,
        largFeet: 0,
        lngFeet: 0,
        largInches: 0,
        lngInches: 0,
        InputUnit: 'cm',
        quoteDetailId: 0,
        currencyId: 0,
    });
    const error = ref({});
    const showErrors = ref(false); // Flag to control error display

    const setMeasurements = (units, prefix) => {
        for (const u of units) {
            const mes = u.value ? parseFloat(u.value) : 0;
            switch (u.abbreviation) {
                case 'cm':
                    data.value[`${prefix}Cm`] = mes;
                    break;
                case 'ft':
                    data.value[`${prefix}Feet`] = mes;
                    break;
                case 'inch':
                    data.value[`${prefix}Inches`] = mes;
                    break;
            }
        }
    };

    const setMeasurementResults = (units, val) => {
        for (const u of units) {
            switch (u.abbreviation) {
                case 'cm':
                    u.value = val.cm;
                    break;
                case 'ft':
                    u.value = val.feet;
                    break;
                case 'inch':
                    u.value = val.inch;
                    break;
            }
        }
    };
    const calculateInchesFeet = async () => {
        calculationLoader.value = true;
        data.value.quoteDetailId = parseInt(props.quoteDetailId);
        if (props.calculateHt) {
            data.value.totalPriceHt = parseFloat(props.totalHt);
        } else {
            data.value.totalPriceHt = null;
            delete data.value.totalPriceHt;
        }
        if (props.currencyId) {
            data.value.currencyId = parseInt(props.currencyId);
        } else {
            data.value.currencyId = 0;
        }
        const larg = measurements.value.find((m) => m.name === 'Largeur');
        const long = measurements.value.find((m) => m.name === 'Longueur');
        if (larg) {
            setMeasurements(larg.unit, 'larg');
        }
        if (long) {
            setMeasurements(long.unit, 'lng');
        }

        try {
            error.value = {};
            showErrors.value = false;
            const result = await contremarqueService.calculateMesurementsNew(data.value);
            const dimension = result.dimension;
            sufaceM2.value = dimension.surface['m²'];
            sufaceSqft.value = dimension.surface.sqft;
            setMeasurementResults(larg.unit, dimension.width);
            setMeasurementResults(long.unit, dimension.length);
            store.commit('setMeasurements', measurements.value);
            emit('changePrices', result.price);
            window.showMessage("Le calcul s'est terminé avec succès");
        } catch (e) {
            // Ensure we're working with an actual object
            const violations = e?.violations || e?.response?.data?.violations;
            if (Array.isArray(violations) && violations.length > 0) {
                if (typeof violations[0] === 'string') {
                    // First case: array of strings
                    error.value = handleStringViolations(violations);
                } else if (typeof violations[0] === 'object' && violations[0].propertyPath) {
                    // Second case: array of objects
                    error.value = handleObjectViolations(violations);
                } else {
                    error.value = { message: e?.detail || 'An unexpected error occurred.' };
                }
            } else {
                error.value = { message: e?.detail || 'An unexpected error occurred.' };
            }
            showErrors.value = true; // Show errors when they are detected
            console.log('Final formatted error:', error.value);
        } finally {
            calculationLoader.value = false;
        }
    };

    // Function to get the error message for a specific measurement and unit
    const getErrorMessages = (measurementName, unitAbbr) => {
        let key = '';

        // Map "largeur" and "longueur" to "larg" and "lng"
        if (measurementName === 'Largeur') key += 'larg';
        else if (measurementName === 'Longueur') key += 'lng';
        // Correct unit abbreviation mapping
        const unitMap = {
            cm: 'Cm',
            ft: 'Feet',
            inch: 'Inches',
        };
        // Append the unit abbreviation (capitalize first letter)
        key += unitMap[unitAbbr] || unitAbbr; // Use mapped unit or fallback
        console.log(key, error.value[key]);
        return error.value[key] || ''; // Return the corresponding error message or an empty string
    };

    const formatDataValue = () => {
        if (props.dimensionsProps) {
            measurements.value = measurements.value.map((m) => {
                const d = props.dimensionsProps[m.id];
                if (d) {
                    m.unit = m.unit.map((u) => {
                        let tmpU = d.find((t) => t.unit_id === u.id);
                        if (tmpU) {
                            u.value = parseFloat(tmpU.value);
                        }
                        return u;
                    });
                }
                return m;
            });
            store.commit('setMeasurements', measurements.value);
        }
    };
    const getMeasurements = async () => {
        try {
            const unitOfMeasurements = await contremarqueService.getUnitOfMeasurements();
            const meas = await contremarqueService.getMeasurements();

            measurements.value = meas.map((m) => ({
                ...m,
                unit: unitOfMeasurements.map((u) => ({
                    ...u,
                    value: null,
                })),
            }));

            formatDataValue();
        } catch (e) {
            console.error(e.message);
        }
    };

    onMounted(() => {
        affectAreaSquareWeight();
        getMeasurements();
    });

    const handleWeight = () => {
        emit('changeWeight', weight.value);
    };

    const affectAreaSquareWeight = () => {
        if (props.globalWeight) {
            weight.value = Helper.FormatNumber(props.globalWeight);
        }
        if (props.areaSquareMeter) {
            sufaceM2.value = Helper.FormatNumber(props.areaSquareMeter);
        }
        if (props.areaSquareFeet) {
            sufaceSqft.value = Helper.FormatNumber(props.areaSquareFeet);
        }
    };

    watch(
        () => props.dimensionsProps,
        () => {
            formatDataValue();
        }
    );
    watch(
        () => [props.areaSquareMeter, props.areaSquareFeet, props.globalWeight],
        () => {
            affectAreaSquareWeight();
        }
    );

    const handleChange = (abb) => {
        data.value.InputUnit = abb;
    };
</script>
