<template>
    <ul class="timeline" v-if="resume.length > 0">
        <template v-for="(item, indexItem) in resume" :key="indexItem">
            <li :class="['mb-3', 'timeline-item']">
                <template v-if="['sale'].includes(item?.type)">
                    <span :class="['timeline-point', 'timeline-point-'+item?.class]"></span>
                    <div class="timeline-event shadow-sm">
                        <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <span class="d-flex justify-content-start align-items-start flex-wrap gap-2">
                                <span v-text="item?.icon"></span>
                                <span :class="['text-uppercase fw-bold', 'text-'+item?.class]" v-text=item?.name></span>
                                <span :class="['badge', 'fw-semibold', 'text-capitalize', { 'bg-label-success': ['active'].includes(item?.data?.status), 'bg-label-danger': ['inactive', 'canceled'].includes(item?.data?.status) }]" v-text="item?.data?.formatted_status"></span>
                            </span>
                            <small class="text-body-secondary mt-1" v-text="legibleFormatDate({dateString: item?.data?.created_at, type: 'datetime'})"></small>
                        </div>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Sucursal</span>
                            <span v-text="item?.data?.serie?.branch?.name" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Documento</span>
                            <span v-text="item?.data?.serie_sequential" class="fw-semibold ms-2"></span>
                            <span v-text="' ('+item.data?.serie?.document_type?.name+')'" class="ms-1"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Fecha de emisión</span>
                            <span v-text="legibleFormatDate({dateString: item?.data?.issue_date, type: 'date'})" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Total</span>
                            <span v-text="item?.data?.currency?.sign+' '+item?.data?.total" class="fw-semibold ms-2"></span>
                        </p>
                    </div>
                </template>
                <template v-else-if="['subscription'].includes(item?.type)">
                    <span :class="['timeline-point', 'timeline-point-'+item?.class]"></span>
                    <div class="timeline-event shadow-sm">
                        <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <span class="d-flex justify-content-start align-items-start flex-wrap gap-2">
                                <span v-text="item?.icon"></span>
                                <span :class="['text-uppercase fw-bold', 'text-'+item?.class]" v-text=item?.name></span>
                                <span :class="['badge', 'fw-semibold', 'text-capitalize', { 'bg-label-success': ['active'].includes(item?.data?.status), 'bg-label-danger': ['inactive', 'canceled'].includes(item?.data?.status) }]" v-text="item?.data?.formatted_status"></span>
                            </span>
                            <small class="text-body-secondary mt-1" v-text="legibleFormatDate({dateString: item?.data?.created_at, type: 'datetime'})"></small>
                        </div>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Sucursal</span>
                            <span v-text="item?.data?.branch?.name" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Fecha de inicio</span>
                            <span v-text="legibleFormatDate({dateString: item?.data?.start_date, type: 'datetime'})" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Fecha de finalización</span>
                            <span v-text="legibleFormatDate({dateString: item?.data?.end_date, type: 'datetime'})" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Duración</span>
                            <span v-text="item?.data?.formatted_duration" class="fw-semibold ms-2"></span>
                        </p>
                    </div>
                </template>
                <template v-else-if="['attendance'].includes(item?.type)">
                    <span :class="['timeline-point', 'timeline-point-'+item?.class]"></span>
                    <div class="timeline-event shadow-sm">
                        <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap mb-2">
                            <span class="d-flex justify-content-start align-items-start flex-wrap gap-2">
                                <span v-text="item?.icon"></span>
                                <span :class="['text-uppercase fw-bold', 'text-'+item?.class]" v-text=item?.name></span>
                                <span :class="['badge', 'fw-semibold', { 'bg-label-success': ['active'].includes(item?.data?.status), 'bg-label-primary': ['finalized'].includes(item?.data?.status), 'bg-label-danger': ['canceled'].includes(item?.data?.status) }]" v-text="item?.data?.formatted_status"></span>
                            </span>
                            <small class="text-body-secondary mt-1" v-text="legibleFormatDate({dateString: item?.data?.created_at, type: 'datetime'})"></small>
                        </div>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Sucursal</span>
                            <span v-text="item?.data?.branch?.name" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Ingreso</span>
                            <span v-text="legibleFormatDate({dateString: item?.data?.start_date, type: 'datetime'})" class="fw-semibold ms-2"></span>
                        </p>
                        <p class="d-flex flex-wrap gap-0 gap-md-0 mb-0">
                            <span class="colon-at-end">Salida</span>
                            <span v-text="isDefined({value: item?.data?.end_date}) ? legibleFormatDate({dateString: item?.data?.end_date, type: 'datetime'}) : 'Pendiente'" class="fw-semibold ms-2"></span>
                        </p>
                    </div>
                </template>
            </li>
        </template>
    </ul>
    <div v-else class="text-center">
        <WithoutData type="image"/>
    </div>
</template>

<script>
import * as Utils  from "../../Helpers/Utils.js";

export default {
    name: "Timeline",
    emits: [],
    props: {
        data: {
            type: Object,
            required: false,
            default: {} // customer, sales, subscriptions, attendances
        }
    },
    computed: {
        resume: function() {

            const sales = (this.data?.sales ?? []).map(e => ({
                type: "sale",
                name: "Venta",
                date: new Date(e?.created_at),
                data: e,
                icon: "💰",
                class: "primary"
            }));

            const subscriptions = (this.data?.subscriptions ?? []).map(e => ({
                type: "subscription",
                name: "Membresía",
                date: new Date(e?.created_at),
                data: e,
                icon: "📄",
                class: "success"
            }));

            const attendances = (this.data?.attendances ?? []).map(e => ({
                type: "attendance",
                name: "Asistencia",
                date: new Date(e?.created_at),
                data: e,
                icon: "⌚",
                class: "secondary"
            }));

            return [...sales, ...subscriptions, ...attendances].sort((a, b) => b.date - a.date);

        }
    },
    methods: {
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        legibleFormatDate({dateString = null, type = "datetime"}) {

            return Utils.legibleFormatDate({dateString, type});

        }
    }
};
</script>
