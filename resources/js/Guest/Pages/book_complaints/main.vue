<template>
    <div class="container flex-grow-1 container-p-y mt-5">
        <div class="text-end mt-1 mt-md-2 mb-2 py-4">
            <span class="badge bg-label-primary">Libro de reclamaciones y sugerencias</span>
        </div>
        <h4 class="text-center mb-1">
            <span class="position-relative fw-extrabold z-1">Registra tu queja, reclamo o sugerencia</span>
        </h4>
        <div class="text-center text-muted pb-2 mb-3">
            <span class="d-block fw-regular">Cuéntanos lo ocurrido o propon ideas para mejorar nuestro servicio.</span>
            <div class="d-block fw-regular">Todos los campos con <span class="text-danger">*</span> son obligatorios. </div>
        </div>

        <!-- Content -->
        <div class="nav-align-top mt-3 shadow-lg">
            <ul class="nav nav-tabs nav-fill" role="tablist" v-show="false">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-1" aria-controls="navs-pills-1" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-inline-flex align-items-center fw-bold">
                            <i class="fa fa-info-circle me-1_5"></i>
                            <span class="ms-2">Tipo</span>
                        </span>
                        <i class="fa fa-info-circle d-sm-none"></i>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-2" aria-controls="navs-pills-2" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-inline-flex align-items-center fw-bold">
                            <i class="fa fa-info-circle me-1_5"></i>
                            <span class="ms-2">Identificación</span>
                        </span>
                        <i class="fa fa-info-circle d-sm-none"></i>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-3" aria-controls="navs-pills-3" aria-selected="false" tabindex="-1">
                        <span class="d-none d-sm-inline-flex align-items-center fw-bold">
                            <i class="fa fa-info-circle me-1_5"></i>
                            <span class="ms-2">Detalle</span>
                        </span>
                        <i class="fa fa-info-circle d-sm-none"></i>
                    </button>
                </li>
            </ul>
            <div class="tab-content px-5">
                <ul class="list-inline d-flex flex-wrap justify-content-center align-items-center mb-4 mt-3">
                    <li class="list-inline-item me-4 text-center">
                        <span :class="['badge rounded-pill px-3 py-2', forms.entity.createUpdate.data.step >= 1 ? 'bg-primary' : 'bg-secondary']">1</span>
                        <small class="d-block mt-1">Tipo</small>
                    </li>
                    <li class="list-inline-item me-4  text-center">
                        <span :class="['badge rounded-pill px-3 py-2', forms.entity.createUpdate.data.step >= 2 ? 'bg-primary' : 'bg-secondary']">2</span>
                        <small class="d-block mt-1">Identificación</small>
                    </li>
                    <li class="list-inline-item  text-center">
                        <span :class="['badge rounded-pill px-3 py-2', forms.entity.createUpdate.data.step >= 3 ? 'bg-primary' : 'bg-secondary']">3</span>
                        <small class="d-block mt-1">Detalle</small>
                    </li>
                </ul>
                <div class="tab-pane fade" id="navs-pills-1" role="tabpanel">
                    <h2 class="fw-bold text-heading my-0 text-center">¿Qué tipo de comunicación deseas realizar?</h2>
                    <p class="text-muted fw-semibold text-center mb-4">Selecciona una de las opciones que mejor describa tu solicitud.</p>
                    <div class="row g-3 py-8">
                        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12" v-for="record in types" :key="record.code">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content form-check-input-payment gap-4 align-items-center">
                                    <input v-model="forms.entity.createUpdate.data.type" class="form-check-input my-2" type="radio" :value="record.code">
                                    <span class="custom-option-body">
                                        <div class="ms-3 fw-bold text-heading fs-5">
                                            <span :class="[record?.data?.icon, 'text-'+record?.data?.color]"></span>
                                            <span v-text="record?.label" :class="['ms-3', 'text-'+record?.data?.color]"></span>
                                        </div>
                                        <p class="ms-1 mt-2 fw-semibold text-muted" v-text="record?.data?.description"></p>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-row-reverse mt-4">
                        <button type="button" class="btn waves-effect btn-primary" @click="changeStep('2')" :disabled="!isDefined({value: forms.entity.createUpdate.data.type})">
                            <span class="ms-2">Siguiente: Identificación</span>
                            <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-2" role="tabpanel">
                    <legend class="fw-bold text-heading my-0 text-center">Completa tus datos personales</legend>
                    <p class="text-muted fw-semibold text-center mb-4">Necesitamos esta información para continuar con tu solicitud</p>
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
                                    @close="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                                    :options="identityDocumentTypes"
                                    :clearable="false"
                                    :searchable="false"/>
                            </template>
                        </InputSlot>
                        <InputText
                            v-model="forms.entity.createUpdate.data.document_number"
                            @input="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                            hasDiv
                            title="Número de documento"
                            isRequired
                            placeholder="Ej. 12345678"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.document_number"
                            xl="6"
                            lg="6"/>
                        <InputText
                            v-model="forms.entity.createUpdate.data.name"
                            @input="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                            hasDiv
                            title="Nombre"
                            placeholder="Ej. Juan Pérez"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.name"
                            xl="6"
                            lg="6"/>
                        <InputText
                            v-model="forms.entity.createUpdate.data.email"
                            @input="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                            hasDiv
                            title="Correo electrónico"
                            placeholder="Ej. juan@hotmail.com"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.email"
                            xl="6"
                            lg="6"/>
                        <InputText
                            v-model="forms.entity.createUpdate.data.phone_number"
                            @input="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                            hasDiv
                            title="Celular"
                            placeholder="Ej. 987876762"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.phone_number"
                            xl="6"
                            lg="6"/>
                    </div>
                    <div class="d-flex flex-wrap flex-row-reverse gap-2 mt-4">
                        <button type="button" class="btn waves-effect btn-primary" @click="changeStep('3')" :disabled="!isDefined({value: forms.entity.createUpdate.data.identity_document_type}) || !isDefined({value: forms.entity.createUpdate.data.document_number}) || !isDefined({value: forms.entity.createUpdate.data.name})">
                            <span>Siguiente: Detalle</span>
                            <i class="fa fa-arrow-right ms-2"></i>
                        </button>
                        <button type="button" class="btn waves-effect btn-secondary" @click="changeStep('1')">
                            <i class="fa fa-arrow-left"></i>
                            <span class="ms-2">Anterior: Tipo</span>
                        </button>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-3" role="tabpanel">
                    <h2 class="fw-bold text-heading my-0 text-center">Describe lo ocurrido</h2>
                    <p class="text-muted fw-semibold text-center mb-4">Proporciónanos los detalles de lo sucedido y lo que deseas que hagamos al respecto.</p>
                    <div class="row g-3">
                        <InputTextArea
                            v-model="forms.entity.createUpdate.data.description"
                            @input="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                            hasDiv
                            title="Descripción"
                            isRequired
                            placeholder="Ej: El producto llegó dañado"
                            maxlength="200"
                            rows="3"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.description"
                            xl="12"
                            lg="12"/>
                        <InputTextArea
                            v-model="forms.entity.createUpdate.data.request"
                            @input="validateForm({functionName: 'createUpdateEntity', form: forms.entity.createUpdate.data, extras: {setErrors: true}})"
                            hasDiv
                            title="Pedido del cliente"
                            placeholder="Ej: Solicito un reemplazo o reembolso"
                            maxlength="200"
                            rows="3"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.request"
                            xl="12"
                            lg="12"/>
                    </div>
                    <div class="d-flex flex-wrap flex-row-reverse gap-2 mt-4">
                        <button type="button" class="btn waves-effect btn-success" @click="createUpdateEntity()" :disabled="!isDefined({value: forms.entity.createUpdate.data.description})" v-if="isDefined({value: forms.entity.createUpdate.data.type})">
                            <i class="fa fa-check-circle"></i>
                            <span class="ms-2" v-text="'Enviar '+(currentType?.label).toLowerCase()"></span>
                        </button>
                        <button type="button" class="btn waves-effect btn-secondary" @click="changeStep('2')">
                            <i class="fa fa-arrow-left"></i>
                            <span class="ms-2">Anterior: Detalle</span>
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
                            step: 0,
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
                    ...Requests.config({entity: "book_complaints"})
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

                this.changeStep("1");

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
        changeStep(step) {

            this.formErrors({functionName: "createUpdateEntity", type: "clear"});

            const tabTrigger = document.querySelector(`[data-bs-target="#navs-pills-${step}"]`);
            const tab = new bootstrap.Tab(tabTrigger);
            tab.show();

            this.forms.entity.createUpdate.data.step = step;

        },
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

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.identity_document_type_id = form?.identity_document_type?.code;

                delete form.identity_document_type;

                let createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: form.id}) :
                                                                             Requests.post({route: this.config.entity.routes.store, data: form}));

                if(Requests.valid({result: createUpdate})) {

                    // Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});
                    // Alerts.toastrs({type: "success", subtitle: createUpdate?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", msgContent: createUpdate?.data?.msg});

                    this.clearForm({functionName});
                    // this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});
                    this.changeStep("1");

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    // Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "error", msgContent: createUpdate?.data?.msg});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                // Alerts.swals({show: false});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

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

                result.type                   = [];
                result.identity_document_type = [];
                result.document_number        = [];
                result.name                   = [];
                result.email                  = [];
                result.phone_number           = [];
                result.description            = [];
                result.request                = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.type})) {

                    result.type.push(`${isDescriptive ? "Tipo:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.identity_document_type})) {

                    result.identity_document_type.push(`${isDescriptive ? "Tipo de documento:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.document_number})) {

                    result.document_number.push(`${isDescriptive ? "Número de documento:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.name})) {

                    result.name.push(`${isDescriptive ? "Nombre:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.description})) {

                    result.description.push(`${isDescriptive ? "Descripción:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(extras?.setErrors) {

                    this.formErrors({functionName, type: "set", errors: result});

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
        filterByOptions: function() {

            return [
                {code: "all", label: "Todos"},
                {code: "name", label: "Nombre"}
            ];

        },
        identityDocumentTypes: function() {

            return this.options?.identityDocumentTypes?.records.map(e => ({code: e.id, label: e.name}));

        },
        types: function() {

            return this.options?.bookComplaints?.types.map(e => ({code: e.code, label: e.label, data: e}));

        },
        statuses: function() {

            return this.options?.bookComplaints?.statuses.map(e => ({code: e.code, label: e.label}));

        },
        currentType() {

            const currentType = (this.types ?? []).filter(e => e.code == this.forms.entity.createUpdate.data.type);

            return currentType.length > 0 ? currentType[0] : null;

        }
    },
    watch: {
        "lists.entity.filters.filter_by": function(newValue, oldValue) {

            // this.listEntity({});

        }
    }
};
</script>
