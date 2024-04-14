<template>
    <input :id="id" :type="showPassword ? 'text' : 'password'" class="form-control" :placeholder="placeholder"
        @input="updateValue($event.target.value)" :value="inputValue" />
    <span class="eye-password cursor-pointer" @click="showPassword = !showPassword" data-bs-toggle="tooltip"
        data-bs-placement="right" :title="showPassword ? 'Hide Password' : 'Show Password'">
        <i :class="['far', showPassword ? 'fa-eye' : 'fa-eye-slash']"></i>
    </span>
</template>
<script>
import "/src/assets/sass/font-icons/fontawesome/css/regular.css";
import "/src/assets/sass/font-icons/fontawesome/css/fontawesome.css";

export default {
    props: {
        placeholder: {
            type: String,
            default: 'Password'
        },
        id: {
            type: String,
            default: 'password'
        },
        value: {
            type: [String, null],
        },
        modelValue: {
            type: [String, null],
        }
    },
    data() {
        return {
            inputValue: this.value,
            showPassword: false
        };
    },
    methods: {
        updateValue(newValue) {
            this.inputValue = newValue;
            this.$emit('update:modelValue', newValue);
        }
    },
    watch: {
        value(newValue) {
            this.inputValue = newValue;
        }
    }
};
</script>
<style scoped>
.eye-password {
    position: absolute;
    top: 23px;
    right: 15px;
    cursor: pointer;
    font-size: large;
}
</style>
