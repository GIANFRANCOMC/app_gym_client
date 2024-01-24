<template>
    <!-- Breadcrumb route -->
    <h4 class="py-2 mb-4">
        <span class="text-muted fw-light">
            <i class="fa fa-home fa-2xs"></i> /
        </span>
        <span class="text-uppercase">
            COLABORADORES
        </span>
    </h4>

    <!-- Content -->
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <inputText
                v-model="lists.admins.filters.general"
                :showDiv="true"
                title="Buscar"
                :titleClass="['fw-bold', 'colon-at-end', 'ms-1']"
                placeholder="Ingrese la búsqueda"
                @enter-key-pressed="listAdmins({})">
                <template v-slot:default>
                    <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Número de documento, Apellidos, Nombres." role="button"></i>
                </template>
                <template v-slot:inputGroupAppend>
                    <button class="btn btn-primary waves-effect" type="button" @click="listAdmins({})">
                        <i class="fa fa-search"></i>
                    </button>
                </template>
            </inputText>
        </div>
        <div class="align-self-end ms-3">
            <button class="btn btn-primary" data-bs-toggle="modal" :data-bs-target="`#${forms.admins.add.modals.default}`">
                <i class="fa fa-plus"></i>
                <span class="ms-1">Agregar</span>
            </button>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th class="align-middle text-center fw-bold col-1">TIPO DE<br/>DOCUMENTO</th>
                        <th class="align-middle text-center fw-bold col-1">NÚMERO DE<br/>DOCUMENTO</th>
                        <th class="align-middle text-center fw-bold col-2">APELLIDOS</th>
                        <th class="align-middle text-center fw-bold col-2">NOMBRES</th>
                        <th class="align-middle text-center fw-bold col-1">CORREO<br/>ELECTRÓNICO</th>
                        <th class="align-middle text-center fw-bold col-1" colspan="2">FECHA DE NACIMIENTO</th>
                        <th class="align-middle text-center fw-bold col-1">GÉNERO</th>
                        <th class="align-middle text-center fw-bold col-1">ESTADO</th>
                        <!-- <th class="align-middle text-center fw-bold col-1">Acciones</th> -->
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <template v-if="lists.admins.records.data && lists.admins.records.data.length > 0">
                        <tr v-for="record in lists.admins.records.data" :key="record.id" class="align-middle">
                            <td class="text-center text-uppercase" v-text="record.formatted_type_document"></td>
                            <td class="text-center" v-text="record.number_document"></td>
                            <td class="text-center" v-text="record.last_name"></td>
                            <td class="text-center" v-text="record.first_name"></td>
                            <td class="text-center" v-text="record?.user?.email"></td>
                            <td class="text-center" v-text="record.formatted_birth_date"></td>
                            <td class="text-start">
                                <span class="badge rounded-pill bg-label-info">{{ record.age }} años</span>
                            </td>
                            <td class="text-center">
                                <i :class="['fa', { 'fa-male text-info': ['male'].includes(record.gender), 'fa-female text-danger': ['female'].includes(record.gender), 'fa-person-half-dress text-warning': ['other'].includes(record.gender) }]"></i>
                                <span v-text="record.formatted_gender" class="text-capitalize ms-1"></span>
                            </td>
                            <td class="text-center">
                                <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </td>
                            <!-- <td class="text-center">
                                <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect waves-light">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </td> -->
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td class="text-center" colspan="99" v-text="generalConfiguration.messages.withoutResults"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <template v-if="lists.admins.records.data && lists.admins.records.data.length > 0">
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination d-flex flex-wrap">
                    <template v-for="link in lists.admins.records.links">
                        <li :class="['page-item my-1', link.active ? 'active' : (link.url ? '' : 'disabled')]">
                            <a class="page-link waves-effect me-1" href="javascript:void(0);" @click="listAdmins({url: link.url})" v-html="link.label"></a>
                        </li>
                    </template>
                </ul>
            </nav>
        </template>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.admins.add.modals.default" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">AGREGAR COLABORADOR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                            <inputSelect
                                v-model="forms.admins.add.data.type_document"
                                :options="forms.admins.add.options.type_document"
                                :showDiv="true"
                                title="Tipo de documento"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.admins.add.errors?.type_document"
                                :xl="6"
                                :lg="6"
                                :md="12"
                                :sm="12">
                            </inputSelect>
                            <inputText
                                v-model="forms.admins.add.data.number_document"
                                :showDiv="true"
                                title="Número de documento"
                                :required="true"
                                :maxlength="25"
                                :showTextBottom="true"
                                :textBottomInfo="forms.admins.add.errors?.number_document"
                                :xl="6"
                                :lg="6"
                                :md="12"
                                :sm="12">
                                <template v-slot:inputGroupAppend>
                                    <button class="btn btn-primary waves-effect"
                                            type="button"
                                            :disabled="!validateVariable({value: forms.admins.add.data.type_document}) || !validateVariable({value: forms.admins.add.data.number_document})"
                                            @click="consultNumberDocument({functionName: 'createAdmin', numberDocument: forms.admins.add.data.number_document, type: forms.admins.add.data.type_document})">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </template>
                            </inputText>
                    </div>
                    <div class="row g-2 mb-3">
                        <inputText
                            v-model="forms.admins.add.data.last_name"
                            :showDiv="true"
                            title="Apellidos"
                            :required="true"
                            :maxlength="60"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.last_name"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                        <inputText
                            v-model="forms.admins.add.data.first_name"
                            :showDiv="true"
                            title="Nombres"
                            :required="true"
                            :maxlength="80"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.first_name"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                    </div>
                    <div class="row g-2 mb-3">
                        <inputText
                            v-model="forms.admins.add.data.email"
                            :showDiv="true"
                            title="Correo electrónico"
                            :required="true"
                            :maxlength="60"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.email"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                        <inputText
                            v-model="forms.admins.add.data.password"
                            :showDiv="true"
                            title="Contraseña"
                            :required="true"
                            :maxlength="80"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.password"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                    </div>
                    <div class="row g-2 mb-3">
                        <inputDate
                            v-model="forms.admins.add.data.birth_date"
                            :showDiv="true"
                            title="Fecha de nacimiento"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.birth_date"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputDate>
                        <inputSelect
                            v-model="forms.admins.add.data.gender"
                            :options="forms.admins.add.options.gender"
                            :showDiv="true"
                            title="Género"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.gender"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputSelect>
                    </div>
                    <div class="row g-2 mb-3">
                        <inputText
                            v-model="forms.admins.add.data.phone"
                            :showDiv="true"
                            title="Celular"
                            :required="true"
                            :maxlength="80"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.phone"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                        <inputSelect
                            v-model="forms.admins.add.data.status"
                            :options="forms.admins.add.options.status"
                            :showDiv="true"
                            title="Estado"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.status"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputSelect>
                    </div>
                    <div class="row g-2 mb-3">
                        <inputSelect2
                            :id="`${forms.admins.add.select2.branches}`"
                            :options="forms.admins.add.options.branches"
                            :multiple="true"
                            :showDiv="true"
                            title="Sucursal"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.admins.add.errors?.branches"
                            :xl="12"
                            :lg="12"
                            :md="12"
                            :sm="12">
                        </inputSelect2>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="createAdmin()">
                        <i class="fa fa-save"></i>
                        <span class="ms-1">Guardar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { requestRoute, generalConfiguration } from "../helpers/constants.js";
