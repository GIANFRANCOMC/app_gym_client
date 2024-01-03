<template>
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <div class="fw-bold">
                <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Número de documento, Nombres, Apellidos." role="button"></i>
                <span class="ms-1">Buscar:</span>
            </div>
            <div class="input-group">
                <inputText
                    v-model="lists.customers.filters.general"
                    @enter-key-pressed="listCustomers()"
                    placeholder="Ingrese la búsqueda">
                </inputText>
                <button class="btn btn-primary waves-effect" type="button" @click="listCustomers()">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div class="align-self-end ms-3">
            <button class="btn btn-primary" data-bs-toggle="modal" :data-bs-target="`#${forms.customers.add.modals.default}`">
                <i class="fa fa-plus"></i>
                <span class="ms-1">Agregar</span>
            </button>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr class="text-nowrap">
                        <th class="align-middle text-center fw-bold col-1">Número de<br/>documento</th>
                        <th class="align-middle text-center fw-bold col-2">Apellidos</th>
                        <th class="align-middle text-center fw-bold col-2">Nombres</th>
                        <th class="align-middle text-center fw-bold col-1" colspan="2">Fecha de Nacimiento</th>
                        <th class="align-middle text-center fw-bold col-1">Estado</th>
                        <th class="align-middle text-center fw-bold col-1">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr v-for="record in lists.customers.records.data" :key="record.id" class="align-middle">
                        <td class="text-center" v-text="record.number_document"></td>
                        <td class="text-center" v-text="record.first_name"></td>
                        <td class="text-center" v-text="record.last_name"></td>
                        <td class="text-center">
                            <span v-text="record.formatted_birth_date"></span>
                        </td>
                        <td class="text-start">
                            <span class="badge rounded-pill bg-label-info">{{ record.age }} años</span>
                        </td>
                        <td class="text-center">
                            <span :class="['badge', 'text-capitalize', { 'bg-label-primary': record.status === 'active', 'bg-label-danger': record.status === 'inactive' }]" v-text="record.formatted_status"></span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm rounded-pill btn-danger waves-effect waves-light ms-1">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <template v-if="lists.customers.records.data && lists.customers.records.data.length > 0">
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination">
                    <template v-for="link in lists.customers.records.links">
                        <li :class="['page-item', link.active ? 'active' : (link.url ? '' : 'disabled')]">
                            <a class="page-link waves-effect me-1" href="javascript:void(0);" @click="listCustomers(link.url)" v-html="link.label"></a>
                        </li>
                    </template>
                </ul>
            </nav>
        </template>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="forms.customers.add.modals.default" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Agregar cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <inputText
                                v-model="forms.customers.add.data.number_document"
                                :showDiv="true"
                                title="Número de documento"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.customers.add.errors?.number_document">
                            </inputText>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <inputText
                                v-model="forms.customers.add.data.last_name"
                                :showDiv="true"
                                title="Apellidos"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.customers.add.errors?.last_name">
                            </inputText>
                        </div>
                        <div class="col">
                            <inputText
                                v-model="forms.customers.add.data.first_name"
                                :showDiv="true"
                                title="Nombres"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.customers.add.errors?.first_name">
                            </inputText>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <inputText
                                v-model="forms.customers.add.data.birth_date"
                                :showDiv="true"
                                title="Fecha de nacimiento"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.customers.add.errors?.birth_date">
                            </inputText>
                        </div>
                        <div class="col">
                            <inputText
                                v-model="forms.customers.add.data.status"
                                :showDiv="true"
                                title="Estado"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.customers.add.errors?.status">
                            </inputText>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" @click="createCustomer()">
                        <i class="fa fa-save"></i>
                        <span class="ms-1">Guardar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { requestRoute } from "../helpers/constants.js";
import { showLoading, hideSwal, successSwal, errorSwal } from "../helpers/sweetalert2.js";
import { initTooltips, hideTooltips } from "../helpers/tooltips.js";
import {  } from "../helpers/utils.js";

import axios from "axios";
import inputText from "../componentes/inputText.vue";

export default {
    components: {
        inputText
    },
    mounted: async function () {

        await this.listCustomers();
        initTooltips();

    },
    data() {
        return {
            lists: {
                customers: {
                    filters: {
                        general: "",
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                customers: {
                    add: {
                        modals: {
                            default: "addCustomerModal"
                        },
                        data: {
                            number_document: "",
                            last_name: "",
                            first_name: "",
                            birth_date: "",
                            status: ""
                        },
                        errors: {

                        }
                    }
                }
            }
        };
    },
    methods: {
        async listCustomers(url) {

            return new Promise(resolve => {

                showLoading({type: "list"});

                let params = {};
                params.general = this.lists.customers.filters.general;

                let requestUrl    = url || `${requestRoute}/customers/list`,
                    requestConfig = { params: params };

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    this.lists.customers.records = response.data;

                })
                .catch((error) => {

                    //

                })
                .finally(function () {

                    hideSwal();
                    initTooltips();
                    resolve(true);

                });

            });

        },
        createCustomer() {

            let functionName = "createCustomer";

            showLoading({type: "saveForm"});
            this.clearFormErrors({functionName});

            let requestUrl  = `${requestRoute}/customers`,
                requestData = {};

            requestData.number_document = this.forms.customers.add.data.number_document;
            requestData.last_name       = this.forms.customers.add.data.last_name;
            requestData.first_name      = this.forms.customers.add.data.first_name;
            requestData.birth_date      = this.forms.customers.add.data.birth_date;
            requestData.status          = this.forms.customers.add.data.status;

            axios
            .post(requestUrl, requestData)
            .then((response) => {

                if(response.status === 200) {

                    this.clearForm({functionName});
                    successSwal({});

                }

            })
            .catch((error) => {

                switch(error.response.status) {
                    case 422:
                        this.setErrors({functionName, errors: error.response.data.errors});
                        break;
                }

                //errorSwal({type: error.response.status, title: "Error xd"});
                hideSwal();

            })
            .finally(() => {

                //

            });

        },
        // Utilities
        clearForm({functionName}) {

            switch(functionName) {
                case "createCustomer":
                    this.forms.customers.add.data.number_document = "";
                    this.forms.customers.add.data.last_name       = "";
                    this.forms.customers.add.data.first_name      = "";
                    this.forms.customers.add.data.birth_date      = "";
                    this.forms.customers.add.data.status          = "";
                    break;
            }

        },
        clearFormErrors({functionName}) {

            switch(functionName) {
                case "createCustomer":
                    let propiedadesErrors = Object.keys(this.forms.customers.add.errors);

                    for(const error of propiedadesErrors) {

                        this.forms.customers.add.errors[error] = [];
                    }
                    break;
            }

        },
        setErrors({functionName, errors}) {

            switch(functionName) {
                case "createCustomer":
                    this.forms.customers.add.errors.number_document = errors.number_document ?? [];
                    this.forms.customers.add.errors.last_name       = errors.last_name ?? [];
                    this.forms.customers.add.errors.first_name      = errors.first_name ?? [];
                    this.forms.customers.add.errors.birth_date      = errors.birth_date ?? [];
                    this.forms.customers.add.errors.status          = errors.status ?? [];
                    break;
            }

        }
    }
};
</script>
