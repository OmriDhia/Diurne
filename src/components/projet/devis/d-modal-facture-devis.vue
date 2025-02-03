<template>
    <div>
        <d-base-modal id="downloadFacture" title="Téléchargement facture" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <Editor
                        :api-key="apiKey"
                        :init="initEditor"
                        v-model="editorData"
                    />
                </div>
            </template>
            <template v-slot:modal-footer>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="exportToPDF">Export PDF</button>
                <button class="btn btn-custom pe-2 ps-2" @click.prevent="exportToDOCX">Export DOCX</button>
            </template>
        </d-base-modal>
    </div>
</template>

<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import { TINYMCE_API_KEY } from '../../../config/config'
    import { devisDocxStyle } from '../../../composables/constants'
    import html2pdf from 'html2pdf.js';
    import dBaseModal from "../../base/d-base-modal.vue";
    import quoteService from '../../../Services/quote-service';
    import { asBlob } from 'html-docx-ts';
    import { saveAs } from 'file-saver';
    import Editor from '@tinymce/tinymce-vue'

    const props = defineProps({
        quoteId: {
            type: Number,
        },
    });
    const emit = defineEmits(['onClose']);
    const apiKey = TINYMCE_API_KEY;
    const initEditor = computed(() => {
        return {
                deprecation_warnings: false,
                selector: 'textarea',
                language: 'fr_FR',
                min_height: 300,
                menubar: true,
                branding: false,
                forced_root_block: '',
                fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 24px 25px 26px 27px 28px 29px 30px 32px 34px 36px 38px 40px 51px 61px 71px",
                plugins: 'autoresize advlist autolink lists link image charmap print preview searchreplace visualblocks code insertdatetime media table paste code wordcount',
                toolbar: 'undo redo | code | bold italic backcolor forecolor | alignleft aligncenter alignright alignjustify \ ' +
                    'formatselect | fontselect | fontsizeselect | lineheight',
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
            }
    });

    const editorData = ref('');
    
    const exportToPDF = () => {
        const contentToExport = editorData.value;
        html2pdf()
            .set({ margin: 5 })
            .from(contentToExport)
            .toPdf()
            .get('pdf')
            .then((pdf) => {
                pdf.save(`Facture_devis_${props.quoteId}.pdf`);
            });
    };
    
    onMounted(() => {
        if (props.quoteId) {
            getQuoteHtml(props.quoteId);
        }
    });
    
   const exportToDOCX = async () => {
       const contentToExport =  editorData.value;
       asBlob(contentToExport).then(blobData => {
           saveAs(blobData, `Facture_devis_${props.quoteId}.docx`,{centerStr: 'Diurne'}) // save as docx document
       })
    };
   
    const getQuoteHtml = async (id) => {
        try {
            if (id) {
                const fetchedHtml = await quoteService.getQuoteHtml(id);
                editorData.value = fetchedHtml;
                editor.value.commands.setContent(editorData.value); // Set content in the editor
            }
        } catch (e) {
            console.error("Error fetching quote HTML:", e.message);
        }
    };

    // Close modal
    const handleClose = () => {
        emit('onClose');
    };
</script>
