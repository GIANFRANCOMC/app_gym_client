<template>
    <template v-if="withDiv">
        <div :class="classDivFormControl">
            <slot></slot>
            <label>{{ titulo }} {{ required ? "(*):" : ":" }}</label>
            <input
                type="file"
                :id="id"
                :class="['form-control rounded-0', addClass]"
                :placeholder="placeholder"/>
            <small class="text-danger">
                {{ mostrarError }}
            </small>
        </div>
    </template>
    <template v-else>
        <input
            type="file"
            :id="id"
            :class="['form-control rounded-0', addClass]"
            :placeholder="placeholder"/>
        <small v-if="withError" class="text-danger">
            {{ mostrarError }}
        </small>
    </template>
</template>

<script>
export default {
    name: "inputSingleFile",
    props: {
        id: {
            type: String,
            required: false,
            default: 'abc-123-def-456'
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
        emitValue(target) {

            const file = target.files;
            //console.log(target, file);
            this.$emit('update:modelValue', file[0]); // Funciona pero, da error Bug, VueJs

        }
    }
};
</script>
