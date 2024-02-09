<template>
    <div class="row invoice-add">
        <!-- Invoice Add-->
        <div class="col-lg-9 col-12 mb-lg-0 mb-4">
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
                            :xl="3"
                            :lg="3"
                            :md="12"
                            :sm="12">
                        </inputDate>
                        <inputSelect2
                            :id="forms.sales.add.select2.branches"
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
                            :id="forms.sales.add.select2.customers"
                            :options="forms.sales.add.options.customers"
                            :showDiv="true"
                            title="Cliente"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.customer_id"
                            :xl="5"
                            :lg="5"
                            :md="12"
                            :sm="12">
                        </inputSelect2>
                    </div>
                    <hr>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr class="text-uppercase">
                                    <th class="align-middle text-center fw-bold col-1">TIPO</th>
                                    <th class="align-middle text-center fw-bold col-1">PRODUCTO</th>
                                    <th class="align-middle text-center fw-bold col-1">CANTIDAD</th>
                                    <th class="align-middle text-center fw-bold col-1">PRECIO UNITARIO</th>
                                    <th class="align-middle text-center fw-bold col-1">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <template v-if="forms.sales.add.data.details && forms.sales.add.data.details.length > 0">
                                    <tr v-for="(record, key, index) in forms.sales.add.data.details" :key="record.id" class="align-middle">
                                        <td class="text-center" v-text="record?.detailType?.text"></td>
                                        <td class="text-center" v-text="record?.item?.text"></td>
                                        <td class="text-center" v-text="record?.quantity"></td>
                                        <td class="text-center" v-text="record?.price"></td>
                                        <td class="text-center" v-text="record?.total"></td>
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
            </div>
        </div>

        <!-- Invoice Actions -->
        <div class="col-lg-3 col-12 invoice-actions">
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary w-100 mb-2 waves-effect waves-light" data-bs-toggle="modal" :data-bs-target="`#${forms.sales.add.modals.default}`">
                        <span class="d-flex align-items-center justify-content-center text-nowrap">
                            <i class="fa fa-plus me-2"></i> Agregar ítem
                        </span>
                    </button>
                    <button type="button" class="btn btn-success w-100 mb-2 waves-effect waves-light" @click="createSale()">
                        <i class="fa fa-save me-2"></i>
                        <span class="ms-1">Generar venta</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div class="modal fade" :id="forms.sales.add.modals.default" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase">AGREGAR ÍTEM</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 mb-3">
                            <inputSelect2
                                :id="forms.sales.add.select2.detailTypes"
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
                        </div>
                        <div class="row g-2 mb-3" v-show="validateVariable({value: forms.sales.add.data.detail.detailType?.id})">
                            <inputSelect2
                                :id="forms.sales.add.select2.items"
                                :options="forms.sales.add.options.items"
                                :showDiv="true"
                                :title="forms.sales.add.data.detail.detailType?.text"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.items"
                                :xl="12"
                                :lg="12"
                                :md="12"
                                :sm="12">
                            </inputSelect2>
                        </div>
                        <div class="row g-2 mb-3" v-show="validateVariable({value: forms.sales.add.data.detail.item?.id})">
                            <inputNumber
                                v-model="forms.sales.add.data.detail.quantity"
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
                                v-model="forms.sales.add.data.detail.price"
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
                                v-model="forms.sales.add.data.detail.total"
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
                        <button type="button" class="btn btn-primary" @click="addItemSale()">
                            <i class="fa fa-plus"></i>
                            <span class="ms-1">Agregar</span>
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
import { validateVariable, consultNumberDocument, getPersonInfo, uuidv4, getCurrentDate } from "../helpers/main.js";

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

        let menuId = "menu-item-sales";

        document.getElementById(menuId).classList.add("active");
        document.getElementById(menuId).classList.add("open");

        await this.initParams({});
        await this.initOthers({});

        // await this.listSales({});
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
                            branches:    "branchSelect2",
                            customers:   "customerSelect2",
                            detailTypes: "detailTypesSelect2",
                            items:       "itemsSelect2",
                        },
                        data: {
                            sale_date: "",
                            branch: {},
                            customer: {},
                            details: [],
                            detail: {
                                detailType: {},
                                item: {},
                                quantity: 0,
                                price: 0,
                                total: 0
                            }
                        },
                        options: {
                            branches: [],
                            customers: [],
                            memberships: [],
                            productServices: [],
                            detailTypes: [],
                            items: [],
                            status: []
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
                        detailTypes     = response.data.detailTypes,
                        status          = response.data.status;

                    this.forms.sales.add.options.branches        = branches.map(e => ({code: e.id, label: e.name}));
                    this.forms.sales.add.options.customers       = customers.map(e => ({code: e.id, label: getPersonInfo({person: e})}));
                    this.forms.sales.add.options.memberships     = memberships.map(e => ({code: e.id, label: e.name, extras: JSON.stringify(e)}));
                    this.forms.sales.add.options.productServices = productServices.map(e => ({code: e.id, label: e.name, extras: JSON.stringify(e)}));
                    this.forms.sales.add.options.detailTypes     = detailTypes.map(e => ({code: e.code, label: e.label}));
                    this.forms.sales.add.options.status          = status.map(e => ({code: e.code, label: e.label}));

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

                el.forms.sales.add.data.sale_date = getCurrentDate();

                $(`#${el.forms.sales.add.select2.branches}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.branch = {id: data?.id, text: data?.text};

                });

                $(`#${el.forms.sales.add.select2.customers}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.customer = {id: data?.id, text: data?.text};

                });

                $(`#${el.forms.sales.add.select2.detailTypes}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.detail.detailType = {id: data?.id, text: data?.text};
                    el.forms.sales.add.options.items          = el.forms.sales.add.options[data?.id];

                    el.forms.sales.add.data.detail.item     = {};
                    el.forms.sales.add.data.detail.quantity = 0;
                    el.forms.sales.add.data.detail.price    = 0;
                    el.forms.sales.add.data.detail.total    = 0;

                });

                $(`#${el.forms.sales.add.select2.detailTypes}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.detail.detailType = {id: data?.id, text: data?.text};
                    el.forms.sales.add.options.items          = el.forms.sales.add.options[data?.id];

                    el.forms.sales.add.data.detail.item     = {};
                    el.forms.sales.add.data.detail.quantity = 0;
                    el.forms.sales.add.data.detail.price    = 0;
                    el.forms.sales.add.data.detail.total    = 0;

                });

                $(`#${el.forms.sales.add.select2.items}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.detail.item = {id: data?.id, text: data?.text};

                    try {

                        let extrasDOM  = data.element.getAttribute("extras"),
                            extrasJSON = JSON.parse(extrasDOM);

                        el.forms.sales.add.data.detail.quantity = 1;
                        el.forms.sales.add.data.detail.price    = extrasJSON?.price ?? 0;
                        el.forms.sales.add.data.detail.total    = (el.forms.sales.add.data.detail.quantity * el.forms.sales.add.data.detail.price);

                    }catch(e) {

                        toastrAlert({subtitle: e, type: "error"});

                    }

                });

                resolve(true);

            });

        },
        addItemSale() {

            (this.forms.sales.add.data.details).push({id: uuidv4(), ...this.forms.sales.add.data.detail});
            toastrAlert({type: "success"});

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
            //this.clearFormErrors({functionName});

            let requestUrl  = `${requestRoute}/sales`,
                requestData = {};

            requestData.sale_date   = this.forms.sales.add.data.sale_date;
            requestData.branch_id   = this.forms.sales.add.data.branch?.id;
            requestData.customer_id = this.forms.sales.add.data.customer?.id;
            requestData.details     = this.forms.sales.add.data.details;

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
                    case 403:
                        toastrAlert({code: error.response.status, type: "error"});
                        break;

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
