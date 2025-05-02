<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="nav-align-top">
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item" role="presentation">
                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-general" aria-controls="navs-pills-general" aria-selected="false" tabindex="-1">
                    <span class="d-none d-sm-inline-flex align-items-center fw-bold">
                        <i class="fa fa-info-circle me-1_5"></i>
                        <span class="ms-2">Registrar reclamo</span>
                    </span>
                    <i class="fa fa-info-circle d-sm-none"></i>
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-pills-general" role="tabpanel">
                <div class="row g-3">
                    <InputSlot
                        hasDiv
                        title="Tipo de documento"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.identity_document_type"
                        xl="6"
                        lg="6">
                        <template v-slot:input>
                            <v-select
                                v-model="forms.entity.createUpdate.data.identity_document_type"
                                :options="identityDocumentTypes"
                                :clearable="false"
                                :searchable="false"/>
                        </template>
                    </InputSlot>
                    <InputText
                        v-model="forms.entity.createUpdate.data.document_number"
                        hasDiv
                        title="Número de documento"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.document_number"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.name"
                        hasDiv
                        title="Nombre"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.name"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.email"
                        hasDiv
                        title="Correo electrónico"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.email"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.phone_number"
                        hasDiv
                        title="Celular"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.phone_number"
                        xl="6"
                        lg="6"/>
                    <InputSlot
                        hasDiv
                        title="Tipo de solicitud"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.type"
                        xl="6"
                        lg="6">
                        <template v-slot:input>
                            <v-select
                                v-model="forms.entity.createUpdate.data.type"
                                :options="types"
                                :clearable="false"
                                :searchable="false"/>
                        </template>
                    </InputSlot>
                    <InputText
                        v-model="forms.entity.createUpdate.data.description"
                        hasDiv
                        title="Descripción"
                        isRequired
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.description"
                        xl="6"
                        lg="6"/>
                    <InputText
                        v-model="forms.entity.createUpdate.data.request"
                        hasDiv
                        title="Pedido del cliente"
                        hasTextBottom
                        :textBottomInfo="forms.entity.createUpdate.errors?.request"
                        xl="6"
                        lg="6"/>
                </div>
            </div>
            <div class="mt-4">
                <div class="row g-3">
                    <div class="d-flex flex-row-reverse">
                        <button type="button" class="btn waves-effect btn-primary" @click="createUpdateEntity()">
                            <i class="fa fa-save"></i>
                            <span class="ms-2">Guardar información</span>
                        </button>
                    </div>
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
                        route: Requests.config({entity: "book_complaints", type: "list"})
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
                            identity_document_type: null,
                            document_number: "",
                            name: "",
                            email: "",
                            phone_number: "",
                            type: null,
                            description: "",
                            request: "",
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
                    ...Requests.config({entity: "book_complaints"}),
                    page: {
                        title: "Libro de reclamaciones",
                        active: true
                    }
                }
            }
        };
    },
    methods: {
        // Init
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, data: {page: "main"}, showAlert: true});

            this.options.bookComplaints        = initParams.data?.config?.bookComplaints;
            this.options.identityDocumentTypes = initParams.data?.config?.identityDocumentTypes;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.forms.entity.createUpdate.data.identity_document_type = (this.identityDocumentTypes).length > 0 ? this.identityDocumentTypes[0] : null;
                this.forms.entity.createUpdate.data.type                   = (this.types).length > 0 ? this.types[0] : null;

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {filter_by: filters?.filter_by?.code, word: filters.word};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        modalCreateUpdateEntity({record = null}) {

            const functionName = "modalCreateUpdateEntity";

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                let status = this.statuses.filter(e => e.code === record?.status)[0];

                this.forms.entity.createUpdate.data.id          = record?.id;
                this.forms.entity.createUpdate.data.name        = record?.name;
                this.forms.entity.createUpdate.data.status      = status;

            }else {

                this.forms.entity.createUpdate.data.status = this.statuses[0];

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

                form.identity_document_type_id = form?.identity_document_type?.code;
                form.type = form?.type?.code;

                delete form.identity_document_type;

                let createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: form.id}) :
                                                                             Requests.post({route: this.config.entity.routes.store, data: form}));

                if(Requests.valid({result: createUpdate})) {

                    Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});
                    Alerts.toastrs({type: "success", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                    this.clearForm({functionName});
                    // this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                this.formErrors({functionName, type: "set", errors: validateForm});
                Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                Alerts.swals({show: false});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id                     = null;
                    // this.forms.entity.createUpdate.data.identity_document_type = null;
                    this.forms.entity.createUpdate.data.document_number        = "";
                    this.forms.entity.createUpdate.data.name                   = "";
                    this.forms.entity.createUpdate.data.email                  = "";
                    this.forms.entity.createUpdate.data.phone_number           = "";
                    // this.forms.entity.createUpdate.data.type                   = null;
                    this.forms.entity.createUpdate.data.description            = "";
                    this.forms.entity.createUpdate.data.request                = "";
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

                result.identity_document_type = [];
                result.document_number        = [];
                result.name                   = [];
                result.email                  = [];
                result.phone_number           = [];
                result.type                   = [];
                result.description            = [];
                result.request                = [];

                if(!this.isDefined({value: form?.identity_document_type})) {

                    result.identity_document_type.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.document_number})) {

                    result.document_number.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.name})) {

                    result.name.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.type})) {

                    result.type.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.description})) {

                    result.description.push(this.config.forms.errors.labels.required);
                    result.bool = false;

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

            return [this.config.entity.page];

        },
        filterByOptions: function() {

            return [
                {code: "all", label: "Todos"},
                {code: "name", label: "Nombre"}
            ];

        },
        identityDocumentTypes: function() {

            return this.options?.identityDocumentTypes?.records.map(e => ({code: e.id, label: e.name}));

        },
        statuses: function() {

            return this.options?.bookComplaints?.statuses.map(e => ({code: e.code, label: e.label}));

        },
        types: function() {

            return this.options?.bookComplaints?.types.map(e => ({code: e.code, label: e.label}));

        }
    },
    watch: {
        "lists.entity.filters.filter_by": function(newValue, oldValue) {

            // this.listEntity({});

        }
    }
};
</script>
