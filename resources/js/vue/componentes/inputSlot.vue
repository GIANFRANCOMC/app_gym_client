<template>
    <template v-if="withDiv">
        <div :class="classDivFormControl">
            <slot name="preInput"></slot>
            <label>{{ titulo }} {{ required ? "(*):" : ":" }}</label>
            <slot name="input"></slot>
            <small class="text-danger">
                {{ mostrarError }}
            </small>
        </div>
    </template>
    <template v-else>

    </template>
</template>

<script>
export default {
    name: "inputSlot",
    props: {
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
    }
};
</script>
