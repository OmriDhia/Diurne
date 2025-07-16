<template>
    <div>
        <d-base-modal id="downloadFactureProforma" title="Téléchargement facture proforma" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <Editor
                        crossorigin="anonymous"
                        :api-key="apiKey"
                        :init="initEditor"
                        v-model="editorData"
                    />
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="exportToPDF"  :disabled="loading">
                    <btn-load-icon v-if="loading"></btn-load-icon>
                    Export PDF
                </button>
            </template>
        </d-base-modal>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import btnLoadIcon from '../../common/svg/btn-load-icon.vue';
    import { TINYMCE_API_KEY } from '../../../config/config'
    import { devisDocxStyle } from '../../../composables/constants'
    import axiosInstance from '../../../config/http';
    import dBaseModal from "../../base/d-base-modal.vue";
    import quoteService from '../../../Services/quote-service';
    import { saveAs } from 'file-saver';
    import Editor from '@tinymce/tinymce-vue'

    const props = defineProps({
        quoteId: {
            type: Number,
        },
    });
    let changedHtml = false
    const emit = defineEmits(['onClose']);
    const loading = ref(false);
    const apiKey = TINYMCE_API_KEY;
    const initEditor = {
                deprecation_warnings: false,
                selector: 'textarea',
                language: 'fr_FR',
                min_height: 300,
                menubar: true,
                branding: false,
                forced_root_block: '',
                fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 24px 25px 26px 27px 28px 29px 30px 32px 34px 36px 38px 40px 51px 61px 71px",
                plugins: 'autoresize advlist autolink lists link image charmap preview searchreplace visualblocks code insertdatetime media table wordcount',
                toolbar: 'undo redo | bold italic backcolor forecolor | alignleft aligncenter alignright alignjustify \ ' +
                    'formatselect | fontselect | fontsizeselect | lineheight ',
                file_picker_types: 'image',
                automatic_uploads: true,
                image_title: true,
                content_style: devisDocxStyle,
                file_picker_callback: function (cb, value, meta) {
                    let input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function () {
                        let file = this.files[0];
        
                        let reader = new FileReader();
                        reader.onload = function () {
                            let id = 'blobid' + (new Date()).getTime();
                            let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            let base64 = reader.result.split(',')[1];
                            let blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {title: file.name});
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
            };

    const editorData = ref('');
    const utf8ToBase64 = (str) => btoa(unescape(encodeURIComponent(str)));
    const exportToPDF = async () => {
        loading.value = true;
        let contentToExport = editorData.value;
        if (changedHtml) {
            contentToExport = ` <!DOCTYPE html>
                                <html lang=fr>
                                    <head>
                                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                        <title>A document with a short head</title>
                                        <style> 
                                            ${devisDocxStyle} 
                                        </style> 
                                    </head>
                                    <body> 
                                        ${editorData.value}
                                    </body>
                                    </html>`;
        }

        try { 	
            const res = await axiosInstance.post(`/api/export-quote-pdf/${props.quoteId}`, { 
                html: utf8ToBase64(contentToExport)
            },{
                responseType: "blob"
            });
            const pdfBlob = new Blob([res.data], { type: "application/pdf" });
            saveAs(pdfBlob, `Facture_devis_proforma${props.quoteId}.pdf`)
        } catch (error) {
            console.error("Error exporting quote to PDF:", error.message);
            throw new Error('Échec de récupération de devis pdf');
        }finally{
            loading.value = false;
        }
    };
    
    onMounted(() => {
        if (props.quoteId) {
            getQuoteHtml(props.quoteId);
        }
    });
    
    const getQuoteHtml = async (id) => {
        try {
            if (id) {
                const fetchedHtml = await quoteService.getQuoteHtml(id);
                editorData.value = fetchedHtml;
            }
        } catch (e) {
            console.error("Error fetching quote HTML:", e.message);
        }
    };

    watch(() => editorData.value, async (newVal) => {
        changedHtml = true;
    });
    const handleClose = () => {
        emit('onClose');
    };
</script>
