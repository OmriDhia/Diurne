<template>
    <div class="language-container">
        <template v-if="processedLanguages.length">
            <div v-for="lang in processedLanguages" :key="lang.language_id" class="language-item">
                <label class="language-label">
                    {{ getLanguageName(lang) }} ({{ lang.is_code.toUpperCase() }})
                </label>
                <input v-model="descriptions[lang.language_id]" class="language-input"
                    :placeholder="`Enter ${lang.is_code.toUpperCase()} description`" />
            </div>
        </template>
        <div v-else class="language-warning">
            Aucune langue disponible - Veuillez configurer les langues dans l'administration
        </div>
    </div>
</template>

<script>

export default {
    props: {
        value: { // descriptions existantes
            type: Array,
            default: () => []
        },
        languages: {
            type: Array,
            default: () => []
        },
        valueKey: {
            type: String,
            default: 'label'
        },
        languageKey: {
            type: String,
            default: 'language_id'
        }
    },
    data() {
        return {
            descriptions: {}
        };
    },
    mounted() {
        console.log('[DEBUG] Child component mounted');
        console.log('[DEBUG] Received languages prop:', this.languages);
        console.log('[DEBUG] Received value prop:', this.value);
        console.log('[DEBUG] Computed processedLanguages:', this.processedLanguages);
    },
    processedLanguages() {
        console.log('Raw languages input:', this.languages);
        if (!this.languages || !Array.isArray(this.languages)) {
            console.warn('Invalid languages prop received');
            return [];
        }

        return this.languages
            .filter(lang => {
                const isValid = lang.language_id && lang.is_code;
                if (!isValid) {
                    console.warn('Invalid language entry:', lang);
                }
                return isValid;
            })
            .map(lang => ({
                ...lang,
                name: lang.name || `Language_${lang.language_id}`
            }));
    },
    watch: {
        value: {
            immediate: true,
            handler(newVal) {
                // Initialise les descriptions
                this.descriptions = {};
                newVal.forEach(desc => {
                    this.descriptions[desc[this.languageKey]] = desc[this.valueKey];
                });

                // Assure que toutes les langues ont une entrée
                this.processedLanguages.forEach(lang => {
                    if (this.descriptions[lang.language_id] === undefined) {
                        this.descriptions[lang.language_id] = '';
                    }
                });
            }
        }
    },
    methods: {
        getLanguageName(lang) {
            return lang.name || `Langue ${lang.language_id}`;
        },
        emitUpdate() {
            const result = Object.keys(this.descriptions)
                .map(langId => ({
                    [this.languageKey]: parseInt(langId),
                    [this.valueKey]: this.descriptions[langId]
                }));
            this.$emit('input', result);
        }
    }
};
</script>

<style scoped>
.language-container {
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    background: #f9f9f9;
}

.language-item {
    margin-bottom: 12px;
}

.language-label {
    display: block;
    margin-bottom: 4px;
    font-weight: 500;
}

.language-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.language-warning {
    color: #dc3545;
    padding: 8px;
    background: #fff3f3;
    border-radius: 4px;
}
</style>