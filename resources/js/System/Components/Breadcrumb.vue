<template>
    <div class="d-flex justify-content-start flex-wrap mb-4" v-if="list.length > 0">
        <span class="user-select-none fs-5 text-muted">
            <i class="fa fa-home fa-2xs"></i>
        </span>
        <template v-for="(value, index) in list" :key="index">
            <span :class="['user-select-none fs-5', value?.active ? 'text-primary' : 'text-muted', 'fw-bold mx-1']">/</span>
            <span :class="['user-select-none fs-5', value?.active ? 'text-primary' : 'text-muted', 'fw-bold']" v-text="value?.title"></span>
        </template>
    </div>
</template>

<script>
export default {
    name: "Breadcrumb",
    emits: [],
    props: {
        list: {
            type: Array,
            required: false,
            default: []
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
