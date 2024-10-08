<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Sucursales' }]"/>

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
                    <button class="btn btn-primary waves-effect" type="button" @click="listEntity({})" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Nombre.">
                        <i class="fa fa-search"></i>
                    </button>
                </template>
            </InputText>
        </div>
        <div class="align-self-end">
            <button class="btn btn-primary waves-effect ms-3" @click="modalCreateUpdateEntity({})">
                <i class="fa fa-plus"></i>
                <span class="ms-1">Agregar</span>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr class="text-center">
                    <th class="fw-bold col-1">NOMBRE</th>
                    <th class="fw-bold col-1">SERIES</th>
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
                            <td v-text="record.name"></td>
                            <td class="text-start" v-html="record?.series?.map(e => '* '+e?.code+e?.number+' - '+e?.documentType?.name).join('<br/>')"></td>
                            <td>
                                <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect" @click="modalCreateUpdateEntity({record})" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
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

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.default.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.createUpdate.extras.modals.default.titles[isDefined({value: forms.entity.createUpdate.data?.id}) ? 'update' : 'store']"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <InputText
                            v-model="forms.entity.createUpdate.data.name"
                            hasDiv
                            title="Nombre"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.name"
                            xl="6"
                            lg="6"
                            md="12"
                            sm="12"/>
                        <InputSelect
                            v-model="forms.entity.createUpdate.data.status"
                            :options="options?.branches?.statusses"
                            hasDiv
                            title="Estado"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.status"
                            xl="6"
                            lg="6"
                            md="12"
                            sm="12"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', isDefined({value: forms.entity.createUpdate.data?.id}) ? 'btn-warning' : 'btn-primary']" @click="createUpdateEntity()">
                        <i class="fa fa-save"></i>
                        <span class="ms-1">Guardar</span>
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
// import InputDate    from "../../Components/InputDate.vue";
import InputNumber  from "../../Components/InputNumber.vue";
import InputSelect  from "../../Components/InputSelect.vue";
// import InputSelect2 from "../../Components/InputSelect2.vue";
import InputText    from "../../Components/InputText.vue";
import Paginator    from "../../Components/Paginator.vue";

export default {
    components: {
        Breadcrumb,
        // InputDate,
        InputNumber,
        InputSelect,
        // InputSelect2,
        InputText,
        Paginator
    },
    mounted: async function() {

        Utils.openNavbarItem(this.config.entity.page.menu.id, {});
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
                        route: Requests.config({entity: "branches", type: "list"})
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
                                }
                            }
                        },
                        data: {
                            id: null,
                            name: "",
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
                    ...Requests.config({entity: "branches"}),
                    page: {
                        title: "Configuración",
                        active: true,
                        menu: {
                            id: "menu-list-branches"
                        }
                    }
                }
            }
        };
    },
    methods: {
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, showAlert: true});

            this.options.branches = initParams.data?.config?.branches;

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
        modalCreateUpdateEntity({record = null}) {

            const functionName = "modalCreateUpdateEntity";

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                this.forms.entity.createUpdate.data.id          = record?.id;
                this.forms.entity.createUpdate.data.name        = record?.name;
                this.forms.entity.createUpdate.data.status      = record?.status;

            }else {

                //

            }

            // Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.default.id});

        },
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = JSON.parse(JSON.stringify(this.forms.entity.createUpdate.data));

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                let createUpdate = await (this.isDefined({value: this.forms.entity.createUpdate.data.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: this.forms.entity.createUpdate.data.id}) : Requests.post({route: this.config.entity.routes.store, data: form}));

                if(createUpdate?.bool && createUpdate?.data?.bool) {

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
                    this.forms.entity.createUpdate.data.id     = null;
                    this.forms.entity.createUpdate.data.name   = "";
                    this.forms.entity.createUpdate.data.status = "";
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

                result.name   = [];
                result.status = [];

                if(!this.isDefined({value: form?.name})) {

                    result.name.push(this.config.forms.errors.labels.required);
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

        }
    }
};
</script>
