<template>
    <template v-if="withDiv">
        <div :class="classDivFormControl">
            <slot></slot>
            <label>{{ titulo }} {{ required ? "(*):" : ":" }}</label>
            <select
                :class="['form-control rounded-0', addClass]"
                :value="modelValue"
                @input="emitValue($event.target.value)">
                <option v-if="withOptionDefault" value="">Seleccione</option>
                <option v-for="(option, index) in options" :value="option.id">
                    {{ option.value }}
                </option>
            </select>
            <small class="text-danger">
                {{ mostrarError }}
            </small>
        </div>
    </template>
    <template v-else>
        <select
            :class="['form-control rounded-0', addClass]"
            :value="modelValue"
            @input="emitValue($event.target.value)">
            <option v-if="withOptionDefault" value="">Seleccione</option>
            <option v-for="(option, index) in options" :value="option.id">
                {{ option.value }}
            </option>
        </select>
        <small v-if="withError" class="text-danger">
            {{ mostrarError }}
        </small>
    </template>
</template>

<script>
export default {
    name: "inputSelect",
    props: {
        modelValue: {
            type: [String, Number],
            default: ""
        },
        withOptionDefault:{
            type: Boolean,
            required: false,
            default: true
        },
        options: {
            type: Array,
            required: false,
            default: []
        },
        withDiv: {
            type: Boolean,
            required: false,
            default: true,
        },
        withError: {
            type: Boolean,
            required: false,
            default: true,
        },
        titulo: {
            type: String,
            required: false,
            default: ""
        },
        required: {
            type: Boolean,
            required: false,
            default: false,
        },
        addClass:{
            type: String,
            required: false,
            default: ""
        },
        placeholder: {
            type: String,
            required: false,
            default: "",
        },
        errors: {
            type: Array,
            required: false,
            default: [],
        },
        xl: {
            type: String,
            required: false,
            default: "4"
        },
        lg: {
            type: String,
            required: false,
            default: "6"
        },
        md: {
            type: String,
            required: false,
            default: "6"
        },
        sm: {
            type: String,
            required: false,
            default: "6"
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    computed: {
        mostrarError() {

            return this.errors.length > 0 ? this.errors[0] : "";

        },
        classDivFormControl() {

            return `form-group col-xl-${this.xl} col-lg-${this.lg} col-md-${this.md} col-sm-${this.sm}`;

        }
    },
    methods: {
        emitValue(value) {

            this.$emit('update:modelValue', value);

        }
    }
};
</script>
