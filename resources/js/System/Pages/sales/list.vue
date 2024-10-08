<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Listado' }]"/>

    <!-- Records -->
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <InputText
                v-model="lists.entity.filters.general"
                @enterKeyPressed="listEntity({})"
                hasDiv
                title="Buscar"
                :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
                placeholder="Ingrese la búsqueda">
                <template v-slot:inputGroupAppend>
                    <button class="btn btn-primary waves-effect" type="button" @click="listEntity({})" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Nombre, Descripción.">
                        <i class="fa fa-search"></i>
                    </button>
                </template>
            </InputText>
        </div>
        <!-- <div class="align-self-end">
            <button class="btn btn-primary waves-effect ms-3" @click="modalCreateUpdateEntity({})">
                <i class="fa fa-plus"></i>
                <span class="ms-1">Agregar</span>
            </button>
        </div> -->
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light align-middle">
                <tr class="text-center">
                    <th class="fw-bold col-1">SECUENCIA</th>
                    <th class="fw-bold col-1">CLIENTE</th>
                    <th class="fw-bold col-1">FECHA DE VENTA</th>
                    <th class="fw-bold col-1">TOTAL</th>
                    <th class="fw-bold col-1">ESTADO</th>
                    <th class="fw-bold col-1">ACCIONES</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0 bg-white">
                <template v-if="lists.entity.extras.loading">
                    <tr class="text-center">
                        <td colspan="99">
                            <i class="fas fa-spinner fa-spin fa-3x my-3"></i>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <template v-if="lists.entity.records.total > 0">
                        <tr v-for="record in lists.entity.records.data" :key="record.id" class="text-center">
                            <td class="text-start">
                                <span v-text="record.serie?.code+record.serie?.number+'-'+record.sequential"></span><br/>
                                <small v-text="record.serie?.documentType?.name"></small>
                            </td>
                            <td class="text-start">
                                <span v-text="record.holder?.name"></span><br/>
                                <small v-text="record.holder?.document_number"></small>
                            </td>
                            <td v-text="record.formatted_sale_date"></td>
                            <td v-text="record.formatted_total"></td>
                            <td>
                                <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm rounded-pill btn-success waves-effect m-1" @click="modalPrintEntity({record})" data-bs-toggle="tooltip" data-bs-placement="top" title="Imprimir">
                                    <i class="fa fa-print"></i>
                                </button>
                                <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect m-1" @click="modalCreateUpdateEntity({record})" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td class="text-center" colspan="99" v-text="config.messages.withoutResults"></td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center" v-if="!lists.entity.extras.loading">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
    </div>

    <PrintSale modalId="adasdasd" :bool="true" :title="forms.entity.createUpdate.extras.modals.finished.titles.header" :data="forms.entity.createUpdate.extras.modals.finished.data"/>
</template>

<script>
import * as Alerts    from "../../Helpers/Alerts.js";
import * as Constants from "../../Helpers/Constants.js";
import * as Requests  from "../../Helpers/Requests.js";
import * as Utils     from "../../Helpers/Utils.js";

import Breadcrumb   from "../../Components/Breadcrumb.vue";
import InputDate    from "../../Components/InputDate.vue";
import InputNumber  from "../../Components/InputNumber.vue";
import InputSelect  from "../../Components/InputSelect.vue";
// import InputSelect2 from "../../Components/InputSelect2.vue";
import InputText    from "../../Components/InputText.vue";
import Paginator    from "../../Components/Paginator.vue";

import PrintSale    from "../../Components/Sales/PrintSale.vue";

export default {
    components: {
        Breadcrumb,
        InputDate,
        InputNumber,
        InputSelect,
        // InputSelect2,
        InputText,
        Paginator,
        PrintSale
    },
    mounted: async function() {

        Utils.openNavbarItem(this.config.entity.page.menu.id, {addClass: "open"});
        Utils.openNavbarItem("menu-item-list-sales", {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            await this.listEntity({});

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "sales", type: "list"})
                    },
                    filters: {
                        general: ""
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                entity: {
                    createUpdate: {
                        extras: {
                            modals: {
                                default: {
                                    id: "createUpdateEntityModal",
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar"
                                    }
                                },
                                finished: {
                                    id: "finishedModal",
                                    titles: {
                                        header: "Comprobante",
                                        bool: false
                                    },
                                    data: {
                                        id: ""
                                    }
                                }
                            }
                        },
                        data: {
                            id: null,
                            name: "",
                            description: "",
                            price: "",
                            status: ""
                        },
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "sales"}),
                    page: {
                        title: "Ventas",
                        active: true,
                        menu: {
                            id: "menu-item-sales"
                        }
                    }
                }
            }
        };
    },
    methods: {
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, showAlert: true});

            this.options.sales = initParams.data?.config?.sales;

            return initParams?.bool && initParams?.data?.bool;

        },
        async initOthers({}) {

            return true;

        },
        async listEntity({url = null}) {

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: this.lists.entity.filters}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        modalPrintEntity({record = null}) {

            this.forms.entity.createUpdate.extras.modals.finished.data = record;

            $("#adasdasd").modal("show");

        },
        modalCreateUpdateEntity({record = null}) {

            window.location.href = this.config.entity.routes.create;

        },
        async createUpdateEntity() {

            //

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    //
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["modalCreateUpdateEntity", "createUpdateEntity"].includes(functionName)) {

                //

            }

        },
        validateForm({functionName, form = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

                //

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        }
    }
};
</script>
