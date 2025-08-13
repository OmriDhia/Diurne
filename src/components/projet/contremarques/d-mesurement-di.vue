<template>
    <div>
        <div class="row align-items-center justify-content-end p-0 pt-2 mt-3">
            <div class="col-auto">
                <button class="btn btn-custom mb-2 font-size-0-7" @click="calculateInchesFeet" :disabled="disabled">Calculer</button>
            </div>
        </div>
        <div class="row align-items-center justify-content-between p-0 pt-2">
            <template v-for="(measurement, index) in measurements" :key="index">
                <div class="col-xl-5-1 col-md-12 mt-2 mb-2 pe-0">
                    <div class="row align-items-start">
                        <h6 class="w-100">{{ measurement.name }} <span class="required">*</span></h6>
                    </div>
                    <div class="card p-0" :class="{ 'is-invalid': error }">
                        <div class="card-body ps-2 mt-2">
                            <div class="row">
                                <template v-for="(unit, uIndex) in measurement.unit" :key="uIndex">
                                    <div class="col-12 col-md-4">
                                        <div class="row align-items-center">
                                            <div class="col-12 col-sm-auto text-black mb-1 mb-sm-0">
                                                {{ unit.abbreviation }}
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                <input class="form-control text-center" v-model="unit.value" :disabled="disabled" @change="handleChange(unit.abbreviation)" />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card p-0" :class="{ 'is-invalid': error }">
                        <div class="card-body ps-2 mt-2">
                            <div class="row g-3">
                                <template v-for="(unit, uIndex) in measurement.unit" :key="uIndex">
                                    <div class="col-12 col-sm-4">
                                        <div class="d-flex flex-column flex-sm-row align-items-center mb-2">
                                        <!-- <div class="d-flex align-items-center mb-2"> 
                                            <label class="col-2 col-sm-3 text-black fw-bold me-3 mb-0">
                                                {{ unit.abbreviation }}
                                            </label>
                                            <input class="form-control text-center" v-model="unit.value" :disabled="disabled" @change="handleChange(unit.abbreviation)" />
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div> -->
                </div>
            </template>
            <div v-if="error" class="invalid-feedback">{{ $t('tous Les champs sont obligatoire.') }}</div>
        </div>
    </div>
</template>

<script setup>
    import { useStore } from 'vuex';
    import { computed, onMounted, ref, watch } from 'vue';
    import contremarqueService from '../../../Services/contremarque-service';
    import dInput from '../../base/d-input.vue';

    const props = defineProps({
        dimensionsProps: {
            type: Object,
        },
        firstLoad: {
            type: Boolean,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        error: {
            type: Boolean,
            default: false,
        },
    });
    const emit = defineEmits(['changeMeasurements']);
    const store = useStore();
    const measurements = ref([]);

    const data = ref({
        largCm: 0,
        lngCm: 0,
        largFeet: 0,
        lngFeet: 0,
        largInches: 0,
        lngInches: 0,
        InputUnit: 'cm',
        quoteDetailId: 0,
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
        const larg = measurements.value.find((m) => m.name === 'Largeur');
        const long = measurements.value.find((m) => m.name === 'Longueur');
        if (larg) {
            setMeasurements(larg.unit, 'larg');
        }
        if (long) {
            setMeasurements(long.unit, 'lng');
        }

        try {
            const result = await contremarqueService.calculateMesurementsNew(data.value);
            const dimension = result.dimension;
            setMeasurementResults(larg.unit, dimension.width);
            setMeasurementResults(long.unit, dimension.length);
            store.commit('setMeasurements', measurements);
        } catch (error) {
            console.error(`Error calculation mesurements:`, error);
        }
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
                    value: '',
                })),
            }));

            formatDataValue();
        } catch (e) {
            console.error(e.message);
        }
    };

    const handleChange = (abb) => {
        data.value.InputUnit = abb;
    };

    onMounted(() => {
        getMeasurements();
    });

    watch(
        () => measurements.value,
        (newMeasurements) => {
            store.commit('setMeasurements', newMeasurements);
            if (!props.firstLoad) {
                emit('changeMeasurements', newMeasurements);
            }
        },
        { deep: true }
    );
    watch(
        () => props.dimensionsProps,
        (dimensions) => {
            formatDataValue();
        }
    );
</script>
