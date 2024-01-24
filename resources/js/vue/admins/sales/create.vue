<template>
    <div class="row invoice-add">
        <!-- Invoice Add-->
        <div class="col-lg-10 col-12 mb-lg-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row">
                        <inputDate
                            v-model="forms.sales.add.data.sale_date"
                            :showDiv="true"
                            title="Fecha de venta"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.sale_date"
                            :xl="2"
                            :lg="2"
                            :md="12"
                            :sm="12">
                        </inputDate>
                        <inputSelect2
                            :id="`${forms.sales.add.select2.branch_id}`"
                            :options="forms.sales.add.options.branches"
                            :showDiv="true"
                            title="Sucursal"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.branch_id"
                            :xl="4"
                            :lg="4"
                            :md="12"
                            :sm="12">
                        </inputSelect2>
                        <inputSelect2
                            :id="`${forms.sales.add.select2.customer_id}`"
                            :options="forms.sales.add.options.customers"
                            :showDiv="true"
                            title="Cliente"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.customer_id"
                            :xl="6"
                            :lg="6"
                            :md="12"
                            :sm="12">
                        </inputSelect2>
                    </div>
                    <hr>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr class="text-uppercase">
                                    <th class="align-middle text-center fw-bold col-1">PRODUCTO</th>
                                    <th class="align-middle text-center fw-bold col-1">CANTIDAD</th>
                                    <th class="align-middle text-center fw-bold col-1">PRECIO UNITARIO</th>
                                    <th class="align-middle text-center fw-bold col-1">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <template v-if="forms.sales.add.data.details && forms.sales.add.data.details.length > 0">
                                    <tr v-for="(record, key, index) in forms.sales.add.data.details" :key="record.id" class="align-middle">
                                        <!-- <td>
                                            <inputSelect2
                                                :id="`${forms.sales.add.select2.customers}`"
                                                :options="forms.sales.add.options.customers"
                                                :showTextBottom="true"
                                                :textBottomInfo="forms.sales.add.errors?.customers">
                                            </inputSelect2>
                                        </td>
                                        <td>
                                            <inputText
                                                v-model="forms.sales.add.data.phone"
                                                :showDiv="false"
                                                :maxlength="80"
                                                :showTextBottom="true"
                                                :textBottomInfo="forms.sales.add.errors?.phone">
                                                <template v-slot:inputGroupAppend>
                                                    <button class="btn btn-primary waves-effect" type="button">
                                                        UND
                                                    </button>
                                                </template>
                                            </inputText>
                                        </td>
                                        <td>
                                            <inputText
                                                v-model="forms.sales.add.data.phone"
                                                :maxlength="80"
                                                :showTextBottom="true"
                                                :textBottomInfo="forms.sales.add.errors?.phone">
                                            </inputText>
                                        </td>
                                        <td>
                                            <inputText
                                                v-model="forms.sales.add.data.phone"
                                                :maxlength="80"
                                                :showTextBottom="true"
                                                :textBottomInfo="forms.sales.add.errors?.phone">
                                            </inputText>
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
                    <div class="row mt-3">
                        <button class="btn btn-primary waves-effect" data-bs-toggle="modal" :data-bs-target="`#${forms.sales.add.modals.default}`">
                            <i class="fa fa-plus"></i> Agregar producto
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Actions -->
        <div class="col-lg-2 col-12 invoice-actions">
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary d-grid w-100 mb-2 waves-effect waves-light" data-bs-toggle="offcanvas" data-bs-target="#sendInvoiceOffcanvas">
                    <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="ti ti-send ti-xs me-2"></i>Send Invoice</span>
                    </button>
                    <a href="./app-invoice-preview.html" class="btn btn-label-secondary d-grid w-100 mb-2 waves-effect">Preview</a>
                    <button type="button" class="btn btn-label-secondary d-grid w-100 waves-effect">Save</button>
                </div>
            </div>
            <div>
                <p class="mb-2">Accept payments via</p>
                <select class="form-select mb-4">
                    <option value="Bank Account">Bank Account</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Card">Credit/Debit Card</option>
                    <option value="UPI Transfer">UPI Transfer</option>
                </select>
                <div class="d-flex justify-content-between mb-2">
                    <label for="payment-terms" class="mb-0">Payment Terms</label>
                    <label class="switch switch-primary me-0">
                    <input type="checkbox" class="switch-input" id="payment-terms" checked="">
                    <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                    </span>
                    <span class="switch-label"></span>
                    </label>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <label for="client-notes" class="mb-0">Client Notes</label>
                    <label class="switch switch-primary me-0">
                    <input type="checkbox" class="switch-input" id="client-notes">
                    <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                    </span>
                    <span class="switch-label"></span>
                    </label>
                </div>
                <div class="d-flex justify-content-between">
                    <label for="payment-stub" class="mb-0">Payment Stub</label>
                    <label class="switch switch-primary me-0">
                    <input type="checkbox" class="switch-input" id="payment-stub">
                    <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                    </span>
                    <span class="switch-label"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div class="modal fade" :id="forms.sales.add.modals.default" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase">AGREGAR DETALLE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 mb-3">
                            <inputSelect2
                                :id="`${forms.sales.add.select2.detailTypes}`"
                                :options="forms.sales.add.options.detailTypes"
                                :showDiv="true"
                                title="Tipo"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detailTypes"
                                :xl="4"
                                :lg="4"
                                :md="12"
                                :sm="12">
                            </inputSelect2>
                            <inputSelect2
                                :id="`${forms.sales.add.select2.items}`"
                                :options="forms.sales.add.options.productServices"
                                :showDiv="true"
                                title="Producto - Servicio"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.productServices"
                                :xl="8"
                                :lg="8"
                                :md="12"
                                :sm="12">
                            </inputSelect2>
                        </div>
                        <div class="row g-2 mb-3">
                            <inputNumber
                                v-model="forms.sales.add.data.quantity"
                                :showDiv="true"
                                title="Cantidad"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.quantity"
                                :xl="4"
                                :lg="4"
                                :md="12"
                                :sm="12">
                            </inputNumber>
                            <inputNumber
                                v-model="forms.sales.add.data.price"
                                :showDiv="true"
                                title="Precio unitario"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.price"
                                :xl="4"
                                :lg="4"
                                :md="12"
                                :sm="12">
                            </inputNumber>
                            <inputNumber
                                v-model="forms.sales.add.data.total"
                                :showDiv="true"
                                title="Total"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.total"
                                :xl="4"
                                :lg="4"
                                :md="12"
                                :sm="12">
                            </inputNumber>
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

    </div>
