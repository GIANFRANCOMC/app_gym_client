<template>
    <template v-if="showDiv">
        <div :class="[divSizeClass]">
            <slot name="default"></slot>
            <label :class="[...titleClass]">{{ title }} {{ required ? requiredLabel+":" : ":" }}</label>
            <input
                type="text"
                :value="modelValue"
                @input="emitValue($event.target.value)"
                :class="['form-control', ...addClass]"
                :placeholder="placeholder"
                @keyup.enter="handleEnterKey"
                :disabled="disabled"/>
            <template v-if="showTextBottom">
                <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
            </template>
        </div>
    </template>
    <template v-else>
        <slot name="default"></slot>
        <input
            type="text"
            :value="modelValue"
            @input="emitValue($event.target.value)"
            :class="['form-control', ...addClass]"
            :placeholder="placeholder"
            @keyup.enter="handleEnterKey"
            :disabled="disabled"/>
        <template v-if="showTextBottom">
            <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
        </template>
    </template>
</template>

<script>
export default {
    name: "inputText",
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
            default: []
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
            default: "(*)",
        },
        addClass:{
            type: Array,
            required: false,
            default: []
        },
        placeholder: {
            type: String,
            required: false,
            default: "",
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
