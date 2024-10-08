<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Crear' }]"/>

    <div class="row invoice-add">
        <!-- Invoice Add-->
        <div class="col-lg-9 col-12 mb-lg-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <InputSelect2
                            :id="forms.entity.createUpdate.extras.select2.branch"
                            :options="options?.branches?.records.map(e=>({code: e.id, label: e.name}))"
                            hasDiv
                            title="Sucursal"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.holder_id"
                            xl="5"
                            lg="5"
                            md="12"
                            sm="12"/>
                        <InputSelect2
                            :id="forms.entity.createUpdate.extras.select2.serie"
                            :options="series"
                            hasDiv
                            title="Tipo de comprobante"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.serie_id"
                            xl="4"
                            lg="4"
                            md="12"
                            sm="12"/>
                        <InputDate
                            v-model="forms.entity.createUpdate.data.sale_date"
                            hasDiv
                            title="Fecha de venta"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.sale_date"
                            xl="3"
                            lg="3"
                            md="12"
                            sm="12"/>
                    </div>
                    <div class="row g-3 mb-3">
                        <InputSelect2
                            :id="forms.entity.createUpdate.extras.select2.holder"
                            :options="options?.holders?.records.map(e=>({code: e.id, label: e.name}))"
                            hasDiv
                            title="Cliente"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.holder_id"
                            xl="9"
                            lg="9"
                            md="12"
                            sm="12"/>
                        <InputSelect2
                            :id="forms.entity.createUpdate.extras.select2.currency"
                            :options="options?.currencies?.records.map(e=>({code: e.id, label: e.plural_name+' ('+e.sign+')', extras: JSON.stringify({sign: e.sign})}))"
                            hasDiv
                            title="Moneda"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.currency_id"
                            xl="3"
                            lg="3"
                            md="12"
                            sm="12"/>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th class="fw-bold col-1">DESCRIPCIÓN</th>
                                    <th class="fw-bold col-1">CANTIDAD</th>
                                    <th class="fw-bold col-1">PRECIO UNITARIO</th>
                                    <th class="fw-bold col-1">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 bg-white">
                                <template v-if="(forms.entity.createUpdate.data.details).length > 0">
                                    <tr v-for="record in forms.entity.createUpdate.data.details" :key="record.id" class="text-center">
                                        <td class="text-start fw-bold" v-text="record.name"></td>
                                        <td>
                                            <InputNumber
                                                v-model="record.quantity"
                                                @input="calculateRecordDetail({record})"/>
                                        </td>
                                        <td>
                                            <InputNumber
                                                v-model="record.price"
                                                @input="calculateRecordDetail({record})">
                                                <template v-slot:inputGroupPrepend v-if="isDefined({value: record?.currency})">
                                                    <button class="btn btn-primary waves-effect" type="button" v-text="record?.currency?.sign"></button>
                                                </template>
                                            </InputNumber>
                                        </td>
                                        <td v-text="(forms.entity.createUpdate.data.currency?.sign ?? '')+' '+record.total"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="fw-bold text-end">TOTAL:</td>
                                        <td colspan="1" class="fw-bold text-center" v-text="(forms.entity.createUpdate.data.currency?.sign ?? '')+' '+forms.entity.createUpdate.data.total"></td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td class="text-center" colspan="99" v-text="config.messages.withoutResults"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <small :class="config.forms.errors.styles.default" v-text="isDefined({value: forms.entity.createUpdate.errors?.details}) ? forms.entity.createUpdate.errors?.details[0] : ''"></small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-12 invoice-actions">
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary w-100 my-1 waves-effect" @click="modalAddDetail({})">
                        <i class="fa fa-plus"></i>
                        <span class="ms-2">Agregar ítem</span>
                    </button>
                    <button class="btn btn-success w-100 my-1 waves-effect" @click="createUpdateEntity()">
                        <i class="fa-solid fa-cash-register"></i>
                        <span class="ms-2">Generar venta</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.details.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.createUpdate.extras.modals.details.titles[isDefined({value: forms.entity.createUpdate.data?.id}) ? 'update' : 'store']"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <InputSelect2
                            :id="forms.entity.createUpdate.extras.modals.details.select2.item"
                            :options="options?.items?.records.map(e=>({code: e.id, label: e.name}))"
                            hasDiv
                            title="Producto"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.item_id"
                            xl="12"
                            lg="12"
                            md="12"
                            sm="12"/>
                    </div>
                    <div class="row g-3 mb-3">
                        <InputNumber
                            v-model="forms.entity.createUpdate.extras.modals.details.data.quantity"
                            @input="calculateAddDetail()"
                            hasDiv
                            title="Cantidad"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.quantity"
                            xl="4"
                            lg="4"
                            md="12"
                            sm="12"/>
                        <InputNumber
                            v-model="forms.entity.createUpdate.extras.modals.details.data.price"
                            @input="calculateAddDetail()"
                            hasDiv
                            title="Precio"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.price"
                            xl="4"
                            lg="4"
                            md="12"
                            sm="12">
                            <template v-slot:inputGroupPrepend v-if="isDefined({value: forms.entity.createUpdate.extras.modals.details.data.item?.currency})">
                                <button class="btn btn-primary waves-effect" type="button" v-text="forms.entity.createUpdate.extras.modals.details.data.item?.currency?.sign"></button>
                            </template>
                        </InputNumber>
                        <InputNumber
                            v-model="forms.entity.createUpdate.extras.modals.details.data.total"
                            hasDiv
                            title="Total"
                            isRequired
                            disabled
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.total"
                            xl="4"
                            lg="4"
                            md="12"
                            sm="12">
                            <template v-slot:inputGroupPrepend v-if="isDefined({value: forms.entity.createUpdate.data.currency})">
                                <button class="btn btn-primary waves-effect" type="button" v-text="forms.entity.createUpdate.data.currency?.sign"></button>
                            </template>
                        </InputNumber>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', isDefined({value: forms.entity.createUpdate.data?.id}) ? 'btn-warning' : 'btn-primary']" @click="addDetail()">
                        <i class="fa fa-save"></i>
                        <span class="ms-1">Guardar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.finished.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-1 mb-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center">
                            <i :class="['fa fa-check-circle fs-4', forms.entity.createUpdate.extras.modals.finished.titles.bool ? 'text-success' : 'text-danger']"></i>
                            <span class="fw-bold text-uppercase fs-4 ms-2" v-text="forms.entity.createUpdate.extras.modals.finished.titles.header"></span>
                        </div>
                    </div>
                    <template v-if="forms.entity.createUpdate.extras.modals.finished.titles.bool">
                        <div class="row g-1">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="text-center cursor-pointer p-1" @click="exportpp({})">
                                    <div class="badge bg-primary p-3 rounded mb-3">
                                        <i class="fa fa-print fs-3"></i>
                                    </div>
                                    <h5 class="fw-semibold">A4</h5>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="text-center cursor-pointer p-1">
                                    <div class="badge bg-primary p-3 rounded mb-3">
                                        <i class="fa-solid fa-note-sticky fs-3"></i>
                                    </div>
                                    <h5 class="fw-semibold">Ticket</h5>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="text-center cursor-pointer p-1">
                                    <div class="badge bg-success p-3 rounded mb-3">
                                        <i class="fa-solid fa-cash-register fs-3"></i>
                                    </div>
                                    <h5 class="fw-semibold">Nueva venta</h5>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                                <div class="text-center cursor-pointer p-1">
                                    <div class="badge bg-secondary p-3 rounded mb-3">
                                        <i class="fa fa-list fs-3"></i>
                                    </div>
                                    <h5 class="fw-semibold">Ir al listado</h5>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="modal-footer">

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
import InputDate    from "../../Components/InputDate.vue";
import InputNumber  from "../../Components/InputNumber.vue";
import InputSelect  from "../../Components/InputSelect.vue";
import InputSelect2 from "../../Components/InputSelect2.vue";
import InputText    from "../../Components/InputText.vue";
import Paginator    from "../../Components/Paginator.vue";

