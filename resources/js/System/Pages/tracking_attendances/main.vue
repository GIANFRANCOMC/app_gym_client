<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row align-items-end g-3 mb-4">
        <InputSlot
            hasDiv
            title="Sucursal"
            :titleClass="[config.forms.classes.title]"
            xl="12"
            lg="12">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.branch"
                    :options="branches"
                    :class="config.forms.classes.select2"
                    :clearable="false"/>
            </template>
        </InputSlot>
        <InputDate
            v-model="lists.entity.filters.start_date"
            hasDiv
            title="Fecha de ingreso"
            :titleClass="[config.forms.classes.title]"
            xl="5"
            lg="4"/>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            xl="auto"
            lg="auto">
            <template v-slot:input>
                <template v-if="!lists.entity.extras.loading">
                    <button type="button" class="btn btn-primary waves-effect" @click="listEntity({})">
                        <i class="fa fa-search"></i>
                        <span class="ms-2">Buscar</span>
                    </button>
                    <button type="button" class="btn btn-primary waves-effect ms-3" @click="modalCreateUpdateEntity({type: 'store'})">
                        <i class="fa fa-plus"></i>
                        <span class="ms-2">Agregar asistencia</span>
                    </button>
                </template>
            </template>
        </InputSlot>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center align-middle">
                    <th class="fw-bold col-1">SUCURSAL</th>
                    <th class="fw-bold col-1">CLIENTE</th>
                    <th class="fw-bold col-1">INGRESO</th>
                    <th class="fw-bold col-1">SALIDA</th>
                    <th class="fw-bold col-1">ACCIONES</th>
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
                        <tr v-for="record in lists.entity.records.data" :key="record.id" class="text-center">
                            <td class="text-start">
                                <span v-text="record.branch?.name" class="fw-bold d-block"></span>
                            </td>
                            <td class="text-start">
                                <span v-text="record.customer?.name" class="fw-bold d-block"></span>
                                <small v-text="record.customer?.document_number" class="d-block"></small>
                            </td>
                            <td>
                                <span v-text="legibleFormatDate({dateString: record.start_date, type: 'date'})" class="d-block fw-semibold"></span>
                                <span v-text="legibleFormatDate({dateString: record.start_date, type: 'time'})" class="d-block fw-semibold"></span>
                            </td>
                            <td>
                                <template v-if="isDefined({value: record.end_date})">
                                    <span v-text="legibleFormatDate({dateString: record.end_date, type: 'date'})" class="d-block fw-semibold"></span>
                                    <span v-text="legibleFormatDate({dateString: record.end_date, type: 'time'})" class="d-block fw-semibold"></span>
                                </template>
                                <template v-else>
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <span class="alert-icon rounded">
                                            <i class="fa fa-warning"></i>
                                        </span>
                                        <span class="ms-3">Pendiente</span>
                                    </div>
                                </template>
                            </td>
                            <td>
                                <button v-if="['active'].includes(record?.status)" type="button" class="btn btn-sm btn-success waves-effect my-1" @click="modalCreateUpdateEntity({record, type: 'finalized'})">
                                    <i class="fa fa-check"></i>
                                    <span class="ms-2">Finalizar</span>
                                </button>
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

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.default.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.createUpdate.extras.modals.default.titles[forms.entity.createUpdate.extras.modals.default.type]"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <InputSlot
                            hasDiv
                            title="Sucursal"
                            isRequired
                            xl="12"
                            lg="12">
                            <template v-slot:input>
                                <span v-if="isDefined({value: forms.entity.createUpdate.data?.id})" v-text="forms.entity.createUpdate.data?.branch?.data?.name" class="fw-semibold"></span>
                                <v-select
                                    v-else
                                    v-model="forms.entity.createUpdate.data.branch"
                                    :options="branches"
                                    :class="config.forms.classes.select2"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
                        <InputSlot
                            hasDiv
                            title="Cliente"
                            isRequired
                            xl="12"
                            lg="12">
                            <template v-slot:input>
                                <span v-if="isDefined({value: forms.entity.createUpdate.data?.id})" v-text="forms.entity.createUpdate.data?.customer?.data?.name" class="fw-semibold"></span>
                                <v-select
                                    v-else
                                    v-model="forms.entity.createUpdate.data.customer"
                                    :options="customers"
                                    :class="config.forms.classes.select2"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
                        <InputDatetime
                            v-model="forms.entity.createUpdate.data.start_date"
                            hasDiv
                            title="Ingreso"
                            isRequired
                            :disabled="isDefined({value: forms.entity.createUpdate.data?.id})"
                            xl="6"
                            lg="6"/>
                        <InputDatetime
                            v-model="forms.entity.createUpdate.data.end_date"
                            hasDiv
                            title="Salida"
                            :isRequired="isDefined({value: forms.entity.createUpdate.data?.id})"
                            :disabled="!isDefined({value: forms.entity.createUpdate.data?.id})"
                            xl="6"
                            lg="6"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', forms.entity.createUpdate.extras.modals.default.config.buttons[forms.entity.createUpdate.extras.modals.default.type]]" @click="createUpdateEntity()">
                        <i :class="forms.entity.createUpdate.extras.modals.default.config.icons[forms.entity.createUpdate.extras.modals.default.type]"></i>
                        <span class="ms-2" v-text="forms.entity.createUpdate.extras.modals.default.config.labels[forms.entity.createUpdate.extras.modals.default.type]"></span>
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

