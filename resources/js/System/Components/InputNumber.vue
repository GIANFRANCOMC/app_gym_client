<template>
    <template v-if="hasDiv">
        <div :class="[...divClass, divSizeClass]">
            <slot name="default"></slot>
            <label v-if="!!title" v-text="title" :class="[...titleClass]"></label>
            <label v-if="isRequired" v-text="requiredLabel" :class="[...requiredClass]"></label>
            <div class="input-group">
                <slot name="inputGroupPrepend"></slot>
                <input
                    type="text"
                    :value="isEditing ? modelValue : formattedValue"
                    @focus="handleFocus"
                    @blur="handleBlur"
                    @input="updateValue($event.target.value)"
                    @keydown="handleKeyDown"
                    :class="[...inputClass]"
                    :placeholder="placeholder"
                    :disabled="disabled"
                    @keyup.enter="handleEnterKey"/>
                <slot name="inputGroupAppend"></slot>
            </div>
            <div v-if="hasTextBottom">
                <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
            </div>
        </div>
    </template>
    <template v-else>
        <slot name="default"></slot>
        <label v-if="!!title" v-text="title" :class="[...titleClass]"></label>
        <label v-if="isRequired" v-text="requiredLabel" :class="[...requiredClass]"></label>
        <div class="input-group">
            <slot name="inputGroupPrepend"></slot>
            <input
                type="text"
                :value="isEditing ? modelValue : formattedValue"
                @focus="handleFocus"
                @blur="handleBlur"
                @input="updateValue($event.target.value)"
                @keydown="handleKeyDown"
                :class="[...inputClass]"
                :placeholder="placeholder"
                :disabled="disabled"
                @keyup.enter="handleEnterKey"/>
            <slot name="inputGroupAppend"></slot>
        </div>
        <div v-if="hasTextBottom">
            <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
        </div>
    </template>
</template>

<script>
import { generalConfig } from "../Helpers/Constants.js";
import { separatorNumber } from "../Helpers/Utils.js";

export default {
    name: "InputNumber",
    emits: ["enterKeyPressed", "update:modelValue", "input", "change"],
    props: {
        modelValue: {
            type: [String, Number],
            default: ""
        },
        // Div - Show
        hasDiv: {
            type: Boolean,
            required: false,
            default: false
        },
        divClass: {
            type: Array,
            required: false,
            default: []
        },
        // Div - Title
        title: {
            type: String,
            required: false,
            default: ""
        },
        titleClass: {
            type: Array,
            required: false,
            default: ["form-label", "colon-at-end"]
        },
        // Input - Required
        isRequired: {
            type: Boolean,
            required: false,
            default: false
        },
        requiredLabel: {
            type: String,
            required: false,
            default: generalConfig.forms.inputs.required
        },
        requiredClass: {
            type: Array,
            required: false,
            default: ["text-danger", "ms-1", "fw-bold"]
        },
        // Input - Props
        inputClass:{
            type: Array,
            required: false,
            default: ["form-control"]
        },
        minValue: {
            type: [String, Number],
            required: false,
            default: generalConfig.forms.inputs.minValue
        },
        maxValue: {
            type: [String, Number],
            required: false,
            default: generalConfig.forms.inputs.maxValue
        },
        decimals: {
            type: [String, Number],
            required: false,
            default: generalConfig.forms.inputs.round
        },
        placeholder: {
            type: String,
            required: false,
            default: ""
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false
        },
        // Text Bottom
        hasTextBottom: {
            type: Boolean,
            required: false,
            default: false
        },
        textBottomType: {
            type: String,
            required: false,
            default: "first"
        },
        textBottomClass: {
            type: Array,
            required: false,
            default: [generalConfig.forms.errors.styles.default]
        },
        textBottomInfo: {
            type: Array,
            required: false,
            default: []
        },
        // Sizes
        xl: {
            type: [String, Number],
            required: false,
            default: "12"
        },
        lg: {
            type: [String, Number],
            required: false,
            default: "12"
        },
        md: {
            type: [String, Number],
            required: false,
            default: "12"
        },
        sm: {
            type: [String, Number],
            required: false,
            default: "12"
        }
    },
    data() {
        return {
            isEditing: false
        };
    },
    computed: {
        formattedValue() {

            return separatorNumber(this.modelValue);

        },
        textBottom() {

            try {

                return this.textBottomInfo.length > 0 ? this.textBottomInfo[0] : "";

            }catch(e) {

                return e;

            }

        },
        divSizeClass() {

            return `form-group col-xl-${this.xl} col-lg-${this.lg} col-md-${this.md} col-sm-${this.sm}`;

        }
    },
    methods: {
        updateValue(value) {

            let valueString = String(value).trim();

            if(valueString === "") {

                this.emitValue({result: valueString});

            }else {

                const hasFormattedNumber = /^\d+(\.\d+)?$/.test(valueString); // Case: 1  2  3.1  5.67  0.329
                const hasDecimalInitNumber = this.decimals > 0 && /^\d+\.$/.test(valueString); // Case: With decimals (Input: 12. 3134. 23461.)

                if(hasFormattedNumber || hasDecimalInitNumber) {

                    let numericValue = Number(value);

                    if(isNaN(numericValue) || numericValue < this.minValue) {

                        this.emitValue({result: this.minValue});

                    }else if(numericValue > this.maxValue) {

                        this.emitValue({result: this.maxValue});

                    }else {

                        const regexDecimals = this.decimals > 0 ? new RegExp(`^\\d+(\\.\\d{1,${this.decimals}})?$`) : /^-?\d+$/;
                        const hasFormattedDecimal = regexDecimals.test(valueString);

                        if(this.decimals > 0) {

                            hasFormattedDecimal || hasDecimalInitNumber ? this.emitValue({reset: false, result: numericValue}) : this.emitValue({result: Number(numericValue.toFixed(this.decimals))});

                        }else {

                            hasFormattedDecimal ? this.emitValue({reset: false, result: numericValue}) : this.emitValue({result: Number(numericValue.toFixed(this.decimals))});

                        }

                    }

                }else {

                    this.emitValue({result: this.minValue});

                }

            }

        },
        emitValue({reset = true, result}) {

            if(reset) {

                this.$emit("update:modelValue", null);

                this.$nextTick(() => {
                    this.$emit("update:modelValue", result);
                    this.$emit("input", result);
                    this.$emit("change", result);
                });

            }else {

                this.$emit("update:modelValue", result);
                this.$emit("input", result);
                this.$emit("change", result);

            }

        },
        handleFocus() {

            this.isEditing = true;

        },
        handleBlur() {

            this.isEditing = false;

        },
        handleKeyDown(event) {

            let allowedKeys = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "ArrowLeft", "ArrowRight", "Backspace", "Tab"];

            if(this.decimals > 0) {

                allowedKeys.push(".");

            }

            if(!allowedKeys.includes(event.key)) {

                event.preventDefault();

            }

        },
        handleEnterKey() {

            this.$emit("enterKeyPressed");

        }
    }
};
</script>

<style scoped>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
