<template>
    <template v-if="withDiv">
        <div class="form-group col-xl-4 col-lg-6 col-md-6 col-sm-6">
            <slot></slot>
            <label>{{ titulo }} {{ required ? "(*):" : ":" }}</label>
            <input
                type="date"
                :class="['form-control rounded-0', addClass]"
                :placeholder="placeholder"
                :value="modelValue"
                @input="emitValue($event.target.value)"
                :disabled="disabled"/>
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
        <small v-if="withError" class="text-danger">
            {{ mostrarError }}
        </small>
    </template>
</template>

<script>
export default {
    name: "inputDate",
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
