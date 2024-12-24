<template>
    <div class="pe-3">
        <div class="row align-items-center justify-content-between p-0 pt-2">
            <template v-for="(measurement, index) in measurements" :key="index">
                <div class="col-md-4">
                    <template v-for="(unit, uIndex) in measurement.unit" :key="uIndex">
                        <div class="row align-items-center">
                            <div class="col-md-6 text-black">
                                {{ measurement.name }} en {{ unit.abbreviation }}:
                            </div>
                            <div class="col-md-6">
                                <input class="form-control text-center" v-model="unit.value" :disabled="disabled" @change="handleChange(unit.abbreviation)">
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <div class="col-md-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-black">
                        Surface en m²:
                    </div>
                    <div class="col-md-6">
                        <input class="form-control text-center" v-model="sufaceM2" :disabled="true">
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 text-black">
                        Surface en sqft:
                    </div>
                    <div class="col-md-6">
                        <input class="form-control text-center" v-model="sufaceSqft" :disabled="true">
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 text-black">
                        Poids (kg):
                    </div>
                    <div class="col-md-6">
                        <input class="form-control text-center" v-model="weight" @change="handleWeight">
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center pt-4">
            <div class="col-auto">
                <button id="clickConvertCalculation" class="btn btn-custom ps-4 pe-4" @click="calculateInchesFeet">Calculer et Convertir</button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {useStore} from "vuex";
    import {computed, onMounted, ref, watch} from "vue";
    import contremarqueService from "../../../Services/contremarque-service";
    import dInput from "../../base/d-input.vue";

    const props = defineProps({
        dimensionsProps : {
            type: Object
        },
        quoteDetailId : {
            type: Number,
            default: 0
        },
        firstLoad : {
            type: Boolean
        },
        disabled: {
            type: Boolean,
            default: false
        },
        calculateHt: {
            type: Boolean,
            default: false
        },
        totalHt: {
            type: Number,
            default: 0
        },
        globalWeight: {
            type: String,
            default: ""
        },
        currencyId: {
            type: Number,
            default: 0
        }
    });
    const emit = defineEmits(['changePrices','changeWeight']);
    const store = useStore();
    const measurements = ref([]);
    const sufaceM2 = ref("");
    const sufaceSqft = ref("");
    const weight = ref("");
    const data = ref({
        largCm: 0,
        lngCm: 0,
        largFeet: 0,
        lngFeet: 0,
        largInches: 0,
        lngInches: 0,
        InputUnit: "cm",
        quoteDetailId: 0,
        currencyId: 0
    });
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

    const setMeasurementResults = (units,val) => {
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
        data.value.quoteDetailId = parseInt(props.quoteDetailId);
        if(props.calculateHt){
            data.value.totalPriceHt = parseFloat(props.totalHt);
        }
        if(props.currencyId){
            data.value.currencyId = parseInt(props.currencyId);
        }
        const larg = measurements.value.find(m => m.name === 'Largeur');
        const long = measurements.value.find(m => m.name === 'Longueur');
        if (larg) {
            setMeasurements(larg.unit, 'larg');
        }
        if (long) {
            setMeasurements(long.unit, 'lng');
        }
        
        try {
            const result = await contremarqueService.calculateMesurementsNew(data.value);
            const dimension = result.dimension
            sufaceM2.value = dimension.surface['m²'];
            sufaceSqft.value = dimension.surface.sqft;
            setMeasurementResults(larg.unit,dimension.larg);
            setMeasurementResults(long.unit,dimension.lng);
            store.commit('setMeasurements', measurements.value);
            emit('changePrices', result.price);
            
            window.showMessage("Le calcul s'est terminé avec succès")
        } catch (error) {
            console.error(`Error calculation mesurements:`, error);
        }
    };

    const formatDataValue = () => {
        if (props.dimensionsProps) {
            measurements.value = measurements.value.map(m => {
                const d = props.dimensionsProps[m.id];
                if (d) {
                    m.unit = m.unit.map(u => {
                        let tmpU = d.find(t => t.unit_id === u.id);
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

            measurements.value = meas.map(m => ({
                ...m,
                unit: unitOfMeasurements.map(u => ({
                    ...u,
                    value: null
                }))
            }));

            formatDataValue();
        } catch (e) {
            console.error(e.message);
        }
    };

    onMounted(() => {
        weight.value = props.globalWeight;
        getMeasurements();
    });
    
    const handleWeight = () =>{
        emit('changeWeight',weight.value)
    };
    
    watch(
        () => props.dimensionsProps,
        () => {
            formatDataValue();
        }
    );
    watch(
        () => props.globalWeight,
        () => {
            weight.value = props.globalWeight;
        }
    );
    
    const handleChange = (abb) => {
        data.value.InputUnit = abb;
    }
</script>
