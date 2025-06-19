<template>
    <ul class="timeline d-flex flex-wrap gap-2" v-if="resume.length > 0">
        <template v-for="(item, indexItem) in resume" :key="indexItem">
            <li :class="['timeline-item', 'ps-4', 'pe-0', 'w-100']">
                <span :class="['timeline-point', 'timeline-point-'+item?.class]"></span>
                <template v-if="['sale'].includes(item?.type)">
                    <div class="timeline-event shadow-sm">
                        <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap mb-2 mb-md-1">
                            <div class="d-flex justify-content-start align-items-start flex-wrap gap-3">
                                <div>
                                    <!-- <span v-text="item?.icon"></span> -->
                                    <span :class="['text-uppercase', 'fw-bold', 'ms-1', 'text-'+item?.class]" v-text="item?.name"></span>
                                </div>
                                <span :class="['badge', 'fw-semibold', 'py-1', { 'bg-label-success': ['active'].includes(item?.data?.status), 'bg-label-danger': ['inactive', 'canceled'].includes(item?.data?.status) }]" v-text="item?.data?.formatted_status"></span>
                            </div>
                            <small class="text-body-secondary" v-text="legibleFormatDate({dateString: item?.data?.created_at, type: 'datetime'})"></small>
                        </div>
                        <div class="d-flex justify-content-start align-items-end flex-wrap gap-1">
                            <div class="d-flex flex-wrap">
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Sucursal</span>
                                    <span v-text="item?.data?.serie?.branch?.name" class="fw-semibold ms-2"></span>
                                </div>
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Documento</span>
                                    <span v-text="item?.data?.serie_sequential" class="fw-semibold ms-2"></span>
                                    <i class="fa fa-info-circle ms-2 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" :title="item.data?.serie?.document_type?.name"></i>
                                </div>
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Fecha de emisión</span>
                                    <span v-text="legibleFormatDate({dateString: item?.data?.issue_date, type: 'date'})" class="fw-semibold ms-2"></span>
                                </div>
                            </div>
                            <div class="ms-auto" v-if="isDefined({value: item?.data?.total})">
                                <div class="d-block w-100 fs-4 fw-bold">
                                    <span v-text="item?.icon"></span>
                                    <span v-text="item?.data?.currency?.sign+' '+item?.data?.total" class="ms-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else-if="['subscription'].includes(item?.type)">
                    <div class="timeline-event shadow-sm">
                        <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap mb-2 mb-md-1">
                            <div class="d-flex justify-content-start align-items-start flex-wrap gap-3">
                                <div>
                                    <!-- <span v-text="item?.icon"></span> -->
                                    <span :class="['text-uppercase', 'fw-bold', 'ms-1', 'text-'+item?.class]" v-text="item?.name"></span>
                                </div>
                                <span :class="['badge', 'fw-semibold', 'py-1', { 'bg-label-success': ['active'].includes(item?.data?.status), 'bg-label-primary': ['inactive'].includes(item?.data?.status), 'bg-label-danger': ['canceled'].includes(item?.data?.status) }]" v-text="item?.data?.formatted_status"></span>
                            </div>
                            <small class="text-body-secondary" v-text="legibleFormatDate({dateString: item?.data?.created_at, type: 'datetime'})"></small>
                        </div>
                        <div class="d-flex justify-content-start align-items-end flex-wrap gap-1">
                            <div class="d-flex flex-wrap">
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Sucursal</span>
                                    <span v-text="item?.data?.branch?.name" class="fw-semibold ms-2"></span>
                                </div>
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Fecha de inicio</span>
                                    <span v-text="legibleFormatDate({dateString: item?.data?.start_date, type: 'datetime'})" class="fw-semibold ms-2"></span>
                                </div>
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Fecha de finalización</span>
                                    <span v-text="legibleFormatDate({dateString: item?.data?.end_date, type: 'datetime'})" class="fw-semibold ms-2"></span>
                                </div>
                            </div>
                            <div class="ms-auto" v-if="isDefined({value: item?.data?.formatted_duration})">
                                <div class="d-block w-100 fs-4 fw-bold">
                                    <span v-text="item?.icon"></span>
                                    <span v-text="item?.data?.formatted_duration" class="ms-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else-if="['attendance'].includes(item?.type)">
                    <div class="timeline-event shadow-sm">
                        <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap mb-2 mb-md-1">
                            <div class="d-flex justify-content-start align-items-start flex-wrap gap-3">
                                <div>
                                    <!-- <span v-text="item?.icon"></span> -->
                                    <span :class="['text-uppercase', 'fw-bold', 'ms-1', 'text-'+item?.class]" v-text="item?.name"></span>
                                </div>
                                <span :class="['badge', 'fw-semibold', 'py-1', { 'bg-label-success': ['active'].includes(item?.data?.status), 'bg-label-primary': ['finalized'].includes(item?.data?.status), 'bg-label-danger': ['canceled'].includes(item?.data?.status) }]" v-text="item?.data?.formatted_status"></span>
                            </div>
                            <small class="text-body-secondary" v-text="legibleFormatDate({dateString: item?.data?.created_at, type: 'datetime'})"></small>
                        </div>
                        <div class="d-flex justify-content-start align-items-end flex-wrap gap-1">
                            <div class="d-flex flex-wrap">
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Sucursal</span>
                                    <span v-text="item?.data?.branch?.name" class="fw-semibold ms-2"></span>
                                </div>
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Ingreso</span>
                                    <span v-text="legibleFormatDate({dateString: item?.data?.start_date, type: 'datetime'})" class="fw-semibold ms-2"></span>
                                </div>
                                <div class="d-block w-100">
                                    <span class="colon-at-end">Salida</span>
                                    <span v-text="isDefined({value: item?.data?.end_date}) ? legibleFormatDate({dateString: item?.data?.end_date, type: 'datetime'}) : 'Pendiente'" class="fw-semibold ms-2"></span>
                                </div>
                            </div>
                            <div class="ms-auto" v-if="isDefined({value: item?.data?.end_date})">
                                <div class="d-block w-100 fs-4 fw-bold">
                                    <span v-text="item?.icon"></span>
                                    <span v-text="item?.data?.worked_hours" class="ms-2"></span>
                                    <span v-text="item?.data?.worked_hours > 1 ? 'horas trabajadas' : 'hora trabajada'" class="ms-2"></span>
                                </div>
                            </div>
                        </div>
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

            const priority = {
                sale: 3,
                subscription: 2,
                attendance: 1
            };

            return [...sales, ...subscriptions, ...attendances].sort((a, b) => {

                const dateDiff = b.date - a.date;

                if(dateDiff !== 0) return dateDiff;

                return priority[a.type] - priority[b.type];

            });

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
