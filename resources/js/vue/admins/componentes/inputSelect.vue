<template>
    <template v-if="showDiv">
        <div :class="[divSizeClass]">
            <slot name="default"></slot>
            <label :class="[...titleClass]" v-text="title"></label>
            <label v-if="required" :class="[...requiredClass]" v-text="requiredLabel"></label>
            <select
                :value="modelValue"
                @input="emitValue($event.target.value)"
                :class="[...addClass]"
                :disabled="disabled"
                @change="handleChange">
                <option v-if="withDefaultOption" :value="defaultOptionValue" v-text="defaultOptionLabel"></option>
                <option v-for="(option, index) in options" :value="option.code" v-text="option.label"></option>
            </select>
            <template v-if="showTextBottom">
                <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
            </template>
        </div>
    </template>
    <template v-else>
        <slot name="default"></slot>
        <select
            :value="modelValue"
            @input="emitValue($event.target.value)"
            :class="[...addClass]"
            :disabled="disabled"
            @change="handleChange">
            <option v-if="withDefaultOption" :value="defaultOptionValue" v-text="defaultOptionLabel"></option>
            <option v-for="(option, index) in options" :value="option.code" v-text="option.label"></option>
        </select>
        <template v-if="showTextBottom">
            <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
        </template>
    </template>
</template>

<script>
export default {
    name: "inputSelect",
    emits: ["changeEvent", "update:modelValue"],
    props: {
        modelValue: {
            type: [String, Number],
            default: ""
        },
        // Options
        withDefaultOption:{
            type: Boolean,
            required: false,
            default: true
        },
        defaultOptionLabel: {
            type: String,
            required: false,
            default: "Seleccione"
        },
        defaultOptionValue: {
            type: String,
            required: false,
            default: ""
        },
        options: {
            type: Array,
            required: false,
            default: []
        },
        // Title
        showDiv: {
            type: Boolean,
            required: false,
            default: false,
        },
        title: {
            type: String,
            required: false,
            default: ""
        },
        titleClass: {
            type: Array,
            required: false,
            default: ["form-label"]
        },
        // Aspect
        required: {
            type: Boolean,
            required: false,
            default: false,
        },
        requiredLabel: {
            type: String,
            required: false,
            default: "*",
        },
        requiredClass: {
            type: Array,
            required: false,
            default: ["text-danger", "ms-1"]
        },
        addClass:{
            type: Array,
            required: false,
            default: ["form-select"]
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false
        },
        // Text Bottom
        showTextBottom: {
            type: Boolean,
            required: false,
            default: false,
        },
        textBottomType: {
            type: String,
            required: false,
            default: "first",
        },
        textBottomClass: {
            type: Array,
            required: false,
            default: ["text-danger"]
        },
        textBottomInfo: {
            type: Array,
            required: false,
            default: [],
        },
        // Sizes
        xl: {
            type: String,
            required: false,
            default: "12"
        },
        lg: {
            type: String,
            required: false,
            default: "12"
        },
        md: {
            type: String,
            required: false,
            default: "12"
        },
        sm: {
            type: String,
            required: false,
            default: "12"
        }
    },
    computed: {
        textBottom() {

            return this.textBottomInfo.length > 0 ? this.textBottomInfo[0] : "";

        },
        divSizeClass() {

            return `form-group col-xl-${this.xl} col-lg-${this.lg} col-md-${this.md} col-sm-${this.sm}`;

        }
    },
    methods: {
        emitValue(value) {

            this.$emit("update:modelValue", value);

        },
        handleChange() {

            this.$emit("changeEvent");

        }
    }
};
</script>
