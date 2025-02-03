<template>
    <div class="date-range-picker">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="row align-items-center">
                    <div class="col-4">
                        <label for="start-date">Date d'attribution:</label>
                    </div>
                    <div class="col-8">
                        <input type="date" id="start-date" v-model="startDate" @change="updateDates" />
                    </div>
                </div>
                
            </div>
            <div class="col-md-6 col-sm-12 pe-0" v-if="!props.hideDateEnd">
                <div class="row align-items-center">
                    <div class="col-4">
                        <label for="end-date">Date de fin:</label>
                    </div>
                    <div class="col-8">
                        <input type="date" id="end-date" v-model="endDate" @change="updateDates" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, watch } from 'vue';
    import {Helper} from "../../composables/global-methods";

    const props = defineProps({
        dates: {
            type: Object
        },
        hideDateEnd:{
            type: Boolean,
            default: false,
        }
    });
    const date = Helper.FormatDate(new Date());
    const startDate = ref(Helper.FormatDate(new Date()));
    const endDate = ref('');
    
    const emit = defineEmits(['update:dates']);
    
    const updateDates = () => {
        emit('update:dates', { startDate: startDate.value, endDate: endDate.value });
    };

    onMounted(() => {
        affectData(props.dates)
    });
    const affectData = (dates) => {
        startDate.value = dates.startDate || date;
        endDate.value = dates.endDate || '';
    };
    watch(
        () => props.dates,
        (newVal) => {
            affectData(newVal);
        }
    );
</script>

<style scoped>
    .date-range-picker {
        display: flex;
        flex-direction: column;
    }

    .date-range-picker label {
        margin-top: 10px;
    }
</style>
