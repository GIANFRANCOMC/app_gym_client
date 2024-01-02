<template>
    <template v-if="withDiv">
        <div :class="classDivFormControl">
            <slot></slot>
            <label :class="classTitulo">{{ titulo }} {{ required ? "(*):" : ":" }}</label>
            <p :class="classContenido">
                {{ modelValue }}
            </p>
            <small class="text-danger">
                {{ mostrarError }}
            </small>
        </div>
    </template>
    <template v-else>
        <input
            type="text"
            :class="['form-control rounded-0', addClass]"
            :placeholder="placeholder"
            :value="modelValue"
            @input="emitValue($event.target.value)"/>
    </template>
</template>

<script>
export default {
    name: "inputLabel",
    props: {
        modelValue: {
            type: [String, Number],
            default: ""
        },
        withDiv: {
            type: Boolean,
            required: false,
            default: true,
        },
        titulo: {
            type: String,
            required: false,
            default: ""
        },
        classTitulo: {
            type: String,
            required: false,
            default: ""
        },
        classContenido: {
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
