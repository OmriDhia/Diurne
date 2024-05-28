<template>
    <div class="layout-px-spacing mt-4">
        <d-page-title :title="title"></d-page-title>

        <div class="row layout-top-spacing mt-3">
            <div class="panel br-6 p-2">
                <div class="row d-flex justify-content-center justify-items-center gap-2">
                    <div class="col-auto text-size-16 text-dark">
                        {{ $t('Type de recherche')}}:
                    </div>
                    <div class="col-auto">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="contact-radio" v-model="type" name="type" value="contact"/>
                            <label class="custom-control-label text-size-16 text-dark" for="contact-radio"> {{ $t('Contact') }} </label>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="radio-success custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="event-radio" v-model="type" name="type" value="event"/>
                            <label class="custom-control-label text-size-16 text-dark" for="event-radio"> {{ $t('Evènement') }} </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import dPageTitle from '../../components/common/d-page-title.vue';
import { ref, watch } from 'vue';

import { useMeta } from '/src/composables/use-meta';
useMeta({ title: 'Contremarque' });

const type = ref('contact');
const title = ref('Contremarque');
watch(type, (newValue, oldValue) => {
    if(newValue === 'contact'){
        title.value = 'Contacts';
    }else{
        title.value = 'évènement';  
    }
});
    
</script>
<style>
    .text-size-16{
        font-size: 16px !important;
    }
</style>
