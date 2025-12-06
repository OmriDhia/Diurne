<template>
    <div class="validation-field">
        <!-- Mode simple (pour d-compositions.vue) -->
        <div v-if="isSimpleMode" class="mb-2">
            <div class="d-flex gap-3">
                <label 
                    v-for="option in options" 
                    :key="option.label"
                    class="form-check"
                >
                    <input 
                        type="radio" 
                        :name="`validation-${fieldName}`"
                        :value="option.value !== undefined ? option.value : option.label === 'Validée'"
                        :checked="modelValue === (option.value !== undefined ? option.value : option.label === 'Validée')"
                        @change="updateSimpleValue(option.value !== undefined ? option.value : option.label === 'Validée')"
                        class="form-check-input"
                    />
                    <span class="form-check-label">{{ option.label }}</span>
                </label>
            </div>
        </div>

        <!-- Mode avancé (pour checkingList.vue) -->
        <div v-else>
            <!-- Pertinence -->
            <div class="mb-2">
                <label class="form-label fw-bold">Pertinent:</label>
                <div class="d-flex gap-3">
                    <label class="form-check">
                        <input 
                            type="radio" 
                            :name="`relevant-${fieldName}`"
                            :value="true" 
                            :checked="modelValue.relevant === true"
                            @change="updateRelevant(true)"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Oui</span>
                    </label>
                    <label class="form-check">
                        <input
                            type="radio" 
                            :name="`relevant-${fieldName}`"
                            :value="false" 
                            :checked="modelValue.relevant === false"
                            @change="updateRelevant(false)"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Non</span>
                    </label>
                </div>
            </div>

            <!-- Validation (seulement si pertinent) -->
            <div v-if="isRelevant" class="mb-2">
                <label class="form-label fw-bold">Validé:</label>
                <div class="d-flex gap-3">
                    <label class="form-check">
                        <input 
                            type="radio" 
                            :name="`validation-${fieldName}`"
                            :value="true" 
                            :checked="isValidation"
                            @change="updateValidation(true)"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Oui</span>
                    </label>
                    <label class="form-check">
                        <input
                            type="radio" 
                            :name="`validation-${fieldName}`"
                            :value="false" 
                            :checked="!isValidation && isRelevant"
                            @change="updateValidation(false)"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Non</span>
                    </label>
                </div>
            </div>

            <!-- Vu (seulement si pertinent et non validé) -->
            <div v-if="isRelevant && !isValidation" class="mb-2">
                <label class="form-label fw-bold">Vu:</label>
                <div class="d-flex gap-3">
                    <label class="form-check">
                        <input 
                            type="radio" 
                            :name="`seen-${fieldName}`"
                            :value="true" 
                            :checked="isSeen"
                            @change="updateSeen(true)"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Oui</span>
                    </label>
                    <label class="form-check">
                        <input
                            type="radio" 
                            :name="`seen-${fieldName}`"
                            :value="false" 
                            :checked="!isSeen && isRelevant && !isValidation"
                            @change="updateSeen(false)"
                            class="form-check-input"
                        />
                        <span class="form-check-label">Non</span>
                    </label>
                </div>
            </div>

            <!-- Commentaire (seulement si pertinent, non validé et vu) -->
            <div v-if="isRelevant && !isValidation && isSeen" class="mb-2">
                <label class="form-label fw-bold">Commentaire (obligatoire):</label>
                <textarea 
                    v-model="modelValue.comment"
                    class="form-control" 
                    rows="3"
                    :class="{ 'is-invalid': !modelValue.comment && showValidation }"
                    @input="updateComment"
                    placeholder="Veuillez saisir un commentaire..."
                ></textarea>
                <div v-if="!modelValue.comment && showValidation" class="invalid-feedback">
                    Le commentaire est obligatoire quand le champ est vu.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: [Object, Boolean, String, Number],
        required: true,
        default: () => ({
            relevant: null,
            validation: null,
            seen: null,
            comment: ''
        })
    },
    fieldName: {
        type: String,
        required: true
    },
    showValidation: {
        type: Boolean,
        default: false
    },
    options: {
        type: Array,
        default: null
    }
});

const emit = defineEmits(['update:modelValue']);

// Détecter le mode d'utilisation
const isSimpleMode = computed(() => {
    return props.options !== null && props.options !== undefined;
});

// Computed properties pour gérer les valeurs null de manière sûre
const isRelevant = computed(() => {
    return props.modelValue && typeof props.modelValue === 'object' && props.modelValue.relevant === true;
});

const isValidation = computed(() => {
    return props.modelValue && typeof props.modelValue === 'object' && props.modelValue.validation === true;
});

const isSeen = computed(() => {
    return props.modelValue && typeof props.modelValue === 'object' && props.modelValue.seen === true;
});

// Mode simple (pour d-compositions.vue)
const updateSimpleValue = (value) => {
    emit('update:modelValue', value);
};

// Mode avancé (pour checkingList.vue)
const updateRelevant = (value) => {
    const newValue = {
        ...props.modelValue,
        relevant: value,
        // Reset validation and seen if not relevant
        validation: value ? props.modelValue.validation : null,
        seen: value ? props.modelValue.seen : null,
        comment: value ? props.modelValue.comment : ''
    };
    emit('update:modelValue', newValue);
};

const updateValidation = (value) => {
    const newValue = {
        ...props.modelValue,
        validation: value,
        // Reset seen if validated
        seen: value ? null : props.modelValue.seen,
        comment: value ? '' : props.modelValue.comment
    };
    emit('update:modelValue', newValue);
};

const updateSeen = (value) => {
    const newValue = {
        ...props.modelValue,
        seen: value,
        comment: value ? props.modelValue.comment : ''
    };
    emit('update:modelValue', newValue);
};

const updateComment = () => {
    emit('update:modelValue', { ...props.modelValue });
};
</script>

<style scoped>
.validation-field {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
}

.form-check {
    margin-bottom: 0;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}
</style>
