<template>
    <template v-if="['image'].includes(type)">
        <img :src="image" class="fluid-image" width="40%"/>
        <h5 class="fw-bold" v-text="text"></h5>
    </template>
    <template v-else-if="['text'].includes(type)">
        <span v-text="text"></span>
    </template>
</template>

<script>
import * as Constants  from "../Helpers/Constants.js";

export default {
    name: "WithoutData",
    emits: [],
    props: {
        type: {
            type: String,
            required: false,
            default: "image"
        }
    },
    computed: {
        text() {

            return Constants.generalConfig.messages.withoutResults;

        },
        image() {

            return `${Constants.requestRoute}/System/assets/img/utils/without_data.svg`;

        }

    },
    methods: {
        //
    }
};
</script>
