<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Crear' }]"/>

    <div class="row invoice-add">
        <!-- Invoice Add-->
        <div class="col-lg-9 col-12 mb-lg-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <InputSelect
                            v-model="forms.entity.createUpdate.data.customer_id"
                            :options="options?.customers?.records.map(e=>({code: e.id, label: e.name}))"
                            hasDiv
                            title="Cliente"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.customer_id"
                            xl="8"
                            lg="8"
                            md="12"
                            sm="12"/>
                        <InputDate
                            v-model="forms.entity.createUpdate.data.sale_date"
                            hasDiv
                            title="Fecha de venta"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.sale_date"
                            xl="4"
                            lg="4"
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
                                                @input="calculateRecordDetail({record})"/>
                                        </td>
                                        <td v-text="record.total"></td>
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
                        <i class="fa fa-save"></i>
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
                    <div class="row g-2 mb-3">
                        <InputSelect
                            v-model="forms.entity.createUpdate.extras.modals.details.data.item_id"
                            :options="options?.items?.records.map(e=>({code: e.id, label: e.name}))"
                            @change="changeSelectAddDetail()"
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
                    <div class="row g-2 mb-3">
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
                            sm="12"/>
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
                            sm="12"/>
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
// import InputSelect2 from "../../Components/InputSelect2.vue";
import InputText    from "../../Components/InputText.vue";
import Paginator    from "../../Components/Paginator.vue";

export default {
    components: {
        Breadcrumb,
        InputDate,
        InputNumber,
        InputSelect,
        // InputSelect2,
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
                                    data: {
                                        item_id: null,
                                        item: null,
                                        quantity: 0,
                                        price: 0,
                                        total: 0
                                    },
                                    errors: {}
                                }
                            },
                            select2: {
                                customer: "customerSelect2"
                            }
                        },
                        data: {
                            id: null,
                            customer_id: "",
                            sale_date: "",
                            status: "",
                            details: []
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

            this.options.sales     = initParams.data?.config?.sales;
            this.options.customers = initParams.data?.config?.customers;
            this.options.items     = initParams.data?.config?.items;

            return initParams?.bool && initParams?.data?.bool;

        },
        async initOthers({}) {

            return true;

        },
        async listEntity({url = null}) {

            //

        },
        // Forms
        modalAddDetail({}) {

            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.details.id});

        },
        changeSelectAddDetail() {

            const item_id = this.forms.entity.createUpdate.extras.modals.details.data?.item_id;

            let filter = (this.options.items?.records).filter(e => e?.id == item_id);

            if(filter.length === 1) {

                let detail = filter[0];

                this.forms.entity.createUpdate.extras.modals.details.data.item  = detail;
                this.forms.entity.createUpdate.extras.modals.details.data.price = parseFloat(detail?.price);
                this.calculateAddDetail();

            }else {

                this.forms.entity.createUpdate.extras.modals.details.data.item_id = null;
                this.forms.entity.createUpdate.extras.modals.details.data.item    = null;

                Alerts.toastrs({type: "error", subtitle: "El producto seleccionado no es válido."});

            }

        },
        calculateAddDetail() {

            const detailModal = this.forms.entity.createUpdate.extras.modals.details.data,
                  quantity    = parseFloat(detailModal?.quantity),
                  price       = parseFloat(detailModal?.price),
                  total       = quantity * price;

            this.forms.entity.createUpdate.extras.modals.details.data.quantity = quantity;
            this.forms.entity.createUpdate.extras.modals.details.data.price    = price;
            this.forms.entity.createUpdate.extras.modals.details.data.total    = parseFloat(total).toFixed(2);

        },
        addDetail() {

            const functionName = "addDetail";

            this.formErrors({functionName, type: "clear"});
            this.calculateAddDetail();

            let form = JSON.parse(JSON.stringify(this.forms.entity.createUpdate.extras.modals.details.data));

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                (this.forms.entity.createUpdate.data.details).push({id: Utils.uuidv4(), ...form, name: form?.item?.name});

                Alerts.toastrs({type: "success", subtitle: "El producto ha sido agregado al detalle."});

                this.clearForm({functionName});

            }else {

                this.formErrors({functionName, type: "set", errors: validateForm});

            }

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

                    Swal.fire({
                        html: `<h3 class="fw-bold">${createUpdate.data?.msg}</h3>`,
                        allowOutsideClick: false,
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, nueva venta",
                        denyButtonText: "No, ir al listado"
                    })
                    .then((result) => {

                        if(result.isConfirmed) {

                            this.clearForm({functionName});

                        }else if (result.isDenied) {

                            window.location.href = this.config.entity.routes.consult;

                        }

                    });

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
        // Calculate
        calculateRecordDetail({record = null}) {

            const quantity    = parseFloat(record?.quantity),
                  price       = parseFloat(record?.price),
                  total       = quantity * price;

            record.quantity = quantity;
            record.price    = price;
            record.total    = parseFloat(total).toFixed(2);

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "addDetail":
                    this.forms.entity.createUpdate.extras.modals.details.data.item_id  = null;
                    this.forms.entity.createUpdate.extras.modals.details.data.item     = null;
                    this.forms.entity.createUpdate.extras.modals.details.data.quantity = 0;
                    this.forms.entity.createUpdate.extras.modals.details.data.price    = 0;
                    this.forms.entity.createUpdate.extras.modals.details.data.total    = 0;
                    break;

                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id        = null;
                    this.forms.entity.createUpdate.data.details   = [];
                    this.forms.entity.createUpdate.data.sale_date = "";
                    this.forms.entity.createUpdate.data.status    = "";
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

                if(!this.isDefined({value: form?.quantity}) || parseFloat(form?.quantity) < 0) {

                    result.quantity.push(this.config.forms.errors.labels.min_number_0);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.price}) || parseFloat(form?.price) < 0) {

                    result.price.push(this.config.forms.errors.labels.min_number_0);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.total}) || parseFloat(form?.total) < 0) {

                    result.total.push(this.config.forms.errors.labels.min_number_0);
                    result.bool = false;

                }

            }else if(["createUpdateEntity"].includes(functionName)) {

                result.customer_id = [];
                result.sale_date   = [];
                result.status      = [];
                result.details     = [];

                if(!this.isDefined({value: form?.customer_id})) {

                    result.customer_id.push(this.config.forms.errors.labels.required);
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

        }
    }
};
</script>