export default {
    components: {
        Breadcrumb,
        InputDate,
        InputNumber,
        InputSelect,
        InputSelect2,
        InputText,
        Paginator
    },
    mounted: async function() {

        Utils.openNavbarItem(this.config.entity.page.menu.id, {addClass: "open"});
        Utils.openNavbarItem("menu-item-create-sales", {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "sales", type: "list"})
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
                                },
                                details: {
                                    id: "addDetailModal",
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar"
                                    },
                                    select2: {
                                        item: "itemSelect2"
                                    },
                                    data: {
                                        item_id: "",
                                        item: null,
                                        quantity: 1,
                                        price: 0,
                                        total: 0
                                    },
                                    errors: {}
                                },
                                finished: {
                                    id: "finishedModal",
                                    titles: {
                                        header: "",
                                        bool: false
                                    },
                                    data: {
                                        id: ""
                                    }
                                }
                            },
                            select2: {
                                branch: "branchSelect2",
                                currency: "currencySelect2",
                                holder: "holderSelect2",
                                serie: "serieSelect2"
                            }
                        },
                        data: {
                            id: "",
                            branch_id: "",
                            branch: null,
                            serie_id: "",
                            serie: null,
                            currency_id: "",
                            currency: null,
                            holder_id: "",
                            holder: null,
                            sale_date: "",
                            status: "",
                            details: [],
                            total: 0
                        },
                        errors: {
                            details: []
                        }
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "sales"}),
                    page: {
                        title: "Ventas",
                        active: true,
                        menu: {
                            id: "menu-item-sales"
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
            this.options.currencies = initParams.data?.config?.currencies;
            this.options.sales    = initParams.data?.config?.sales;
            this.options.holders  = initParams.data?.config?.customers;
            this.options.items    = initParams.data?.config?.items;

            return initParams?.bool && initParams?.data?.bool;

        },
        async initOthers({}) {

            return new Promise(resolve => {

                let el = this;

                this.forms.entity.createUpdate.data.sale_date = Utils.getCurrentDate();

                $(`#${el.forms.entity.createUpdate.extras.select2.branch}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.entity.createUpdate.data.branch_id = data?.id;
                    el.forms.entity.createUpdate.data.branch    = {id: data?.id, text: data?.text};

                });

                $(`#${el.forms.entity.createUpdate.extras.select2.currency}`).on("select2:select", function(e) {

                    let data = e.params.data;console.log(12);

                    el.forms.entity.createUpdate.data.currency_id = data?.id;
                    el.forms.entity.createUpdate.data.currency    = {id: data?.id, text: data?.text, ...JSON.parse(data.element.getAttribute("extras"))};

                });

                $(`#${el.forms.entity.createUpdate.extras.select2.holder}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.entity.createUpdate.data.holder_id = data?.id;
                    el.forms.entity.createUpdate.data.holder    = {id: data?.id, text: data?.text};

                });

                $(`#${el.forms.entity.createUpdate.extras.select2.serie}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    el.forms.entity.createUpdate.data.serie_id = data?.id;
                    el.forms.entity.createUpdate.data.serie    = {id: data?.id, text: data?.text};

                });

                $(`#${el.forms.entity.createUpdate.extras.modals.details.select2.item}`).on("select2:select", function(e) {

                    let data = e.params.data;

                    const filter = (el.options.items?.records).filter(e => e?.id == data?.id);

                    if(filter.length === 1) {

                        let detail = filter[0];

                        el.forms.entity.createUpdate.extras.modals.details.data.item_id = detail?.id;
                        el.forms.entity.createUpdate.extras.modals.details.data.item    = {...detail, text: data?.text};
                        el.forms.entity.createUpdate.extras.modals.details.data.price   = el.fixedNumber(detail?.price);

                        el.calculateAddDetail();

                    }else {

                        el.forms.entity.createUpdate.extras.modals.details.data.item_id = null;
                        el.forms.entity.createUpdate.extras.modals.details.data.item    = null;

                        Alerts.toastrs({type: "error", subtitle: "El producto seleccionado no es válido."});

                    }

                });

                // $(`#${el.forms.entity.createUpdate.extras.select2.currency}`).val("1").trigger("change.select2");

                resolve(true);

            });

        },
        async listEntity({url = null}) {

            //

        },
        // Form details
        calculateRecordDetail({record = null}) {

            record.quantity = this.fixedNumber(record?.quantity);
            record.price    = this.fixedNumber(record?.price);
            record.total    = this.calculateTotal({item: record});

            this.calculateTotalDetail();

        },
        calculateTotalDetail() {

            let total = 0;

            for(let detail of this.forms.entity.createUpdate.data.details) {

                total += Number(detail?.total);

            }

            this.forms.entity.createUpdate.data.total = this.fixedNumber(total);

        },
        modalAddDetail({}) {

            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.details.id});

        },
        calculateAddDetail() {

            const detailModal = this.forms.entity.createUpdate.extras.modals.details.data;

            this.forms.entity.createUpdate.extras.modals.details.data.quantity = Number(detailModal?.quantity);
            this.forms.entity.createUpdate.extras.modals.details.data.price    = Number(detailModal?.price);
            this.forms.entity.createUpdate.extras.modals.details.data.total    = this.calculateTotal({item: detailModal});

        },
        addDetail() {

            const functionName = "addDetail";

            this.formErrors({functionName, type: "clear"});
            this.calculateAddDetail();

            let form = JSON.parse(JSON.stringify(this.forms.entity.createUpdate.extras.modals.details.data));

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                (this.forms.entity.createUpdate.data.details).push({id: Utils.uuidv4(), ...form, name: form?.item?.name, currency: form?.item?.currency});

                Alerts.toastrs({type: "success", subtitle: "El producto ha sido agregado al detalle."});

                this.clearForm({functionName});

            }else {

                this.formErrors({functionName, type: "set", errors: validateForm});

            }

            this.calculateTotalDetail();

        },
        // Forms
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = JSON.parse(JSON.stringify(this.forms.entity.createUpdate.data));

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                let createUpdate = await (this.isDefined({value: this.forms.entity.createUpdate.data.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: this.forms.entity.createUpdate.data.id}) : Requests.post({route: this.config.entity.routes.store, data: form}));

                if(createUpdate?.bool && createUpdate?.data?.bool) {

                    Alerts.swals({show: false});

                    this.forms.entity.createUpdate.extras.modals.finished.titles.header = createUpdate?.data?.msg;
                    this.forms.entity.createUpdate.extras.modals.finished.titles.bool   = createUpdate?.data?.bool;
                    this.forms.entity.createUpdate.extras.modals.finished.data          = createUpdate?.data?.sale;

                    Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.finished.id});

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
                case "addDetail":
                    this.forms.entity.createUpdate.extras.modals.details.data.item_id  = "";
                    this.forms.entity.createUpdate.extras.modals.details.data.item     = null;
                    this.forms.entity.createUpdate.extras.modals.details.data.quantity = 1;
                    this.forms.entity.createUpdate.extras.modals.details.data.price    = 0;
                    this.forms.entity.createUpdate.extras.modals.details.data.total    = 0;
                    $(`#${this.forms.entity.createUpdate.extras.modals.details.select2.item}`).val("");
                    break;

                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id          = "";
                    this.forms.entity.createUpdate.data.holder_id   = "";
                    this.forms.entity.createUpdate.data.holder      = null;
                    this.forms.entity.createUpdate.data.sale_date   = Utils.getCurrentDate();
                    this.forms.entity.createUpdate.data.status      = "";
                    this.forms.entity.createUpdate.data.details     = [];
                    this.forms.entity.createUpdate.data.total       = 0;
                    $(`#${this.forms.entity.createUpdate.extras.select2.holder}`).val("");
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["addDetail"].includes(functionName)) {

                this.forms.entity.createUpdate.extras.modals.details.errors = ["set"].includes(type) ? errors : [];

            }else if(["createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null}) {

            let result = {
                bool: true
            };

            if(["addDetail"].includes(functionName)) {

                result.item_id  = [];
                result.price    = [];
                result.quantity = [];
                result.total    = [];

                if(!this.isDefined({value: form?.item_id})) {

                    result.item_id.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.quantity}) || Number(form?.quantity) < 0) {

                    result.quantity.push(this.config.forms.errors.labels.min_number_0);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.price}) || Number(form?.price) < 0) {

                    result.price.push(this.config.forms.errors.labels.min_number_0);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.total}) || Number(form?.total) < 0) {

                    result.total.push(this.config.forms.errors.labels.min_number_0);
                    result.bool = false;

                }

            }else if(["createUpdateEntity"].includes(functionName)) {

                result.holder_id = [];
                result.sale_date = [];
                result.status    = [];
                result.details   = [];

                if(!this.isDefined({value: form?.holder_id})) {

                    result.holder_id.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.sale_date})) {

                    result.sale_date.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

                if(this.isDefined({value: form?.id})) {

                    if(!this.isDefined({value: form?.status})) {

                        result.status.push(this.config.forms.errors.labels.required);
                        result.bool = false;

                    }

                }

                if(!this.isDefined({value: form?.details})) {

                    result.details.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        calculateTotal({item}) {

            const quantity    = Number(item?.quantity),
                  price       = Number(item?.price);

            const total = (isNaN(quantity) || isNaN(price)) ? 0 : this.fixedNumber(quantity * price);

            return total;

        },
        fixedNumber(value) {

            return Number(value).toFixed(this.config.forms.inputs.round);

        },
        // export({type, resource}) {
        exportpp({}) {

            // , data: this.lists.entity.filters;
            Requests.get({route: Requests.config({entity: "exports", type: "default"})});

        }
    },
    computed: {
        series() {

            let branch = (this.options?.branches?.records ?? []).filter(e => e?.id == this.forms.entity.createUpdate.data.branch_id);

            if(branch.length === 1) {

                let series = branch[0].series;

                return series.map(e => ({code: e.id, label: `${e.code}${e.number} - ${e?.documentType?.name}`}));

            }else {

                return [];

            }

        }
    }
};
</script>
