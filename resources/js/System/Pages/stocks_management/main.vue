<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row align-items-end g-3 mb-4">
        <InputSlot
            hasDiv
            title="Almacén"
            :titleClass="[config.forms.classes.title]"
            xl="6"
            lg="7">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.warehouse"
                    :options="warehouses"
                    :class="config.forms.classes.select2"
                    :clearable="false"/>
            </template>
        </InputSlot>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            xl="5"
            lg="4">
            <template v-slot:input>
                <button v-if="!lists.entity.extras.loading" type="button" class="btn btn-primary waves-effect" @click="listEntity({})">
                    <i class="fa fa-search"></i>
                    <span class="ms-2">Buscar</span>
                </button>
                <button v-if="isDefined({value: lists.entity.filters.warehouse?.code}) && !lists.entity.extras.loading" type="button" class="btn btn-success waves-effect ms-3" @click="createUpdateEntity({})">
                    <i class="fa fa-save"></i>
                    <span class="ms-2">Guardar</span>
                </button>
            </template>
        </InputSlot>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center align-middle">
                    <th class="fw-bold col-1">#</th>
                    <th class="fw-bold col-3">PRODUCTO</th>
                    <th class="fw-bold col-2">CANTIDAD</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0 bg-white">
                <template v-if="lists.entity.extras.loading">
                    <tr class="text-center">
                        <td colspan="99">
                            <Loader/>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <template v-if="lists.entity.records.total > 0">
                        <tr v-for="(record, indexRecord) in lists.entity.records.data" :key="record.id" class="text-center">
                            <td v-text="indexRecord + 1"></td>
                            <td class="text-start">
                                <ul>
                                    <li>
                                        <span v-text="'Código interno:'" class="fw-bold"></span>
                                        <span v-text="record.internal_code" class="ms-2"></span>
                                    </li>
                                    <li>
                                        <span v-text="'Nombre:'" class="fw-bold"></span>
                                        <span v-text="record.name" class="ms-2"></span>
                                    </li>
                                    <li>
                                        <span v-text="'Descripción:'" class="fw-bold"></span>
                                        <span v-text="isDefined({value: record.description}) ? record.description : 'N/A'" class="ms-2"></span>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <InputNumber
                                    v-model="record.stock_quantity"/>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td class="text-center" colspan="99">
                                <WithoutData type="image"/>
                            </td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center d-none" v-if="!lists.entity.extras.loading && lists.entity.records?.total > 0">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
    </div>
</template>

<script>
import * as Alerts    from "../../Helpers/Alerts.js";
import * as Constants from "../../Helpers/Constants.js";
import * as Requests  from "../../Helpers/Requests.js";
import * as Utils     from "../../Helpers/Utils.js";

export default {
    components: {
        //
    },
    mounted: async function() {

        Utils.navbarItem("menu-item-inventories", {addClass: "open"});
        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            // this.listEntity({});

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "stocks_management", type: "list"})
                    },
                    filters: {
                        branch: null,

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
                                default: {}
                            }
                        },
                        data: {},
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "stocks_management"}),
                    page: {
                        title: "Gestión de stock",
                        active: true,
                        menu: {
                            id: "menu-item-inventories-stocks_management"
                        }
                    }
                }
            }
        };
    },
    methods: {
        // Init
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, data: {page: "main"}, showAlert: true});

            this.options.warehouses = initParams.data?.config?.warehouses;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.lists.entity.filters.warehouse = this.warehouses[0];

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {warehouse_id: filters?.warehouse?.code};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            const items = (this.lists.entity.records.data).map(e => ({id: e.id, stock_quantity: e?.stock_quantity}));

            let form = Utils.cloneJson({warehouse_id: this.lists.entity.filters.warehouse?.code, items});

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                let createUpdate = await Requests.post({route: this.config.entity.routes.store, data: form});

                if(Requests.valid({result: createUpdate})) {

                    Alerts.toastrs({type: "success", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                    // this.clearForm({functionName});
                    // this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                Alerts.generateAlert({messages: validateForm?.msg, msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

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

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

                result.msg = [];

                if(!this.isDefined({value: form?.warehouse_id})) {

                    result.msg.push(`<b>Almacén:</b> ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                for(const [indexRecord, record] of Object.entries(form.items)) {

                    const seq = parseInt(indexRecord) + 1;

                    if(Number(record?.stock_quantity ?? 0) < 0) {

                        result.msg.push(`<b>Producto #${seq}:</b> Cantidad / ${this.config.forms.errors.labels.required}`);
                        result.bool = false;

                    }

                }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        tooltips({show = true, time = 10}) {

            Alerts.tooltips({show, time});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Inventario"}, this.config.entity.page];

        },
        warehouses: function() {

            return this.options?.warehouses?.records.map(e => ({code: e.id, label: e.name}));

        }
    },
    watch: {
        "lists.entity.filters.warehouse": function(newValue, oldValue) {

            if(this.isDefined({value: this.lists.entity.filters.warehouse?.code})) {

                this.listEntity({});

            }else {

                this.lists.entity.records = {
                    total: 0,
                    data: []
                };

            }

        }
    }
};
</script>