export default {
    components: {
        //
    },
    mounted: async function() {

        Utils.navbarItem("menu-item-trackings", {addClass: "open"});
        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            this.listEntity({});

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "tracking_attendances", type: "list"})
                    },
                    filters: {
                        branch: null
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
                                    id: Utils.uuid(),
                                    type: "",
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar",
                                        finalized: "Finalizar"
                                    },
                                    config: {
                                        buttons: {
                                            store: "btn-primary",
                                            update: "btn-warning",
                                            finalized: "btn-success"
                                        },
                                        icons: {
                                            store: "fa fa-plus",
                                            update: "fa fa-save",
                                            finalized: "fa fa-check"
                                        },
                                        labels: {
                                            store: "Agregar asistencia",
                                            update: "Editar asistencia",
                                            finalized: "Finalizar asistencia"
                                        }
                                    }
                                }
                            }
                        },
                        data: {
                            id: null,
                            branch: null,
                            customer: null,
                            start_date: "",
                            end_date: "",
                            status: null
                        },
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "tracking_attendances"}),
                    page: {
                        title: "Asistencia",
                        active: true,
                        menu: {
                            id: "menu-item-trackings-attendances"
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

            this.options.branches  = initParams.data?.config?.branches;
            this.options.customers = initParams.data?.config?.customers;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.lists.entity.filters.branch     = this.branches[0];
                this.lists.entity.filters.start_date = Utils.getCurrentDate("date");

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {branch_id: filters?.branch?.code, start_date: filters?.start_date};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        modalCreateUpdateEntity({record = null, type = "create"}) {

            const functionName = "modalCreateUpdateEntity";

            this.forms.entity.createUpdate.extras.modals.default.type = type;

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                const branch   = this.branches.filter(e => e.code === record?.branch?.id)[0],
                      customer = this.customers.filter(e => e.code === record?.customer?.id)[0];

                this.forms.entity.createUpdate.data.id         = record?.id;
                this.forms.entity.createUpdate.data.branch     = branch;
                this.forms.entity.createUpdate.data.customer   = customer;
                this.forms.entity.createUpdate.data.start_date = record.start_date;
                this.forms.entity.createUpdate.data.end_date   = this.isDefined({value: record.end_date}) ? record.end_date : Utils.getCurrentDate("datetime");

            }else {

                this.forms.entity.createUpdate.data.branch     = this.branches[0];
                this.forms.entity.createUpdate.data.start_date = Utils.getCurrentDate("datetime");

            }

            // Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.default.id});

        },
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.createUpdate.data);

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive", modal: this.forms.entity.createUpdate.extras.modals.default}});

            if(validateForm?.bool) {

                form.branch_id   = form?.branch?.code;
                form.customer_id = form?.customer?.code;

                delete form.branch;
                delete form.customer;

                const createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: form.id}) :
                                                                               Requests.post({route: this.config.entity.routes.store, data: form}));

                if(Requests.valid({result: createUpdate})) {

                    if(["finalized"].includes(this.forms.entity.createUpdate.extras.modals.default.type)) {

                        Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});

                    }

                    Alerts.toastrs({type: "success", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                    this.clearForm({functionName});
                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id         = null;
                    // this.forms.entity.createUpdate.data.branch     = null;
                    this.forms.entity.createUpdate.data.customer   = null;
                    // this.forms.entity.createUpdate.data.start_date = "";
                    this.forms.entity.createUpdate.data.end_date   = "";
                    this.forms.entity.createUpdate.data.status     = null;

                    if(["finalized"].includes(this.forms.entity.createUpdate.extras.modals.default.type)) {

                        this.forms.entity.createUpdate.data.branch     = null;
                        this.forms.entity.createUpdate.data.start_date = "";

                    }
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["modalCreateUpdateEntity", "createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

                result.id         = [];
                result.branch     = [];
                result.customer   = [];
                result.start_date = [];
                result.end_date   = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                const isFinalized = ["finalized"].includes(extras?.modal?.type);

                if(isFinalized) {

                    if(!this.isDefined({value: form?.id})) {

                        result.id.push(`${isDescriptive ? "Registro:" : ""} ${this.config.forms.errors.labels.required}`);
                        result.bool = false;

                    }

                }

                if(!this.isDefined({value: form?.branch})) {

                    result.branch.push(`${isDescriptive ? "Sucursal:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.customer})) {

                    result.customer.push(`${isDescriptive ? "Cliente:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.start_date})) {

                    result.start_date.push(`${isDescriptive ? "Ingreso:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(isFinalized) {

                    if(!this.isDefined({value: form?.end_date})) {

                        result.end_date.push(`${isDescriptive ? "Salida:" : ""} ${this.config.forms.errors.labels.required}`);
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
        legibleFormatDate({dateString = null, type = "datetime"}) {

            return Utils.legibleFormatDate({dateString, type});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Seguimiento"}, this.config.entity.page];

        },
        branches: function() {

            return this.options?.branches?.records.map(e => ({code: e.id, label: e.name, data: e}));

        },
        customers: function() {

            return this.options?.customers?.records.map(e => ({code: e.id, label: `${e.document_number} - ${e.name}`, data: e}));

        },
        statuses: function() {

            return this.options?.subscriptions?.statuses.map(e => ({code: e.code, label: e.label}));

        }
    },
    watch: {
        "lists.entity.filters.branch": function(newValue, oldValue) {

            // this.listEntity({});

        }
    }
};
</script>
