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
                            :divClass="['mt-2']"
                            title="Fecha de venta"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.default?.sale_date"
                            :xl="3"
                            :lg="3"
                            :md="12"
                            :sm="12">
                        </inputDate>
                        <inputSelect2
                            :id="forms.sales.add.select2.branches"
                            :options="forms.sales.add.options.branches"
                            :showDiv="true"
                            :divClass="['mt-2']"
                            title="Sucursal"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.default?.branch_id"
                            :xl="4"
                            :lg="4"
                            :md="12"
                            :sm="12">
                        </inputSelect2>
                        <inputSelect2
                            :id="forms.sales.add.select2.customers"
                            :options="forms.sales.add.options.customers"
                            :showDiv="true"
                            :divClass="['mt-2']"
                            title="Cliente"
                            :required="true"
                            :showTextBottom="true"
                            :textBottomInfo="forms.sales.add.errors?.default?.customer_id"
                            :xl="5"
                            :lg="5"
                            :md="12"
                            :sm="12">
                        </inputSelect2>
                    </div>
                    <div class="table-responsive text-nowrap mt-4">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr class="text-uppercase">
                                    <th class="align-middle text-center fw-bold col-1">DESCRIPCIÓN</th>
                                    <th class="align-middle text-center fw-bold col-1">CANTIDAD</th>
                                    <th class="align-middle text-center fw-bold col-1">PRECIO UNITARIO</th>
                                    <th class="align-middle text-center fw-bold col-1">TOTAL</th>
                                    <th class="align-middle text-center fw-bold col-1"></th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <template v-if="forms.sales.add.data.details && forms.sales.add.data.details.length > 0">
                                    <tr v-for="(record, key) in forms.sales.add.data.details" :key="record.id" class="align-middle">
                                        <td>
                                            <label class="fw-bold" v-text="record?.item?.text"></label>
                                            <div v-if="false">
                                                <small class="fst-italic text-muted">Tipo:</small>
                                                <small class="ms-1" v-text="record?.detail_type?.text"></small>
                                            </div>
                                            <div v-if="['memberships'].includes(record?.detail_type?.id)">
                                                <small class="fst-italic text-muted">Beneficiario:</small>
                                                <small class="ms-1" v-text="record?.beneficiary?.text"></small>
                                            </div>
                                        </td>
                                        <td class="text-center" v-text="record?.quantity"></td>
                                        <td class="text-center" v-text="record?.price"></td>
                                        <td class="text-center" v-text="record?.total"></td>
                                        <td class="text-center">
                                            <button class="btn btn-xs btn-warning waves-effect waves-light">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button class="btn btn-xs btn-danger ms-2 waves-effect waves-light" @click="deleteItemSale({record, key})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="text-center" colspan="99" v-text="generalConfig.messages.withoutResults"></td>
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
                        <i class="fa fa-plus"></i>
                        <span class="ms-2">Agregar ítem</span>
                    </button>
                    <button class="btn btn-success w-100 mb-2 waves-effect waves-light" @click="createSale()">
                        <i class="fa fa-save"></i>
                        <span class="ms-2">Generar venta</span>
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
                                :id="forms.sales.add.select2.detail_types"
                                :options="forms.sales.add.options.detail_types"
                                :showDiv="true"
                                title="Tipo"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detail?.detail_type"
                                :xl="4"
                                :lg="4"
                                :md="12"
                                :sm="12">
                            </inputSelect2>
                            <inputSelect2
                                :id="forms.sales.add.select2.items"
                                :options="forms.sales.add.options.items"
                                :showDiv="true"
                                :title="forms.sales.add.data.detail.detail_type?.text ?? 'Ítem'"
                                :required="true"
                                :disabled="!validateVariable({value: forms.sales.add.data.detail.detail_type?.id})"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detail?.item"
                                :xl="8"
                                :lg="8"
                                :md="12"
                                :sm="12">
                            </inputSelect2>
                        </div>
                        <div class="row g-2 mb-3">
                            <inputNumber
                                v-model="forms.sales.add.data.detail.quantity"
                                :showDiv="true"
                                title="Cantidad"
                                :required="true"
                                :disabled="!validateVariable({value: forms.sales.add.data.detail.item?.id})"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detail?.quantity"
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
                                :disabled="!validateVariable({value: forms.sales.add.data.detail.item?.id})"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detail?.price"
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
                                :disabled="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detail?.total"
                                :xl="4"
                                :lg="4"
                                :md="12"
                                :sm="12">
                            </inputNumber>
                        </div>
                        <div class="row g-2 mb-3" v-show="validateVariable({value: forms.sales.add.data.detail.item?.id}) && ['memberships'].includes(forms.sales.add.data.detail.detail_type?.id)">
                            <inputSelect2
                                :id="forms.sales.add.select2.beneficiaries"
                                :options="forms.sales.add.options.beneficiaries"
                                :showDiv="true"
                                title="Beneficiario"
                                :required="true"
                                :showTextBottom="true"
                                :textBottomInfo="forms.sales.add.errors?.detail?.beneficiary"
                                :xl="12"
                                :lg="12"
                                :md="12"
                                :sm="12">
                            </inputSelect2>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <template v-if="true">
                            <button type="button" class="btn btn-primary" @click="addItemSale()">
                                <i class="fa fa-plus"></i>
                                <span class="ms-1">Agregar</span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";
