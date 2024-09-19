<template>
    <h4 class="py-2 mb-4" v-if="list.length > 0">
        <span class="text-muted fw-light">
            <i class="fa fa-home fa-2xs"></i>
        </span>
        <template v-for="(value, index) in list" :key="index">
            <a href="javascript:void(0)" :class="[value?.active ? 'text-decoration-none' : 'text-muted', 'fw-bold mx-2']">/</a>
            <a href="javascript:void(0)" :class="[value?.active ? 'text-decoration-none' : 'text-muted', 'fw-bold']" v-text="value?.title"></a>
        </template>
    </h4>
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
