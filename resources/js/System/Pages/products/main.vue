<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row align-items-end g-3 mb-4">
        <InputSlot
            hasDiv
            title="Filtrar por"
            :titleClass="[config.forms.classes.title]"
            xl="3"
            lg="4">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.filter_by"
                    :options="filterByOptions"
                    :class="config.forms.classes.select2"
                    :clearable="false"/>
            </template>
        </InputSlot>
        <InputText
            v-model="lists.entity.filters.word"
            @enterKeyPressed="listEntity({})"
            hasDiv
            title="Búsqueda"
            :titleClass="[config.forms.classes.title]"
            xl="4"
            lg="4"/>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            xl="5"
            lg="4">
            <template v-slot:input>
                <button type="button" class="btn btn-primary waves-effect" @click="listEntity({})">
                    <i class="fa fa-search"></i>
                    <span class="ms-2">Buscar</span>
                </button>
                <button type="button" class="btn btn-primary waves-effect ms-3" @click="modalCreateUpdateEntity({})">
                    <i class="fa fa-plus"></i>
                    <span class="ms-2">Agregar</span>
                </button>
            </template>
        </InputSlot>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center align-middle">
                    <th class="fw-bold col-1">CÓDIGO INTERNO</th>
                    <th class="fw-bold col-1">NOMBRE</th>
                    <th class="fw-bold col-1">DESCRIPCIÓN</th>
                    <th class="fw-bold col-1">PRECIO</th>
                    <th class="fw-bold col-1">ESTADO</th>
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
                            <td v-text="record.internal_code"></td>
                            <td v-text="record.name"></td>
                            <td v-text="record.description"></td>
                            <td>
                                <span v-text="record.currency?.sign"></span>
                                <span v-text="record.price" class="ms-2"></span>
                            </td>
                            <td>
                                <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning waves-effect" @click="modalCreateUpdateEntity({record})">
                                    <i class="fa fa-pencil"></i>
                                    <span class="ms-2">Editar</span>
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
    <div class="d-flex justify-content-center" v-if="!lists.entity.extras.loading && lists.entity.records?.total > 0">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.default.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.createUpdate.extras.modals.default.titles[isDefined({value: forms.entity.createUpdate.data?.id}) ? 'update' : 'store']"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <InputText
                            v-model="forms.entity.createUpdate.data.internal_code"
                            hasDiv
                            title="Código interno"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.internal_code"
                            xl="5"
                            lg="5">
                            <template v-slot:inputGroupAppend>
                                <button type="button" :class="['btn waves-effect', isDefined({value: forms.entity.createUpdate.data?.id}) ? 'btn-warning' : 'btn-primary']" @click="setGenerateCode({length: 12})" data-bs-toggle="tooltip" data-bs-placement="top" title="Generar aleatoriamente">
                                    <i class="fa fa-rotate"></i>
                                </button>
                            </template>
                        </InputText>
                        <InputText
                            v-model="forms.entity.createUpdate.data.name"
                            hasDiv
                            title="Nombre"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.name"
                            xl="7"
                            lg="7"/>
                        <InputText
                            v-model="forms.entity.createUpdate.data.description"
                            hasDiv
                            title="Descripción"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.description"
                            xl="12"
                            lg="12"/>
                        <InputNumber
                            v-model="forms.entity.createUpdate.data.price"
                            hasDiv
                            title="Precio"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.price"
                            xl="6"
                            lg="6"/>
                        <InputSlot
                            hasDiv
                            title="Moneda"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.currency"
                            xl="6"
                            lg="6">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.currency"
                                    :options="currencies"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
                        <InputSlot
                            hasDiv
                            title="Estado"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.status"
                            xl="6"
                            lg="6">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.status"
                                    :options="statusses"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', isDefined({value: forms.entity.createUpdate.data?.id}) ? 'btn-warning' : 'btn-primary']" @click="createUpdateEntity()">
                        <i class="fa fa-save"></i>
                        <span class="ms-2">Guardar</span>
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

        Utils.navbarItem("menu-item-catalogs", {addClass: "open"});
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
                        route: Requests.config({entity: "products", type: "list"})
                    },
                    filters: {
                        filter_by: null,
                        word: ""
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
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar"
                                    }
                                }
                            }
                        },
                        data: {
                            id: null,
                            internal_code: "",
                            name: "",
                            description: "",
                            price: "",
                            currency: null,
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
                    ...Requests.config({entity: "products"}),
                    page: {
                        title: "Productos",
                        active: true,
                        menu: {
                            id: "menu-item-products"
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

            this.options.currencies = initParams.data?.config?.currencies;
            this.options.products   = initParams.data?.config?.products;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.lists.entity.filters.filter_by = this.filterByOptions[0];

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {filter_by: filters?.filter_by?.code, word: filters.word};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: {...filterJson}}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        setGenerateCode({length, showAlert = true}) {

            this.forms.entity.createUpdate.data.internal_code = this.generateCode({length});

            if(showAlert) {

                Alerts.toastrs({type: "success", subtitle: "Código interno generado correctamente."});
                Alerts.tooltips({show: false});

            }

        },
        modalCreateUpdateEntity({record = null}) {

            const functionName = "modalCreateUpdateEntity";

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                let currency = this.currencies.filter(e => e.code === record?.currency_id)[0],
                    status   = this.statusses.filter(e => e.code === record?.status)[0];

                this.forms.entity.createUpdate.data.id            = record?.id;
                this.forms.entity.createUpdate.data.internal_code = record?.internal_code;
                this.forms.entity.createUpdate.data.name          = record?.name;
                this.forms.entity.createUpdate.data.description   = record?.description;
                this.forms.entity.createUpdate.data.price         = record?.price;
                this.forms.entity.createUpdate.data.currency      = currency;
                this.forms.entity.createUpdate.data.status        = status;

            }else {

                // this.setGenerateCode({showAlert: false});
                this.forms.entity.createUpdate.data.currency = this.currencies[0];
                this.forms.entity.createUpdate.data.status   = this.statusses[0];

            }

            // Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.default.id});

        },
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.createUpdate.data);

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                form.currency_id = form?.currency?.code;
                form.status = form?.status?.code;

                delete form.currency;

                let createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: form.id}) : Requests.post({route: this.config.entity.routes.store, data: form}));

                if(Requests.valid({result: createUpdate})) {

                    Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});
                    Alerts.toastrs({type: "success", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                    this.clearForm({functionName});
                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`})

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                this.formErrors({functionName, type: "set", errors: validateForm});
                Alerts.swals({show: false});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id            = null;
                    this.forms.entity.createUpdate.data.internal_code = "";
                    this.forms.entity.createUpdate.data.name          = "";
                    this.forms.entity.createUpdate.data.description   = "";
                    this.forms.entity.createUpdate.data.price         = "";
                    this.forms.entity.createUpdate.data.currency      = null;
                    this.forms.entity.createUpdate.data.status        = null;
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["modalCreateUpdateEntity", "createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

                result.internal_code = [];
                result.name          = [];
                result.description   = [];
                result.price         = [];
                result.currency      = [];
                result.status        = [];

                if(!this.isDefined({value: form?.internal_code})) {

                    result.internal_code.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.name})) {

                    result.name.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.price})) {

                    result.price.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.currency})) {

                    result.currency.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.status})) {

                    result.status.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        generateCode({length}) {

            return Utils.generateCode({length});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Catálogo comercial"}, this.config.entity.page];

        },
        filterByOptions: function() {

            return [
                {code: "all", label: "Todos"},
                {code: "internal_code", label: "Código interno"},
                {code: "name", label: "Nombre"},
                {code: "description", label: "Descripción"},
                {code: "price", label: "Precio"}
            ];

        },
        currencies: function() {

            return this.options?.currencies?.records.map(e => ({code: e.id, label: e.plural_name}));

        },
        statusses: function() {

            return this.options?.products?.statusses.map(e => ({code: e.code, label: e.label}));

        }
    },
    watch: {
        "lists.entity.filters.filter_by": function(newValue, oldValue) {

            // this.listEntity({});

        }
    }
};
</script>
