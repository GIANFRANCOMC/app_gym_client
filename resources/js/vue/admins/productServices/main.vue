<template>
    <!-- Breadcrumb route -->
    <h4 class="py-2 mb-4">
        <span class="text-muted fw-light">
            <i class="fa fa-home fa-2xs"></i> /
        </span>
        <span class="text-uppercase">
            PRODUCTOS - SERVICIOS
        </span>
    </h4>

    <!-- Content -->
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <inputText
                v-model="lists.productServices.filters.general"
                :showDiv="true"
                title="Buscar"
                :titleClass="['fw-bold', 'colon-at-end', 'ms-1']"
                placeholder="Ingrese la búsqueda"
                @enter-key-pressed="listProductServices({})">
                <template v-slot:default>
                    <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Nombre, Descripción." role="button"></i>
                </template>
                <template v-slot:inputGroupAppend>
                    <button class="btn btn-primary waves-effect" type="button" @click="listProductServices({})">
                        <i class="fa fa-search"></i>
                    </button>
                </template>
            </inputText>
        </div>
        <div class="align-self-end ms-3">
            <button class="btn btn-primary" data-bs-toggle="modal" :data-bs-target="`#${forms.productServices.add.modals.default}`">
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
                        <th class="align-middle text-center fw-bold col-1">NOMBRE</th>
                        <th class="align-middle text-center fw-bold col-1">DESCRIPCIÓN</th>
                        <th class="align-middle text-center fw-bold col-1">PRECIO</th>
                        <th class="align-middle text-center fw-bold col-1">ESTADO</th>
                        <!-- <th class="align-middle text-center fw-bold col-1">Acciones</th> -->
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <template v-if="lists.productServices.records.data && lists.productServices.records.data.length > 0">
                        <tr v-for="record in lists.productServices.records.data" :key="record.id" class="align-middle">
                            <td class="text-center" v-text="record.name"></td>
                            <td class="text-center" v-text="record.description"></td>
                            <td class="text-center" v-text="record.price"></td>
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
        <template v-if="lists.productServices.records.data && lists.productServices.records.data.length > 0">
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination d-flex flex-wrap">
                    <template v-for="link in lists.productServices.records.links">
                        <li :class="['page-item my-1', link.active ? 'active' : (link.url ? '' : 'disabled')]">
                            <a class="page-link waves-effect me-1" href="javascript:void(0);" @click="listProductServices({url: link.url})" v-html="link.label"></a>
                        </li>
                    </template>
                </ul>
            </nav>
        </template>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.productServices.add.modals.default" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">AGREGAR PRODUCTO - SERVICIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <inputText
                            v-model="forms.productServices.add.data.name"
                            :showDiv="true"
                            title="Nombre"
                            :required="true"
                            :maxlength="60"
                            :showTextBottom="true"
                            :textBottomInfo="forms.productServices.add.errors?.name"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                        <inputText
                            v-model="forms.productServices.add.data.description"
                            :showDiv="true"
                            title="Descripción"
                            :required="true"
                            :maxlength="80"
                            :showTextBottom="true"
                            :textBottomInfo="forms.productServices.add.errors?.description"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputText>
                    </div>
                    <div class="row g-2 mb-3">
                        <inputNumber
                            v-model="forms.productServices.add.data.price"
                            :showDiv="true"
                            title="Precio"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.productServices.add.errors?.price"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputNumber>
                        <inputSelect
                            v-model="forms.productServices.add.data.status"
                            :options="forms.productServices.add.options.status"
                            :showDiv="true"
                            title="Estado"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.productServices.add.errors?.status"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputSelect>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="createProductService()">
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
import { validateVariable } from "../helpers/main.js";

import axios from "axios";
import inputDate from "../componentes/inputDate.vue";
import inputText from "../componentes/inputText.vue";
import inputSelect from "../componentes/inputSelect.vue";
import inputNumber from "../componentes/inputNumber.vue";

export default {
    components: {
        inputDate, inputText, inputSelect, inputNumber
    },
    mounted: async function () {

        document.getElementById("menu-item-product-services").classList.add("active");
        await this.listProductServices({});
        initTooltips();
    },
    data() {
        return {
            lists: {
                productServices: {
                    filters: {
                        general: "",
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                productServices: {
                    add: {
                        modals: {
                            default: "addProductServiceModal"
                        },
                        data: {
                            name: "",
                            description: "",
                            price: "",
                            status: ""
                        },
                        options: {
                            status: [
                                {code: "active", label: "Activo"},
                                {code: "inactive", label: "Inactivo"}
                            ]
                        },
                        errors: {}
                    }
                }
            },
            generalConfiguration: generalConfiguration
        };
    },
    methods: {
        async listProductServices({url = null}) {

            return new Promise(resolve => {

                showLoading({type: "list"});

                let requestUrl    = url || `${requestRoute}/productServices/list`,
                    requestConfig = {};

                let params = {};
                params.general = this.lists.productServices.filters.general;

                requestConfig.params = params;

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    this.lists.productServices.records = response.data;

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
        createProductService() {

            let functionName = "createProductService";

            showLoading({type: "saveForm"});
            this.clearFormErrors({functionName});

            let requestUrl  = `${requestRoute}/productServices`,
                requestData = {};

            requestData.name        = this.forms.productServices.add.data.name;
            requestData.description = this.forms.productServices.add.data.description;
            requestData.price       = this.forms.productServices.add.data.price;
            requestData.status      = this.forms.productServices.add.data.status;

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
                        toastrAlert({code: 422, type: "error"});
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
                case "createProductService":
                    this.forms.productServices.add.data.name        = "";
                    this.forms.productServices.add.data.description = "";
                    this.forms.productServices.add.data.price       = "";
                    this.forms.productServices.add.data.status      = "";
                    break;
            }

        },
        clearFormErrors({functionName}) {

            switch(functionName) {
                case "createProductService":
                    let errorsKeys = Object.keys(this.forms.productServices.add.errors);

                    for(const errorKey of errorsKeys) {

                        this.forms.productServices.add.errors[errorKey] = [];
                    }
                    break;
            }

        },
        setFormErrors({functionName, errors = []}) {

            switch(functionName) {
                case "createProductService":
                    this.forms.productServices.add.errors.name        = errors.name ?? [];
                    this.forms.productServices.add.errors.description = errors.description ?? [];
                    this.forms.productServices.add.errors.price       = errors.price ?? [];
                    this.forms.productServices.add.errors.status      = errors.status ?? [];
                    break;
            }

        },
        // Utils
        validateVariable({value}) {

            return validateVariable({value});

        }
    }
};
</script>
