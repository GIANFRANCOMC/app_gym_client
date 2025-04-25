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
                <button type="button" class="btn btn-primary waves-effect" @click="listEntity({})" :disabled="lists.entity.extras.loading">
                    <i class="fa fa-search"></i>
                    <span class="ms-2">Buscar</span>
                </button>
                <button type="button" class="btn btn-primary waves-effect ms-3" @click="modalCreateUpdateEntity({})" :disabled="lists.entity.extras.loading">
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
                    <th class="fw-bold col-1">ROL</th>
                    <th class="fw-bold col-1">NÚMERO DE DOCUMENTO</th>
                    <th class="fw-bold col-1">NOMBRE</th>
                    <th class="fw-bold col-1">CORREO ELECTRÓNICO</th>
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
                            <td v-text="record?.role?.name"></td>
                            <td>
                                <span v-text="record.document_number" class="d-block fw-bold"></span>
                                <span v-text="record.identity_document_type?.name" class="d-block badge bg-label-primary fw-bold mt-1"></span>
                            </td>
                            <td v-text="record.name"></td>
                            <td v-text="isDefined({value: record.email}) ? record.email : 'N/A'"></td>
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
                        <InputSlot
                            hasDiv
                            title="Rol"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.role"
                            xl="12"
                            lg="12">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.role"
                                    :options="roles"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
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
                                    @open="tooltips({show: true, time: 1000})"
                                    :clearable="false"/>
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
                            lg="6">
                            <template v-slot:inputGroupAppend>
                                <template v-if="[2].includes(forms.entity.createUpdate.data.identity_document_type?.code)">
                                    <button class="btn btn-primary waves-effect" type="button" @click="searchDocumentNumber({consult: forms.entity.createUpdate})" data-bs-toggle="tooltip" data-bs-placement="top" title="Buscar">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </template>
                            </template>
                        </InputText>
                        <InputText
                            v-model="forms.entity.createUpdate.data.name"
                            hasDiv
                            title="Nombre"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.name"
                            xl="12"
                            lg="12"/>
                        <InputText
                            v-model="forms.entity.createUpdate.data.email"
                            hasDiv
                            title="Correo electrónico"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.email"
                            xl="6"
                            lg="6"/>
                        <InputText
                            v-model="forms.entity.createUpdate.data.password"
                            hasDiv
                            :title="isDefined({value: forms.entity.createUpdate.data?.id}) ? 'Cambiar contraseña' : 'Contraseña'"
                            :isRequired="!isDefined({value: forms.entity.createUpdate.data?.id})"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.password"
                            xl="6"
                            lg="6"/>
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
                                    :options="statuses"
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

        Utils.navbarItem("menu-item-configuration", {addClass: "open"});
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
                        route: Requests.config({entity: "users", type: "list"})
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
                            role: null,
                            identity_document_type: null,
                            document_number: "",
                            name: "",
                            email: "",
                            password: "",
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
                    ...Requests.config({entity: "users"}),
                    page: {
                        title: "Colaboradores",
                        active: true,
                        menu: {
                            id: "menu-item-configuration-users"
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

            this.options.roles                 = initParams.data?.config?.roles;
            this.options.identityDocumentTypes = initParams.data?.config?.identityDocumentTypes;
            this.options.users                 = initParams.data?.config?.users;

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

                let role                 = this.roles.filter(e => e.code === record?.role_id)[0],
                    identityDocumentType = this.identityDocumentTypes.filter(e => e.code === record?.identity_document_type_id)[0],
                    status               = this.statuses.filter(e => e.code === record?.status)[0];

                this.forms.entity.createUpdate.data.role                   = role;
                this.forms.entity.createUpdate.data.id                     = record?.id;
                this.forms.entity.createUpdate.data.identity_document_type = identityDocumentType;
                this.forms.entity.createUpdate.data.document_number        = record?.document_number;
                this.forms.entity.createUpdate.data.name                   = record?.name;
                this.forms.entity.createUpdate.data.email                  = record?.email;
                this.forms.entity.createUpdate.data.password               = "";
                this.forms.entity.createUpdate.data.status                 = status;

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

                form.role_id = form?.role?.code;
                form.identity_document_type_id = form?.identity_document_type?.code;
                form.status = form?.status?.code;

                delete form.role;
                delete form.identity_document_type;

                let createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: form.id}) :
                                                                             Requests.post({route: this.config.entity.routes.store, data: form}));

                if(Requests.valid({result: createUpdate})) {

                    Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.default.id});
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
                    this.forms.entity.createUpdate.data.role                   = null;
                    this.forms.entity.createUpdate.data.identity_document_type = null;
                    this.forms.entity.createUpdate.data.document_number        = "";
                    this.forms.entity.createUpdate.data.name                   = "";
                    this.forms.entity.createUpdate.data.email                  = "";
                    this.forms.entity.createUpdate.data.password               = "";
                    this.forms.entity.createUpdate.data.status                 = null;
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

                result.role                   = [];
                result.identity_document_type = [];
                result.document_number        = [];
                result.name                   = [];
                result.email                  = [];
                result.password               = [];
                result.status                 = [];

                if(!this.isDefined({value: form?.role})) {

                    result.role.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

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

                if(!this.isDefined({value: form?.email})) {

                    result.email.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.id})) {

                    if(!this.isDefined({value: form?.password})) {

                        result.password.push(this.config.forms.errors.labels.required);
                        result.bool = false;

                    }

                }

                if(!this.isDefined({value: form?.status})) {

                    result.status.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

            }

            return result;

        },
        async searchDocumentNumber({consult}) {

            let route = Requests.config({entity: "helpers", type: "searchDocumentNumber"});
            const formJson = {document_number: consult.data.document_number, type: consult.data.identity_document_type?.code};

            Alerts.swals({});

            let searchDocumentNumber = await Requests.get({route: route, data: formJson});

            if(Requests.valid({result: searchDocumentNumber})) {

                const data = searchDocumentNumber.data.data;

                this.forms.entity.createUpdate.data.name = `${data?.first_name} ${data?.last_name} ${data?.second_last_name}`;

                Alerts.toastrs({type: "success", subtitle: searchDocumentNumber?.data?.msg});
                Alerts.swals({show: false});

            }else {

                Alerts.toastrs({type: "error", subtitle: searchDocumentNumber?.data?.msg});
                Alerts.swals({show: false});

            }

            Alerts.tooltips({show: false});

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

            return [{title: "Configuración"}, this.config.entity.page];

        },
        filterByOptions: function() {

            return [
                {code: "all", label: "Todos"},
                {code: "document_number", label: "Número de documento"},
                {code: "name", label: "Nombre"},
                {code: "email", label: "Correo electrónico"}
            ];

        },
        roles: function() {

            return this.options?.roles?.records.map(e => ({code: e.id, label: e.name}));

        },
        identityDocumentTypes: function() {

            return this.options?.identityDocumentTypes?.records.map(e => ({code: e.id, label: e.name}));

        },
        statuses: function() {

            return this.options?.users?.statuses.map(e => ({code: e.code, label: e.label}));

        }
    },
    watch: {
        "lists.entity.filters.filter_by": function(newValue, oldValue) {

            // this.listEntity({});

        }
    }
};
</script>
