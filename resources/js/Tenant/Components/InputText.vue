<template>
    <template v-if="showDiv">
        <div :class="[divSizeClass, ...divClass]">
            <slot name="default"></slot>
            <label :class="[...titleClass]" v-text="title"></label>
            <label v-if="required" :class="[...requiredClass]" v-text="requiredLabel"></label>
            <div class="input-group">
                <slot name="inputGroupPrepend"></slot>
                <input
                    type="text"
                    :value="modelValue"
                    @input="emitValue($event.target.value)"
                    :class="[...addClass]"
                    :placeholder="placeholder"
                    :disabled="disabled"
                    :maxlength="maxlength"
                    @keyup.enter="handleEnterKey"/>
                <slot name="inputGroupAppend"></slot>
            </div>
            <div v-if="showTextBottom">
                <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
            </div>
        </div>
    </template>
    <template v-else>
        <slot name="default"></slot>
        <div class="input-group">
            <slot name="inputGroupPrepend"></slot>
            <input
                type="text"
                :value="modelValue"
                @input="emitValue($event.target.value)"
                :class="[...addClass]"
                :placeholder="placeholder"
                :disabled="disabled"
                :maxlength="maxlength"
                @keyup.enter="handleEnterKey"/>
            <slot name="inputGroupAppend"></slot>
        </div>
        <div v-if="showTextBottom">
            <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
        </div>
    </template>
</template>

<script>
import { requestRoute, generalConfig } from "../Helpers/Constants.js";

export default {
    name: "InputText",
    emits: ["enterKeyPressed", "update:modelValue"],
    props: {
        modelValue: {
            type: [String, Number],
            default: ""
        },
        // Title
        showDiv: {
            type: Boolean,
            required: false,
            default: false
        },
        divClass: {
            type: Array,
            required: false,
            default: []
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
            default: false
        },
        requiredLabel: {
            type: String,
            required: false,
            default: "*"
        },
        requiredClass: {
            type: Array,
            required: false,
            default: ["text-danger", "ms-1"]
        },
        addClass:{
            type: Array,
            required: false,
            default: ["form-control"]
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
        maxlength: {
            type: [String, Number],
            required: false,
            default: generalConfig.forms.inputs.maxlength
        },
        // Text Bottom
        showTextBottom: {
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
            default: ["text-danger"]
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
        handleEnterKey() {

            this.$emit("enterKeyPressed");

        }
    }
};
</script>