</template>

<script>
import { requestRoute, generalConfiguration } from "../helpers/constants.js";
import { showLoading, hideSwal, toastrAlert } from "../helpers/alerts.js";
import { initTooltips, hideTooltips } from "../helpers/tooltips.js";
import { validateVariable, consultNumberDocument, getPersonInfo } from "../helpers/main.js";

import axios from "axios";
import inputDate from "../componentes/inputDate.vue";
import inputText from "../componentes/inputText.vue";
import inputSelect from "../componentes/inputSelect.vue";
import inputSelect2 from "../componentes/inputSelect2.vue";
import inputNumber from "../componentes/inputNumber.vue";

export default {
    components: {
        inputDate, inputText, inputSelect, inputSelect2, inputNumber
    },
    mounted: async function () {

        document.getElementById("menu-item-sales").classList.add("active");
        document.getElementById("menu-item-sales").classList.add("open");

        await this.initParams({});
        await this.initOthers({});

        await this.listSales({});
        initTooltips();

    },
    data() {
        return {
            lists: {
                sales: {
                    filters: {
                        general: "",
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                sales: {
                    add: {
                        modals: {
                            default: "addSaleDetailModal"
                        },
                        select2: {
                            branch_id: 'branchSelect2',
                            customer_id: 'customerSelect2',
                            detailTypes: 'detailTypesSelect2',
                            items: 'itemsSelect2',
                        },
                        data: {
                            sale_date: "",
                            branch_id: "",
                            customer_id: "",
                            details: [],
                            detail: {
                                detailType: {},
                                item: {},
                                quantity: "",
                                price: "",
                                total: ""
                            }
                        },
                        options: {
                            branches: [],
                            customers: [],
                            memberships: [],
                            productServices: [],
                            detailTypes: [],
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
        initParams({}) {

            return new Promise(resolve => {

                let requestUrl    = `${requestRoute}/sales/initParams`,
                    requestConfig = {};

                let params = {};

                requestConfig.params = params;

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    let branches        = response.data.branches,
                        customers       = response.data.customers,
                        memberships     = response.data.memberships,
                        productServices = response.data.productServices,
                        detailTypes     = response.data.detailTypes;

                    this.forms.sales.add.options.branches        = branches.map(e => ({code: e.id, label: e.name}));
                    this.forms.sales.add.options.customers       = customers.map(e => ({code: e.id, label: getPersonInfo({person: e})}));
                    this.forms.sales.add.options.memberships     = memberships.map(e => ({code: e.id, label: e.name}));
                    this.forms.sales.add.options.productServices = productServices.map(e => ({code: e.id, label: e.name, extras: JSON.stringify(e)}));
                    this.forms.sales.add.options.detailTypes     = detailTypes.map(e => ({code: e.code, label: e.label}));

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

                $(`#${el.forms.sales.add.select2.items}`).on("select2:select", function(e) {

                    let data = e.params.data;
                    console.log(data);

                });

                resolve(true);

            });

        },
        listSales({url = null}) {

            return new Promise(resolve => {

                showLoading({type: "list"});

                let requestUrl    = url || `${requestRoute}/sales/list`,
                    requestConfig = {};

                let params = {};
                params.general = this.lists.sales.filters.general;

                requestConfig.params = params;

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    this.lists.sales.records = response.data;

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
        createSale() {

            let functionName = "createSale";

            showLoading({type: "saveForm"});
            this.clearFormErrors({functionName});

            let requestUrl  = `${requestRoute}/sales`,
                requestData = {};

            requestData.name        = this.forms.sales.add.data.name;
            requestData.description = this.forms.sales.add.data.description;
            requestData.price       = this.forms.sales.add.data.price;
            requestData.status      = this.forms.sales.add.data.status;

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
                case "createSale":
                    this.forms.sales.add.data.name        = "";
                    this.forms.sales.add.data.description = "";
                    this.forms.sales.add.data.price       = "";
                    this.forms.sales.add.data.status      = "";
                    break;
            }

        },
        clearFormErrors({functionName}) {

            switch(functionName) {
                case "createSale":
                    let errorsKeys = Object.keys(this.forms.sales.add.errors);

                    for(const errorKey of errorsKeys) {

                        this.forms.sales.add.errors[errorKey] = [];

                    }
                    break;
            }

        },
        setFormErrors({functionName, errors = []}) {

            switch(functionName) {
                case "createSale":
                    this.forms.sales.add.errors.name        = errors.name ?? [];
                    this.forms.sales.add.errors.description = errors.description ?? [];
                    this.forms.sales.add.errors.price       = errors.price ?? [];
                    this.forms.sales.add.errors.status      = errors.status ?? [];
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
