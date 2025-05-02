<template>
    <template v-if="['default'].includes(type)">
        <i class="fas fa-spinner fa-spin fa-3x my-3"></i>
    </template>
</template>

<script>
export default {
    name: "Loader",
    emits: [],
    props: {
        type: {
            type: String,
            required: false,
            default: "default"
        }
    },
    computed: {
        //
    },
    methods: {
        //
    }
};
</script>
