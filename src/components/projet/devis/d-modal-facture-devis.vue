<template>
    <div>
        <d-base-modal id="downloadFacture" title="Téléchargement facture" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <ckeditor v-if="editor && config" v-model="html" :editor="editor" :config="config" />
                </div>
            </template>
        </d-base-modal>
    </div>
</template>
<script setup>
    import { ref, onMounted, watch, computed, watchEffect } from 'vue';
    import dBaseModal from "../../base/d-base-modal.vue";
    import quoteService from '../../../Services/quote-service';
    import { Ckeditor } from '@ckeditor/ckeditor5-vue';

    import {
        ClassicEditor,
        Autoformat,
        AutoImage,
        Autosave,
        BlockQuote,
        Bold,
        CKBox,
        CKBoxImageEdit,
        CloudServices,
        Code,
        Essentials,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        FullPage,
        GeneralHtmlSupport,
        Heading,
        Highlight,
        HtmlComment,
        HtmlEmbed,
        ImageBlock,
        ImageCaption,
        ImageInline,
        ImageInsert,
        ImageInsertViaUrl,
        ImageResize,
        ImageStyle,
        ImageTextAlternative,
        ImageToolbar,
        ImageUpload,
        Indent,
        IndentBlock,
        Italic,
        Link,
        LinkImage,
        List,
        ListProperties,
        MediaEmbed,
        Paragraph,
        PasteFromOffice,
        PictureEditing,
        RemoveFormat,
        ShowBlocks,
        SourceEditing,
        Subscript,
        Superscript,
        Table,
        TableCaption,
        TableCellProperties,
        TableColumnResize,
        TableProperties,
        TableToolbar,
        TextTransformation,
        TodoList,
        Underline
    } from 'ckeditor5';
    import { ExportPdf, ExportWord } from 'ckeditor5-premium-features';

    import 'ckeditor5/ckeditor5.css';
    import 'ckeditor5-premium-features/ckeditor5-premium-features.css';

    const LICENSE_KEY =
        'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3MzYyOTQzOTksImp0aSI6IjNiNGE2MTE0LWNkNjgtNGVhZS1iYmFmLTBlMWJmYTA5NWNmZiIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6IjlmMjYyYzMyIn0.EZ9mrf9ovih7jEJaTz3-3cwGLLDG00imc4gfnptts4Tv_A_u8y-0v9KDwvkEF1O1JJbap44r0EBtQ3hISkow8A';

    const CLOUD_SERVICES_TOKEN_URL =
        'https://nir5jph3vk66.cke-cs.com/token/dev/095a9818e6433a1762ddf39efc6d4f8bfb6dcdc08c2c537ce96eaad83f18?limit=10';

    const isLayoutReady = ref(false);

    const editor = ClassicEditor;

    const config = computed(() => {
        if (!isLayoutReady.value) {
            return null;
        }

        return {
            toolbar: {
                items: [
                    'sourceEditing',
                    'exportWord',
                    'exportPdf',
                    'showBlocks',
                    '|',
                    'heading',
                    '|',
                    'fontSize',
                    'fontFamily',
                    'fontColor',
                    'fontBackgroundColor',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'subscript',
                    'superscript',
                    'code',
                    'removeFormat',
                    '|',
                    'link',
                    'insertImage',
                    'ckbox',
                    'mediaEmbed',
                    'insertTable',
                    'highlight',
                    'blockQuote',
                    'htmlEmbed',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'todoList',
                    'outdent',
                    'indent'
                ],
                shouldNotGroupWhenFull: false
            },
            plugins: [
                Autoformat,
                AutoImage,
                Autosave,
                BlockQuote,
                Bold,
                CKBox,
                CKBoxImageEdit,
                CloudServices,
                Code,
                Essentials,
                ExportPdf,
                ExportWord,
                FontBackgroundColor,
                FontColor,
                FontFamily,
                FontSize,
                FullPage,
                GeneralHtmlSupport,
                Heading,
                Highlight,
                HtmlComment,
                HtmlEmbed,
                ImageBlock,
                ImageCaption,
                ImageInline,
                ImageInsert,
                ImageInsertViaUrl,
                ImageResize,
                ImageStyle,
                ImageTextAlternative,
                ImageToolbar,
                ImageUpload,
                Indent,
                IndentBlock,
                Italic,
                Link,
                LinkImage,
                List,
                ListProperties,
                MediaEmbed,
                Paragraph,
                PasteFromOffice,
                PictureEditing,
                RemoveFormat,
                ShowBlocks,
                SourceEditing,
                Subscript,
                Superscript,
                Table,
                TableCaption,
                TableCellProperties,
                TableColumnResize,
                TableProperties,
                TableToolbar,
                TextTransformation,
                TodoList,
                Underline
            ],
            cloudServices: {
                tokenUrl: CLOUD_SERVICES_TOKEN_URL
            },
            exportPdf: {
                stylesheets: [
                    /* This path should point to application stylesheets. */
                    /* See: https://ckeditor.com/docs/ckeditor5/latest/features/converters/export-pdf.html */
                    './style.css',
                    /* Export PDF needs access to stylesheets that style the content. */
                    'https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css',
                    'https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.css'
                ],
                fileName: 'export-pdf-demo.pdf',
                converterOptions: {
                    format: 'Tabloid',
                    margin_top: '20mm',
                    margin_bottom: '20mm',
                    margin_right: '24mm',
                    margin_left: '24mm',
                    page_orientation: 'portrait'
                }
            },
            exportWord: {
                stylesheets: [
                    /* This path should point to application stylesheets. */
                    /* See: https://ckeditor.com/docs/ckeditor5/latest/features/converters/export-word.html */
                    './style.css',
                    /* Export Word needs access to stylesheets that style the content. */
                    'https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css',
                    'https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.css'
                ],
                fileName: 'export-word-demo.docx',
                converterOptions: {
                    document: {
                        orientation: 'portrait',
                        size: 'Tabloid',
                        margins: {
                            top: '20mm',
                            bottom: '20mm',
                            right: '24mm',
                            left: '24mm'
                        }
                    }
                }
            },
            fontFamily: {
                supportAllValues: true
            },
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            heading: {
                options: [
                    {
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            htmlSupport: {
                allow: [
                    {
                        name: /^.*$/,
                        styles: true,
                        attributes: true,
                        classes: true
                    }
                ]
            },
            image: {
                toolbar: [
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'imageStyle:inline',
                    'imageStyle:wrapText',
                    'imageStyle:breakText',
                    '|',
                    'resizeImage',
                    '|',
                    'ckboxImageEdit'
                ]
            },
            licenseKey: LICENSE_KEY,
            link: {
                addTargetToExternalLinks: true,
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            placeholder: 'Type or paste your content here!',
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
            }
        };
    });

    onMounted(() => {
        isLayoutReady.value = true;
    });

    watchEffect(() => {
        if (config.value) {
            configUpdateAlert(config.value);
        }
    });

 
    function configUpdateAlert(config) {
        if (configUpdateAlert.configUpdateAlertShown) {
            return;
        }

        const isModifiedByUser = (currentValue, forbiddenValue) => {
            if (currentValue === forbiddenValue) {
                return false;
            }

            if (currentValue === undefined) {
                return false;
            }

            return true;
        };

        const valuesToUpdate = [];

        configUpdateAlert.configUpdateAlertShown = true;

        if (!isModifiedByUser(config.cloudServices?.tokenUrl, '<YOUR_CLOUD_SERVICES_TOKEN_URL>')) {
            valuesToUpdate.push('CLOUD_SERVICES_TOKEN_URL');
        }

        if (valuesToUpdate.length) {
            window.alert(
                [
                    'Please update the following values in your editor config',
                    'to receive full access to Premium Features:',
                    '',
                    ...valuesToUpdate.map(value => ` - ${value}`)
                ].join('\n')
            );
        }
    }
    
    const props = defineProps({
        quoteId: {
            type: Number
        },
    });
    
    const html = ref('');
    const getQuoteHtml = async (id) =>{
        try{
            if(id){
                html.value = await quoteService.getQuoteHtml(id);
                console.log(html.value);
            }
        }catch (e){
            console.log(e.message)
        }
    };

    onMounted(() => {
        if(props.quoteId){
            getQuoteHtml(props.quoteId)  
        }
    });
    
    const emit = defineEmits(['onClose']);
    watch(props.quoteId, (quoteId) => {
        getQuoteHtml(quoteId)
    });
    const handleClose = () => {
        emit('onClose')
    };
</script>
