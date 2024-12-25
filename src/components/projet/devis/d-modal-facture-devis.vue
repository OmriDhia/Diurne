<template>
    <div>
        <d-base-modal id="downloadFacture" title="Téléchargement facture" @onClose="handleClose">
            <template v-slot:modal-body>
                <div class="col-12">
                    <!-- Tiptap Editor -->
                    <div class="editor-toolbar">
                        <button @click="setBold">Bold</button>
                        <button @click="setItalic">Italic</button>
                        <button @click="setUnderline">Underline</button>
                        <button @click="setStrike">Strike</button>
                        <button @click="setHeading(1)">H1</button>
                        <button @click="setHeading(2)">H2</button>
                        <button @click="setBulletList">Bullet List</button>
                        <button @click="setOrderedList">Ordered List</button>
                        <button @click="setLink">Link</button>
                        <button @click="setImage">Image</button>
                    </div>
                    <editor-content :editor="editor" class="content"/>
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
    import { ref, watch, onMounted } from 'vue';
    import { useEditor, EditorContent } from '@tiptap/vue-3';
    import { StarterKit } from '@tiptap/starter-kit'; // This already includes BulletList, OrderedList, etc.
    import html2pdf from 'html2pdf.js';
    import dBaseModal from "../../base/d-base-modal.vue";
    import quoteService from '../../../Services/quote-service';
    import { asBlob } from 'html-docx-ts';
    import { saveAs } from 'file-saver';

    const props = defineProps({
        quoteId: {
            type: Number,
        },
    });

    // State
    const editorData = ref('<h1>This is header</h1><p>This is paragraph</p>');

    // Initialize editor
    const editor = useEditor({
        extensions: [
            StarterKit, // This includes all the necessary extensions for formatting, lists, and headings
        ],
        content: editorData.value,
    });

    // Methods for toolbar actions
    const setBold = () => {
        editor.value.chain().focus().toggleBold().run();
    };

    const setItalic = () => {
        editor.value.chain().focus().toggleItalic().run();
    };

    const setUnderline = () => {
        editor.value.chain().focus().toggleUnderline().run();
    };

    const setStrike = () => {
        editor.value.chain().focus().toggleStrike().run();
    };

    const setHeading = (level) => {
        editor.value.chain().focus().toggleHeading({ level }).run();
    };

    const setBulletList = () => {
        editor.value.chain().focus().toggleBulletList().run();
    };

    const setOrderedList = () => {
        editor.value.chain().focus().toggleOrderedList().run();
    };

    const setLink = () => {
        const url = prompt('Enter a URL');
        if (url) {
            editor.value.chain().focus().setLink({ href: url }).run();
        }
    };

    const setImage = () => {
        const imageUrl = prompt('Enter image URL');
        if (imageUrl) {
            editor.value.chain().focus().setImage({ src: imageUrl }).run();
        }
    };

    // Export content to PDF
    const exportToPDF = () => {
        const contentToExport = editor.value.getHTML(); // Get HTML from Tiptap editor
        html2pdf()
            .set({ margin: 5 })
            .from(contentToExport)
            .toPdf()
            .get('pdf')
            .then((pdf) => {
                pdf.save(`Facture_devis_${props.quoteId}.pdf`);
            });
    };

    // Fetch content when component mounts
    onMounted(() => {
        if (props.quoteId) {
            getQuoteHtml(props.quoteId);
        }
    });
   const exportToDOCX = async () => {
        const contentToExport =  await quoteService.getQuoteHtml(props.quoteId);

       asBlob(contentToExport).then(blobData => {
           saveAs(blobData, `Facture_devis_${props.quoteId}.docx`) // save as docx document
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

<style>
    .editor-toolbar {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }
    
    .ProseMirror{
        padding: 5px;
        border: 1px solid #ced4da;
        border-radius: 4px;  
    }
    
    .editor-toolbar button {
        padding: 5px 10px;
        font-size: 14px;
    }
    
    h3 {
        text-align: center;
        text-decoration: underline;
        margin-bottom: 20px;
    }

    .content {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .content > div {
        flex: 1;
        min-width: 300px;
    }

    .content p strong {
        text-decoration: underline;
    }

    .summary {
        margin-top: 20px;
    }
</style>
