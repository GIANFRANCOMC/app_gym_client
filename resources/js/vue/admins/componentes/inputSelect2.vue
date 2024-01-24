<template>
    <template v-if="showDiv">
        <div :class="[divSizeClass, ...divClass]">
            <slot name="default"></slot>
            <label :class="[...titleClass]" v-text="title"></label>
            <label v-if="required" :class="[...requiredClass]" v-text="requiredLabel"></label>
            <div class="input-group">
                <slot name="inputGroupPrepend"></slot>
                <select
                    :id="id"
                    :class="[...addClass]"
                    :disabled="disabled"
                    @change="handleChange"
                    :multiple="multiple">
                    <option v-for="(option, index) in options" :value="option.code" v-text="option.label" :extras="option?.extras"></option>
                </select>
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
            <select
                :id="id"
                :class="[...addClass]"
                :disabled="disabled"
                @change="handleChange"
                :multiple="multiple">
                <option v-for="(option, index) in options" :value="option.code" v-text="option.label"></option>
            </select>
            <slot name="inputGroupAppend"></slot>
        </div>
        <div v-if="showTextBottom">
            <small v-if="textBottomType === 'first'" :class="[...textBottomClass]" v-text="textBottom"></small>
        </div>
    </template>
</template>

<script>
export default {
    name: "inputSelect2",
    emits: [],
    props: {
        id: {
            type: [String],
            required: true
        },
        // Options
        options: {
            type: Array,
            required: false,
            default: []
        },
        multiple: {
            type: Boolean,
            required: false,
            default: false
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
            default: ["select2", "form-select"]
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
    mounted: function() {

        let $this = $(`#${this.id}`);

        $this.wrap('<div class="position-relative w-100"></div>').select2({
            placeholder: 'Seleccione',
            dropdownParent: $this.parent()
        });

    },
    methods: {
        //
    }
};
</script>
