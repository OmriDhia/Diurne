<template>
    <d-base-page>
        <template v-slot:title>
            <d-page-title :title="title"></d-page-title>
        </template>
        <template v-slot:header>
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
        </template>

        <template v-slot:body>
           <d-contact-list v-if="type === 'contact'"></d-contact-list>
           <d-event-list v-if="type === 'event'"></d-event-list>
        </template>
        
    </d-base-page>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import dBasePage from "../../components/base/d-base-page.vue";
import dPageTitle from "../../components/common/d-page-title.vue";
import dEventList from "../../components/contacts/d-event-list.vue";
import dContactList from "../../components/contacts/d-contact-list.vue";
import { useMeta } from '/src/composables/use-meta';
import { CONTACT_SELECTION_STORAGE_NAME } from "../../composables/constants";

useMeta({ title: 'Contacts' });

let selectedType = 'contact';
if(localStorage.getItem(CONTACT_SELECTION_STORAGE_NAME)){
    selectedType = localStorage.getItem(CONTACT_SELECTION_STORAGE_NAME);
}
const type = ref(selectedType);
const title = ref(null);

const affectTitle = (type) => {
    if(type === 'contact'){
        title.value = 'Contacts';
    }else{
        title.value = 'évènement';
    }
    console.log("contact");
};

onMounted(()=>{
    affectTitle(type.value); 
})

watch(type, (newValue) => {
    localStorage.setItem(CONTACT_SELECTION_STORAGE_NAME,newValue);
    affectTitle(newValue)
});
    
</script>
<style>
    .text-size-16{
        font-size: 16px !important;
    }
</style>