import * as Constants from "../helpers/constants.js";
import * as Alerts from "../helpers/alerts.js";
import * as Utils from "../helpers/utils.js";

import inputText from "../componentes/inputText.vue";
import inputSelect from "../componentes/inputSelect.vue";
import inputSelect2 from "../componentes/inputSelect2.vue";
import inputDate from "../componentes/inputDate.vue";

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

        tooltips();

    },
    data() {
        return {
            lists: {},
            forms: {
                sales: {
                    add: {
                        modals: {
                            default: "addSaleDetailModal"
                        },
                        select2: {
                            branches:      "branchSelect2",
                            customers:     "customerSelect2",
                            beneficiaries: "beneficiarySelect2",
                            detail_types:  "detailTypesSelect2",
                            items:         "itemsSelect2",
                        },
                        data: {
                            sale_date: "",
                            branch: {},
                            customer: {},
                            details: [],
                            detail: {
                                detail_type: {},
                                item: {},
                                quantity: 0,
                                price: 0,
                                total: 0,
                                beneficiary: {}
                            }
                        },
                        options: {
                            branches: [],
                            customers: [],
                            beneficiaries: [],
                            detail_types: [],
                            memberships: [],
                            product_services: [],
                            items: [],
                            status: []
                        },
                        errors: {
                            default: {},
                            detail: {}
                        }
                    }
                }
            },
            generalConfig: generalConfig
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

                    let branches         = response.data.branches,
                        customers        = response.data.customers,
                        memberships      = response.data.memberships,
                        product_services = response.data.product_services,
                        detail_types     = response.data.detail_types,
                        status           = response.data.status;

                    this.forms.sales.add.options.branches         = branches.map(e => ({code: e.id, label: e.name}));
                    this.forms.sales.add.options.customers        = customers.map(e => ({code: e.id, label: getPersonInfo({person: e}), extras: JSON.stringify(e)}));
                    this.forms.sales.add.options.beneficiaries    = this.forms.sales.add.options.customers;
                    this.forms.sales.add.options.detail_types     = detail_types.map(e => ({code: e.code, label: e.label}));
                    this.forms.sales.add.options.memberships      = memberships.map(e => ({code: e.id, label: e.name, extras: JSON.stringify(e)}));
                    this.forms.sales.add.options.product_services = product_services.map(e => ({code: e.id, label: e.name, extras: JSON.stringify(e)}));
                    this.forms.sales.add.options.status           = status.map(e => ({code: e.code, label: e.label}));

                })
                .catch((error) => {

                    toastrs({subtitle: error, type: "error"});

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

                $(`#${el.forms.sales.add.select2.beneficiaries}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.detail.beneficiary = {id: data?.id, text: data?.text};

                });

                $(`#${el.forms.sales.add.select2.detail_types}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.sales.add.data.detail.detail_type = {id: data?.id, text: data?.text};
                    el.forms.sales.add.data.detail.item        = {};
                    el.forms.sales.add.data.detail.quantity    = 0;
                    el.forms.sales.add.data.detail.price       = 0;
                    el.forms.sales.add.data.detail.total       = 0;
                    el.forms.sales.add.data.detail.beneficiary = el.forms.sales.add.data.customer?.id ? el.forms.sales.add.data.customer : {};
                    el.forms.sales.add.options.items           = el.forms.sales.add.options[data?.id];

                    $(`#${el.forms.sales.add.select2.beneficiaries}`).val(el.forms.sales.add.data.customer?.id ? el.forms.sales.add.data.customer?.id : "").trigger("change");

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

                        toastrs({subtitle: e, type: "error"});

                    }

                });

                resolve(true);

            });

        },
        addItemSale() {

            let detail     = this.forms.sales.add.data.detail,
                validation = {bool: true, messages: {}};

            if(!this.validateVariable({value: detail.detail_type?.id})) {

                validation.bool = false;
                validation.messages.detail_type = ["Es obligatorio."];

            }

            if(!this.validateVariable({value: detail.item?.id})) {

                validation.bool = false;
                validation.messages.item = ["Es obligatorio."];

            }

            if(!this.validateVariable({value: detail.quantity})) {

                validation.bool = false;
                validation.messages.quantity = ["Es obligatorio."];

            }else {

                if(parseFloat(detail.quantity) <= 0) {

                    validation.bool = false;
                    validation.messages.quantity = ["Debe ser mayor a 0."];

                }

            }

            if(!this.validateVariable({value: detail.price})) {

                validation.bool = false;
                validation.messages.price = ["Es obligatorio."];

            }else {

                if(parseFloat(detail.price) <= 0) {

                    validation.bool = false;
                    validation.messages.price = ["Debe ser mayor a 0."];

                }

            }

            if(!this.validateVariable({value: detail.total})) {

                validation.bool = false;
                validation.messages.total = ["Es obligatorio."];

            }else {

                if(parseFloat(detail.total) <= 0) {

                    validation.bool = false;
                    validation.messages.total = ["Debe ser mayor a 0."];

                }

            }

            if(["memberships"].includes(detail.detail_type?.id)) {

                if(!this.validateVariable({value: detail.beneficiary?.id})) {

                    validation.bool = false;
                    validation.messages.beneficiary = ["Es obligatorio."];

                }

            }

            if(validation.bool) {

                (this.forms.sales.add.data.details).push({id: uuidv4(), ...this.forms.sales.add.data.detail});

                this.forms.sales.add.errors.detail = {};
                toastrs({title: `Ítem agregado correctamente.`, type: "success"});

            }else {

                this.forms.sales.add.errors.detail = validation.messages;
                toastrs({code: 422, type: "error"});

            }

        },
        deleteItemSale({record, key}) {

            let el = this;

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect waves-light"
                },
                buttonsStyling: false
            })
            .then(function(result) {

                if(result.value) {

                    (el.forms.sales.add.data.details).splice(key, 1);
                    toastrs({title: `Ítem eliminado correctamente`, type: "success"});

                }

            });


        },
        createSale() {

            let functionName = "createSale";

            swals({type: "saveForm"});
            this.clearFormErrors({functionName});

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
                        toastrs({subtitle: response.data.message, type: "success"});
                        break;
                }

            })
            .catch((error) => {

                switch(error.response.status) {
                    case 403:
                        toastrs({code: error.response.status, type: "error"});
                        break;

                    case 422:
                        this.setFormErrors({functionName, errors: error.response.data.errors});
                        toastrs({code: error.response.status, type: "error"});
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
                    /* this.forms.sales.add.data.name        = "";
                    this.forms.sales.add.data.description = "";
                    this.forms.sales.add.data.price       = "";
                    this.forms.sales.add.data.status      = ""; */
                    break;
            }

        },
        clearFormErrors({functionName}) {

            switch(functionName) {
                case "createSale":
                    let errorsKeys = Object.keys(this.forms.sales.add.errors.default);

                    for(const errorKey of errorsKeys) {

                        this.forms.sales.add.errors.default[errorKey] = [];

                    }
                    break;
            }

        },
        setFormErrors({functionName, errors = []}) {

            switch(functionName) {
                case "createSale":
                    this.forms.sales.add.errors.default.sale_date   = errors.sale_date ?? [];
                    this.forms.sales.add.errors.default.branch_id   = errors.branch_id ?? [];
                    this.forms.sales.add.errors.default.customer_id = errors.customer_id ?? [];
                    this.forms.sales.add.errors.default.details     = errors.details ?? [];
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