import { showLoading, hideSwal, toastrAlert } from "../helpers/alerts.js";
import { initTooltips, hideTooltips } from "../helpers/tooltips.js";
import { validateVariable, consultNumberDocument } from "../helpers/main.js";

import axios from "axios";
import inputDate from "../componentes/inputDate.vue";
import inputText from "../componentes/inputText.vue";
import inputSelect from "../componentes/inputSelect.vue";
import inputSelect2 from "../componentes/inputSelect2.vue";

export default {
    components: {
        inputDate, inputText, inputSelect, inputSelect2
    },
    mounted: async function() {

        document.getElementById("menu-item-admins").classList.add("active");

        await this.initParams({});
        await this.initOthers({});

        await this.listAdmins({});
        initTooltips();

    },
    data() {
        return {
            lists: {
                admins: {
                    filters: {
                        general: "",
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                admins: {
                    add: {
                        modals: {
                            default: "addAdminModal"
                        },
                        select2: {
                            branches: "branchesSelect2"
                        },
                        data: {
                            type_document: "",
                            number_document: "",
                            last_name: "",
                            first_name: "",
                            email: "",
                            password: "",
                            birth_date: "",
                            gender: "",
                            phone: "",
                            status: "",
                            branches: []
                        },
                        options: {
                            type_document: [
                                {code: "dni", label: "DNI"}
                            ],
                            gender: [
                                {code: "male", label: "Masculino"},
                                {code: "female", label: "Femenino"},
                                {code: "other", label: "Otro"}
                            ],
                            status: [
                                {code: "active", label: "Activo"},
                                {code: "inactive", label: "Inactivo"}
                            ],
                            branches: []
                        },
                        errors: {}
                    }
                }
            },
            generalConfiguration: generalConfiguration
        };
    },
    methods: {
        initParams({}) {

            return new Promise(resolve => {

                let requestUrl    = `${requestRoute}/admins/initParams`,
                    requestConfig = {};

                let params = {};

                requestConfig.params = params;

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    let branches = response.data.branches;

                    this.forms.admins.add.options.branches = branches.map(e => ({code: e.id, label: e.name}));

                })
                .catch((error) => {

                    toastrAlert({subtitle: error, type: "error"});

                })
                .finally(() => {

                    resolve(true);

                });

            });

        },
        initOthers({}) {

            return new Promise(resolve => {

                let el = this;

                $(`#${el.forms.admins.add.select2.branches}`).on("select2:select", function(e) {

                    let data = e.params.data;
                    if(((el.forms.admins.add.data.branches).filter(e => e.toString() == (data.id).toString())).length === 0) (el.forms.admins.add.data.branches).push(data.id);

                });

                $(`#${el.forms.admins.add.select2.branches}`).on("select2:unselect", function(e) {

                    let data = e.params.data;
                    el.forms.admins.add.data.branches = (el.forms.admins.add.data.branches).filter(e => e.toString() !== (data.id).toString());

                });

                resolve(true);

            });

        },
        listAdmins({url = null}) {

            return new Promise(resolve => {

                showLoading({type: "list"});

                let requestUrl    = url || `${requestRoute}/admins/list`,
                    requestConfig = {};

                let params = {};
                params.general = this.lists.admins.filters.general;

                requestConfig.params = params;

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    this.lists.admins.records = response.data;

                })
                .catch((error) => {

                    toastrAlert({subtitle: error, type: "error"});

                })
                .finally(() => {

                    hideSwal();
                    initTooltips();

                    resolve(true);

                });

            });

        },
        createAdmin() {

            let functionName = "createAdmin";

            showLoading({type: "saveForm"});
            this.clearFormErrors({functionName});

            let requestUrl  = `${requestRoute}/admins`,
                requestData = {};

            requestData.type_document   = this.forms.admins.add.data.type_document;
            requestData.number_document = this.forms.admins.add.data.number_document;
            requestData.last_name       = this.forms.admins.add.data.last_name;
            requestData.first_name      = this.forms.admins.add.data.first_name;
            requestData.email           = this.forms.admins.add.data.email;
            requestData.password        = this.forms.admins.add.data.password;
            requestData.birth_date      = this.forms.admins.add.data.birth_date;
            requestData.gender          = this.forms.admins.add.data.gender;
            requestData.phone           = this.forms.admins.add.data.phone;
            requestData.status          = this.forms.admins.add.data.status;
            requestData.branches        = this.forms.admins.add.data.branches;

            axios
            .post(requestUrl, requestData)
            .then((response) => {

                switch(response.status) {
                    case 200:
                        this.clearForm({functionName});
                        toastrAlert({subtitle: response.data.message, type: "success"});
                        break;
                }

            })
            .catch((error) => {

                switch(error.response.status) {
                    case 422:
                        this.setFormErrors({functionName, errors: error.response.data.errors});
                        toastrAlert({code: error.response.status, type: "error"});
                        break;
                }

            })
            .finally(() => {

                hideSwal();

            });

        },
        // Forms
        clearForm({functionName}) {

            switch(functionName) {
                case "createAdmin":
                    this.forms.admins.add.data.type_document   = "";
                    this.forms.admins.add.data.number_document = "";
                    this.forms.admins.add.data.last_name       = "";
                    this.forms.admins.add.data.first_name      = "";
                    this.forms.admins.add.data.email           = "";
                    this.forms.admins.add.data.password        = "";
                    this.forms.admins.add.data.birth_date      = "";
                    this.forms.admins.add.data.gender          = "";
                    this.forms.admins.add.data.phone           = "";
                    this.forms.admins.add.data.status          = "";
                    this.forms.admins.add.data.branches        = [];
                    $(`#${this.forms.admins.add.select2.branches}`).val(null).trigger("change");
                    break;
            }

        },
        clearFormErrors({functionName}) {

            switch(functionName) {
                case "createAdmin":
                    let errorsKeys = Object.keys(this.forms.admins.add.errors);

                    for(const errorKey of errorsKeys) {

                        this.forms.admins.add.errors[errorKey] = [];

                    }
                    break;
            }

        },
        setFormErrors({functionName, errors = []}) {

            switch(functionName) {
                case "createAdmin":
                    this.forms.admins.add.errors.type_document   = errors.type_document ?? [];
                    this.forms.admins.add.errors.number_document = errors.number_document ?? [];
                    this.forms.admins.add.errors.last_name       = errors.last_name ?? [];
                    this.forms.admins.add.errors.first_name      = errors.first_name ?? [];
                    this.forms.admins.add.errors.email           = errors.email ?? [];
                    this.forms.admins.add.errors.password        = errors.password ?? [];
                    this.forms.admins.add.errors.birth_date      = errors.birth_date ?? [];
                    this.forms.admins.add.errors.gender          = errors.gender ?? [];
                    this.forms.admins.add.errors.phone           = errors.phone ?? [];
                    this.forms.admins.add.errors.status          = errors.status ?? [];
                    this.forms.admins.add.errors.branches        = errors.branches ?? [];
                    break;
            }

        },
        // Utils
        validateVariable({value}) {

            return validateVariable({value});

        },
        async consultNumberDocument({functionName, numberDocument, type}) {

            switch(functionName) {
                case "createAdmin":
                    showLoading({type: "externalConsult"});

                    let consult = await consultNumberDocument({numberDocument, type});

                    if(consult.bool) {

                        this.forms.admins.add.data.number_document = consult.data?.number_document;
                        this.forms.admins.add.data.last_name       = consult.data?.last_name;
                        this.forms.admins.add.data.first_name      = consult.data?.first_name;

                    }

                    hideSwal();
                    break;
            }

        }
    }
};
</script>
