<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Crear' }]"/>

    <div class="row invoice-add">
        <!-- Invoice Add-->
        <div class="col-lg-9 col-12 mb-lg-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <InputSelect
                            v-model="forms.entity.createUpdate.data.customer"
                            :options="options?.customers?.records.map(e=>({code: e.id, label: e.name}))"
                            hasDiv
                            title="Cliente"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.customer"
                            xl="8"
                            lg="8"
                            md="12"
                            sm="12"/>
                        <InputDate
                            v-model="forms.entity.createUpdate.data.sale_date"
                            hasDiv
                            title="Fecha de venta"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.sale_date"
                            xl="4"
                            lg="4"
                            md="12"
                            sm="12"/>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="fw-bold col-1">DESCRIPCIÓN</th>
                                    <th class="fw-bold col-1">CANTIDAD</th>
                                    <th class="fw-bold col-1">PRECIO UNITARIO</th>
                                    <th class="fw-bold col-1">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 bg-white">
                                <template v-if="(forms.entity.createUpdate.data.details).length > 0">
                                    <tr v-for="record in forms.entity.createUpdate.data.details" :key="record.id" class="text-center">
                                        <td class="fw-bold" v-text="record.name"></td>
                                        <td v-text="record.quantity"></td>
                                        <td v-text="record.price"></td>
                                        <td v-text="record.total"></td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="text-center" colspan="99" v-text="config.messages.withoutResults"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-12 invoice-actions">
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary w-100 my-1 waves-effect" @click="modalAddDetail({})">
                        <i class="fa fa-plus"></i>
                        <span class="ms-2">Agregar ítem</span>
                    </button>
                    <button class="btn btn-success w-100 my-1 waves-effect" @click="createUpdateEntity()">
                        <i class="fa fa-save"></i>
                        <span class="ms-2">Generar venta</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
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

export default {
    components: {
        Breadcrumb,
        InputDate,
        InputNumber,
        InputSelect,
        // InputSelect2,
        InputText,
        Paginator
    },
    mounted: async function() {

        Utils.openNavbarItem(this.config.entity.page.menu.id);
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});

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
                                items: {
                                    id: "addItemModal",
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar"
                                    }
                                }
                            },
                            select2: {
                                customer: "customerSelect2"
                            }
                        },
                        data: {
                            id: null,
                            details: [],
                            sale_date: "",
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
                            id: "menu-list-sales"
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
            this.options.customers = initParams.data?.config?.customers;

            return initParams?.bool && initParams?.data?.bool;

        },
        async initOthers({}) {

            return true;

        },
        async listEntity({url = null}) {

            //

        },
        // Forms
        modalAddDetail({}) {

            this.addDetail({});

        },
        addDetail({}) {

            (this.forms.entity.createUpdate.data.details).push({id: Utils.uuidv4(), name: "Gaseosa" , quantity: 3, price: 20, total: 60});

        },
        async createUpdateEntity() {

            //

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "createUpdateEntity":
                    //
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["createUpdateEntity"].includes(functionName)) {

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
