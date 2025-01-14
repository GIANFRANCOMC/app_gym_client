<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row g-4">
        <div class="col-lg-9 col-12">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <InputSlot
                            hasDiv
                            title="Sucursal"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.branch"
                            xl="5"
                            lg="6">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.branch"
                                    :options="branches"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
                        <InputSlot
                            hasDiv
                            title="Serie"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.serie"
                            xl="4"
                            lg="6">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.serie"
                                    :options="series"
                                    :clearable="false">
                                    <template #option="{ label, data }">
                                        <span v-text="label" class="d-block fw-bold"></span>
                                        <small v-text="data?.document_type?.name" class="d-block"></small>
                                    </template>
                                </v-select>
                            </template>
                        </InputSlot>
                        <InputDate
                            v-model="forms.entity.createUpdate.data.issue_date"
                            hasDiv
                            title="Fecha de emisión"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.issue_date"
                            xl="3"
                            lg="6"/>
                        <InputSlot
                            hasDiv
                            title="Cliente"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.holder"
                            xl="9"
                            lg="6">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.holder"
                                    :options="holders"
                                    :clearable="false"/>
                            </template>
                        </InputSlot>
                        <InputSlot
                            hasDiv
                            title="Moneda"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.currency"
                            xl="3"
                            lg="6">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.data.currency"
                                    :options="currencies"
                                    :clearable="false">
                                    <template #option="{ label, data }">
                                        <span v-text="label" class="d-block fw-bold"></span>
                                        <small v-text="'('+data?.sign+')'" class="d-block"></small>
                                    </template>
                                </v-select>
                            </template>
                        </InputSlot>
                    </div>
                    <div class="row g-3">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr class="text-center align-middle">
                                        <th class="fw-bold col-auto">#</th>
                                        <th class="fw-bold col-1">DESCRIPCIÓN</th>
                                        <th class="fw-bold col-3">CANTIDAD</th>
                                        <th class="fw-bold col-4">PRECIO UNITARIO</th>
                                        <th class="fw-bold col-3">TOTAL</th>
                                        <th class="fw-bold col-auto"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0 bg-white">
                                    <template v-if="(forms.entity.createUpdate.data.details).length > 0">
                                        <template v-for="(record, keyRecord) in forms.entity.createUpdate.data.details" :key="record.id">
                                            <tr class="text-center">
                                                <td>
                                                    <span v-text="keyRecord + 1" class="badge rounded-pill bg-info"></span>
                                                </td>
                                                <td class="fw-bold text-start">
                                                    <span class="text-break" v-text="record.name"></span>
                                                </td>
                                                <td>
                                                    <InputNumber
                                                        v-model="record.quantity"
                                                        @change="calculateDuration({record})"
                                                        :decimals="getItemDecimals({mode: 'result', record})"/>
                                                    <div class="d-block mt-1">
                                                        <button class="btn btn-danger btn-xs waves-effect" type="button" @click="changeQuantityDetail({record, keyRecord, type: 'subtract'})">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <button class="btn btn-info btn-xs waves-effect" type="button" @click="changeQuantityDetail({record, keyRecord, type: 'add'})">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <InputNumber v-model="record.price">
                                                        <template v-slot:inputGroupPrepend v-if="isDefined({value: record?.currency})">
                                                            <button class="btn btn-primary waves-effect px-1" type="button" v-text="record?.currency?.sign"></button>
                                                        </template>
                                                    </InputNumber>
                                                </td>
                                                <td>
                                                    <span class="text-break">
                                                        <span v-text="record.currency?.sign ?? ''"></span>
                                                        <span v-text="separatorNumber(calculateTotal({item: record}))" class="ms-2"></span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-xs waves-effect my-1" type="button" @click="deleteDetail({record, keyRecord})">
                                                        <i class="fa fa-times"></i>
                                                        <span class="ms-1 fw-semibold">Eliminar</span>
                                                    </button>
                                                    <button class="btn btn-info btn-xs waves-effect my-1" type="button" @click="duplicateDetail({record, keyRecord})">
                                                        <i class="fa fa-copy"></i>
                                                        <span class="ms-1 fw-semibold">Duplicar</span>
                                                    </button>
                                                    <template v-if="['subscription'].includes(record?.type)">
                                                        <button class="btn btn-success btn-xs waves-effect my-1" type="button" @click="viewDetail({record, keyRecord})">
                                                            <i :class="record?.extras?.showDetail ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
                                                            <span class="ms-1 fw-semibold" v-text="record?.extras?.showDetail ? 'Ocultar detalle' : 'Mostar detalle'"></span>
                                                        </button>
                                                    </template>
                                                </td>
                                            </tr>
                                            <template v-if="record?.extras?.showDetail">
                                                <tr class="text-center" v-if="['subscription'].includes(record?.type)" style="border-color: transparent;">
                                                    <td colspan="6">
                                                        <div class="row g-2 my-1">
                                                            <div class="col-3">
                                                                <InputText
                                                                    title="Tipo"
                                                                    v-model="record.extras.formatted_type"
                                                                    isRequired
                                                                    disabled/>
                                                            </div>
                                                            <div class="col-3">
                                                                <InputText
                                                                    title="Duración"
                                                                    v-model="record.extras.formatted_duration"
                                                                    isRequired
                                                                    disabled/>
                                                            </div>
                                                            <div class="col-3">
                                                                <InputDate
                                                                    title="Fecha de inicio"
                                                                    v-model="record.extras.start_date"
                                                                    @change="calculateDuration({record})"
                                                                    isRequired/>
                                                            </div>
                                                            <div class="col-3">
                                                                <InputDate
                                                                    title="Fecha de finalización"
                                                                    v-model="record.extras.end_date"
                                                                    isRequired
                                                                    disabled/>
                                                            </div>
                                                            <div class="divider text-center divider-info">
                                                                <div class="divider-text">
                                                                    <div class="badge rounded-pill bg-label-info px-5 py-1">
                                                                        <i class="fa fa-info-circle"></i>
                                                                        <span class="ms-2 fw-bold h6 text-info" v-text="'Detalle #'+(keyRecord + 1)"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
                                        <tr class="fs-5">
                                            <td colspan="4" class="fw-bold text-end">TOTAL :</td>
                                            <td colspan="2" class="fw-bold text-start">
                                                <span class="text-break">
                                                    <span v-text="forms.entity.createUpdate.data.currency?.data?.sign ?? ''"></span>
                                                    <span v-text="separatorNumber(total)" class="ms-2"></span>
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td class="text-center" colspan="99">
                                                <WithoutData type="text"/>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <!-- <small :class="config.forms.errors.styles.default" v-html="isDefined({value: forms.entity.createUpdate.errors?.details}) ? forms.entity.createUpdate.errors?.details : ''"></small> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <InputText
                            v-model="forms.entity.createUpdate.data.observation"
                            hasDiv
                            :divClass="['mb-3', 'p-0']"
                            title="Observaciones"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.errors?.observation"/>
                        <button class="btn btn-primary waves-effect my-1" @click="modalAddDetail({})">
                            <i class="fa fa-plus"></i>
                            <span class="ms-2">Agregar detalle</span>
                        </button>
                        <button class="btn btn-success waves-effect my-1" @click="createUpdateEntity()">
                            <i class="fa-solid fa-cash-register"></i>
                            <span class="ms-2">Generar venta</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.createUpdate.extras.modals.details.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.createUpdate.extras.modals.details.titles[forms.entity.createUpdate.extras.modals.details.data?.mode]"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <InputSlot
                            hasDiv
                            title="Catálogo comercial"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.item">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.createUpdate.extras.modals.details.data.item"
                                    :options="items"
                                    :clearable="false">
                                    <template #option="{ label, data }">
                                        <span v-text="label" class="d-block fw-bold"></span>
                                        <div class="d-block">
                                            <small>
                                                <i class="fa fa-star"></i>
                                            </small>
                                            <small v-text="data?.formatted_type" class="ms-1"></small>
                                            <small v-text="data?.currency?.sign" class="ms-2"></small>
                                            <small v-text="separatorNumber(data?.price)" class="ms-1"></small>
                                        </div>
                                        <template v-if="['subscription'].includes(data?.type)">
                                            <div class="d-block">
                                                <small>
                                                    <i class="fa fa-clock"></i>
                                                </small>
                                                <small class="ms-1">Duración:</small>
                                                <small v-text="data?.formatted_duration" class="ms-2"></small>
                                            </div>
                                        </template>
                                    </template>
                                </v-select>
                            </template>
                        </InputSlot>
                        <InputNumber
                            v-model="forms.entity.createUpdate.extras.modals.details.data.quantity"
                            @change="calculateDuration({record: forms.entity.createUpdate.extras.modals.details.data})"
                            hasDiv
                            title="Cantidad"
                            isRequired
                            :decimals="getItemDecimals({mode: 'result', record: forms.entity.createUpdate.extras.modals.details.data})"
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.quantity"
                            xl="4"
                            lg="6"/>
                        <InputNumber
                            v-model="forms.entity.createUpdate.extras.modals.details.data.price"
                            hasDiv
                            title="Precio"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.price"
                            xl="4"
                            lg="6">
                            <template v-slot:inputGroupPrepend>
                                <template v-if="isDefined({value: forms.entity.createUpdate.extras.modals.details.data.item?.data?.currency})">
                                    <button class="btn btn-primary waves-effect pe-none" type="button" v-text="forms.entity.createUpdate.extras.modals.details.data.item?.data?.currency?.sign"></button>
                                </template>
                            </template>
                        </InputNumber>
                        <InputSlot
                            hasDiv
                            title="Total"
                            isRequired
                            disabled
                            xl="4"
                            lg="6">
                            <template v-slot:inputGroupPrepend>
                                <template v-if="isDefined({value: forms.entity.createUpdate.data.currency?.data})">
                                    <button class="btn btn-primary waves-effect pe-none" type="button" v-text="forms.entity.createUpdate.data.currency?.data?.sign"></button>
                                </template>
                            </template>
                            <template v-slot:input>
                                <input class="form-control" disabled :value="separatorNumber(totalModalDetail)"/>
                            </template>
                        </InputSlot>
                        <template v-if="['subscription'].includes(forms.entity.createUpdate.extras.modals.details.data.type)">
                            <InputText
                                v-model="forms.entity.createUpdate.extras.modals.details.data.extras.formatted_duration"
                                hasDiv
                                title="Duración"
                                isRequired
                                disabled
                                xl="4"
                                lg="6"/>
                            <InputDate
                                v-model="forms.entity.createUpdate.extras.modals.details.data.extras.start_date"
                                @change="calculateDuration({record: forms.entity.createUpdate.extras.modals.details.data})"
                                hasDiv
                                title="Fecha de inicio"
                                isRequired
                                hasTextBottom
                                :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.extras_start_date"
                                xl="4"
                                lg="6"/>
                            <InputDate
                                v-model="forms.entity.createUpdate.extras.modals.details.data.extras.end_date"
                                hasDiv
                                title="Fecha de finalización"
                                isRequired
                                disabled
                                hasTextBottom
                                :textBottomInfo="forms.entity.createUpdate.extras.modals.details.errors?.extras_end_date"
                                xl="4"
                                lg="6"/>
                        </template>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', ['store'].includes(forms.entity.createUpdate.extras.modals.details.data?.mode) ? 'btn-primary' : 'btn-warning']" @click="addDetail()">
                        <i class="fa fa-save"></i>
                        <span class="ms-2">Guardar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <PrintSale :modalId="forms.entity.createUpdate.extras.modals.finished.id" :data="forms.entity.createUpdate.extras.modals.finished.data">
        <template v-slot:messageAppend>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center mb-2">
                <template v-if="forms.entity.createUpdate.extras.modals.finished.data?.extras?.bool">
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle text-success fs-4"></i>
                        <span class="fw-semibold fs-5 ms-2" v-text="forms.entity.createUpdate.extras.modals.finished.data?.extras?.msg"></span>
                    </div>
                </template>
                <template v-else>
                    <div class="alert alert-danger">
                        <i class="fa fa-check-circle text-danger fs-4"></i>
                        <span class="fw-semibold fs-5 ms-2" v-text="forms.entity.createUpdate.extras.modals.finished.data?.extras?.msg"></span>
                    </div>
                </template>
            </div>
        </template>
        <template v-slot:extraGroupAppend>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 mx-2">
                <div class="text-center cursor-pointer" data-bs-dismiss="modal">
                    <div class="badge bg-success p-3 rounded mb-1">
                        <i class="fa-solid fa-cash-register fs-3"></i>
                    </div>
                    <br/>
                    <span class="fw-semibold">Nueva venta</span>
                </div>
            </div>
        </template>
    </PrintSale>
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

        Utils.navbarItem("menu-item-sales", {addClass: "open"});
        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});

        }

    },
    data() {
        return {
            forms: {
                entity: {
                    createUpdate: {
                        extras: {
                            modals: {
                                details: {
                                    id: Utils.uuid(),
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar"
                                    },
                                    data: {
                                        id: null,
                                        item: null,
                                        type: "",
                                        currency: null,
                                        name: "",
                                        quantity: 1,
                                        price: 0,
                                        observation: "",
                                        extras: {
                                            duration_type: "",
                                            duration_value: "",
                                            start_date: "",
                                            end_date: "",
                                            observation: "",
                                            formatted_duration: "",
                                            formatted_type: "",
                                            showDetail: true
                                        },
                                        mode: "store"
                                    },
                                    errors: {}
                                },
                                finished: {
                                    id: Utils.uuid(),
                                    data: {
                                        id: null,
                                        extras: {}
                                    }
                                }
                            }
                        },
                        data: {
                            id: null,
                            branch: null,
                            serie: null,
                            issue_date: "",
                            holder: null,
                            currency: null,
                            observation: "",
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
                        title: "Nuevo",
                        active: true,
                        menu: {
                            id: "menu-item-create-sales"
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

            this.options.branches    = initParams.data?.config?.branches;
            this.options.currencies  = initParams.data?.config?.currencies;
            this.options.holders     = initParams.data?.config?.customers;
            this.options.items       = initParams.data?.config?.items;
            this.options.salesHeader = initParams.data?.config?.salesHeader;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.forms.entity.createUpdate.data.branch     = (this.branches).length > 0 ? this.branches[0] : null;
                this.forms.entity.createUpdate.data.issue_date = Utils.getCurrentDate();
                this.forms.entity.createUpdate.data.holder     = (this.holders).length > 0 ? this.holders[0] : null;
                this.forms.entity.createUpdate.data.currency   = (this.currencies).length > 0 ? this.currencies[0] : null;

                resolve(true);

            });

        },
        // Actions modal detail
        modalAddDetail({}) {

            let form = this.forms.entity.createUpdate.extras.modals.details;

            form.data.mode = "store";

            Alerts.modals({type: "show", id: form.id});

        },
        addDetail() {

            const functionName = "addDetail";

            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.createUpdate.extras.modals.details.data);

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                delete form.item.data;

                if(["store"].includes(form.mode)) {

                    (this.forms.entity.createUpdate.data.details).push({...form, id: Utils.uuid()});

                    Alerts.toastrs({type: "success", subtitle: `Se ha agregado <b><small>(${form?.quantity})</small> ${form?.name}</b> al detalle de la venta.`});

                    this.clearForm({functionName});

                }

            }else {

                this.formErrors({functionName, type: "set", errors: validateForm});
                Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});

            }

        },
        changeQuantityDetail({record, keyRecord, type = "add"}) {

            let operation = 0;

            const quantity = record?.quantity ?? 0;

            if(["add"].includes(type)) {

                operation = Number(quantity) + 1;

            }else if(["subtract"].includes(type)) {

                operation = Number(quantity) - 1;

            }

            if(Number(operation) >= 0) {

                record.quantity = operation;

            }else {

                Alerts.toastrs({type: "error", subtitle: this.config.forms.errors.labels.min_number_0});

            }

            this.calculateDuration({record});

        },
        deleteDetail({record, keyRecord}) {

            const functionName = "deleteDetail";

            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(record);

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                let el = this;

                Swal.fire({
                    html: `<span>¿Desea eliminar <b>${form?.name}</b> del detalle de la venta?</span>`,
                    icon: "warning",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    customClass: {
                        confirmButton: "btn btn-danger waves-effect",
                        cancelButton: "btn btn-secondary waves-effect ms-3"
                    }
                })
                .then(function(result) {

                    if(result.isConfirmed) {

                        (el.forms.entity.createUpdate.data.details).splice(keyRecord, 1);

                        Alerts.toastrs({type: "success", subtitle: `<b>${form?.name}</b> ha sido eliminado del detalle de la venta.`});

                    }else if(result.isDismissed) {

                        //

                    }

                });

            }

        },
        duplicateDetail({record, keyRecord}) {

            const functionName = "duplicateDetail";

            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(record);

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                let el = this;

                Swal.fire({
                    html: `<span>¿Desea duplicar <b>${form?.name}</b> al detalle de la venta?</span>`,
                    icon: "question",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonText: "Sí, duplicar",
                    cancelButtonText: "Cancelar",
                    customClass: {
                        confirmButton: "btn btn-info waves-effect",
                        cancelButton: "btn btn-secondary waves-effect ms-3"
                    }
                })
                .then(function(result) {

                    if(result.isConfirmed) {

                        form.id = Utils.uuid();

                        (el.forms.entity.createUpdate.data.details).push(form);

                        Alerts.toastrs({type: "success", subtitle: `<b>${form?.name}</b> ha sido duplicado al detalle de la venta.`});

                    }else if(result.isDismissed) {

                        //

                    }

                });

            }

        },
        viewDetail({record, keyRecord}) {

            record.extras.showDetail = !record.extras.showDetail;

        },
        // Entity forms
        async createUpdateEntity() {

            const functionName = "createUpdateEntity";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.createUpdate.data);

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.serie_id    = form?.serie?.code;
                form.holder_id   = form?.holder?.code;
                form.currency_id = form?.currency?.code;

                delete form.branch;
                delete form.serie;
                delete form.holder;
                delete form.currency;

                form.details.forEach(detail => {

                    detail.item_id = detail?.item?.code;
                    detail.currency_id = detail?.currency?.id;

                    delete detail.item;
                    delete detail.currency;

                });

                let createUpdate = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.update, data: form, id: form.id}) :
                                                                             Requests.post({route: this.config.entity.routes.store, data: form}));

                if(Requests.valid({result: createUpdate})) {

                    const {sale, ...extras} = createUpdate.data;

                    this.forms.entity.createUpdate.extras.modals.finished.data = {...sale, extras};

                    Alerts.swals({show: false});
                    Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.finished.id, timeout: 300});

                    this.clearForm({functionName});

                }else {

                    this.formErrors({functionName, type: "set", errors: createUpdate?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: createUpdate?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                // Alerts.swals({show: false});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        // Utils forms
        clearForm({functionName}) {

            switch(functionName) {
                case "addDetail":
                    this.forms.entity.createUpdate.extras.modals.details.data.id   = null;
                    this.forms.entity.createUpdate.extras.modals.details.data.item = null;
                    break;

                case "createUpdateEntity":
                    this.forms.entity.createUpdate.data.id          = null;
                    // this.forms.entity.createUpdate.data.issue_date  = Utils.getCurrentDate();
                    // this.forms.entity.createUpdate.data.holder      = null;
                    this.forms.entity.createUpdate.data.status      = "";
                    this.forms.entity.createUpdate.data.observation = "";
                    // this.forms.entity.createUpdate.data.details     = [];
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
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["addDetail"].includes(functionName)) {

                result.item       = [];
                result.quantity   = [];
                result.price      = [];
                result.extras_start_date = [];
                result.extras_end_date   = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.item})) {

                    result.item.push(`${isDescriptive ? "Detalle:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }else {

                    if(["subscription"].includes(form?.type)) {

                        if(!this.isDefined({value: form?.extras?.start_date})) {

                            result.extras_start_date.push(`${isDescriptive ? "Fecha de inicio:" : ""} ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                        if(!this.isDefined({value: form?.extras?.end_date})) {

                            result.extras_end_date.push(`${isDescriptive ? "Fecha de finalización:" : ""} ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }else {

                            let today = new Date();
                            let endDate = new Date(form?.extras?.end_date);

                            today.setHours(0, 0, 0, 0);
                            endDate.setHours(0, 0, 0, 0);

                            if(today > endDate) {

                                result.extras_end_date.push(`${isDescriptive ? "Fecha de finalización:" : ""} Debe ser mayor a la fecha de hoy.`);
                                result.bool = false;

                            }

                        }

                    }

                }

                if(!this.isDefined({value: form?.quantity}) || Number(form?.quantity) <= 0) {

                    result.quantity.push(`${isDescriptive ? "Cantidad:" : ""} ${this.config.forms.errors.labels.min_number_0}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.price}) || Number(form?.price) <= 0) {

                    result.price.push(`${isDescriptive ? "Precio:" : ""} ${this.config.forms.errors.labels.min_number_0}`);
                    result.bool = false;

                }

            }else if(["deleteDetail"].includes(functionName)) {

                result.item = [];

                if(!this.isDefined({value: form?.item})) {

                    result.item.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

            }else if(["duplicateDetail"].includes(functionName)) {

                result.item = [];

                if(!this.isDefined({value: form?.item})) {

                    result.item.push(this.config.forms.errors.labels.required);
                    result.bool = false;

                }

            }else if(["createUpdateEntity"].includes(functionName)) {

                result.branch      = [];
                result.serie       = [];
                result.issue_date  = [];
                result.holder      = [];
                result.currency    = [];
                result.observation = [];
                result.status      = [];
                result.details     = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.branch})) {

                    result.branch.push(`${isDescriptive ? "Sucursal:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.serie})) {

                    result.serie.push(`${isDescriptive ? "Serie:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.issue_date})) {

                    result.issue_date.push(`${isDescriptive ? "Fecha de emisión:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.holder})) {

                    result.holder.push(`${isDescriptive ? "Cliente:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.currency})) {

                    result.currency.push(`${isDescriptive ? "Moneda:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(this.isDefined({value: form?.id})) {

                    if(!this.isDefined({value: form?.status})) {

                        result.status.push(this.config.forms.errors.labels.required);

                        result.status.push(`${isDescriptive ? "Estado:" : ""} ${this.config.forms.errors.labels.required}`);
                        result.bool = false;

                    }

                }

                if(!this.isDefined({value: form?.details}) || (form?.details).length === 0) {

                    result.details.push(`${isDescriptive ? "Detalle de la venta:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }else {

                    let errorDetails = [];

                    for(let [keyDetail, detail] of Object.entries(form?.details)) {

                        let validateDetail = this.validateForm({functionName: "addDetail", form: detail, extras: {type: "descriptive"}});

                        if(!validateDetail?.bool) {

                            let propsValidate = Utils.getErrors({errors: validateDetail});

                            errorDetails.push(`<p class="mb-1">Detalle de la venta <b>${parseInt(keyDetail) + 1}</b>:</p>`+propsValidate.flat().map(e => `<li>${e}</li>`).join(""));
                            result.bool = false;

                        }

                    }

                    result.details = [errorDetails.join("<br/>")];

                }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        isSubscription(type) {

            return ["subscription"].includes(type);

        },
        calculateTotal({item}) {

            return Utils.calculateTotal({item});

        },
        calculateDuration({mode = "record", record = null}) {

            let data = record;

            if(["subscription"].includes(data?.type)) {

                let startDate     = data?.extras?.start_date,
                    durationType  = data?.extras?.duration_type,
                    durationValue = Number(data?.extras?.duration_value),
                    quantity      = Number(data.quantity);

                let durationTotal = isNaN(durationValue) || isNaN(quantity) ? 0 : (durationValue * quantity);

                const endDate = Utils.addDuration({startDate, type: durationType, quantity: durationTotal});

                if(["record"].includes(mode)) {

                    record.extras.end_date = endDate;

                }else if(["result"].includes(mode)) {

                    return endDate;

                }

            }

        },
        getItemDecimals({mode = "record", record = null}) {

            const decimals = this.isSubscription(record?.type) ? 0 : this.config.forms.inputs.round;

            if(["record"].includes(mode)) {

                record.decimals = decimals;

            }else if(["result"].includes(mode)) {

                return decimals;

            }

        },
        fixedNumber(value, decimals = null) {

            return Utils.fixedNumber(value, decimals);

        },
        separatorNumber(value) {

            return Utils.separatorNumber(value);

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Ventas"}, this.config.entity.page];

        },
        branches: function() {

            return this.options?.branches?.records.map(e => ({code: e.id, label: e.name, data: e}));

        },
        series: function() {

            let branch = (this.options?.branches?.records ?? []).filter(e => e?.id == this.forms.entity.createUpdate.data.branch?.code);

            if(branch.length === 1) {

                let series = branch[0].series;

                return series.map(e => ({code: e.id, label: e.legible_serie, data: e}));

            }

            return [];

        },
        holders: function() {

            return this.options?.holders?.records.map(e => ({code: e.id, label: `${e.document_number} - ${e.name}`, data: e}));

        },
        currencies: function() {

            return this.options?.currencies?.records.map(e => ({code: e.id, label: e.plural_name, data: e}));

        },
        items: function() {

            return this.options?.items?.records.map(e => ({code: e.id, label: e.name, data: e}));

        },
        total: function() {

            let total = 0;

            for(let detail of this.forms.entity.createUpdate.data.details) {

                total += Number(this.calculateTotal({item: detail}));

            }

            return this.fixedNumber(total);

        },
        totalModalDetail: function() {

            return this.calculateTotal({item: this.forms.entity.createUpdate.extras.modals.details.data});

        }
    },
    watch: {
        "forms.entity.createUpdate.data.branch": function(newValue, oldValue) {

            this.forms.entity.createUpdate.data.serie = (this.series).length > 0 ? this.series[0] : null;

        },
        "forms.entity.createUpdate.extras.modals.details.data.item": function(newValue, oldValue) {

            let data = newValue?.data;

            this.forms.entity.createUpdate.extras.modals.details.data.type     = data?.type;
            this.forms.entity.createUpdate.extras.modals.details.data.currency = data?.currency;
            this.forms.entity.createUpdate.extras.modals.details.data.name     = data?.name;
            this.forms.entity.createUpdate.extras.modals.details.data.price    = Number(data?.price ?? 0);

            if(["subscription"].includes(data?.type)) {

                const decimals = this.getItemDecimals({mode: "result", record: this.forms.entity.createUpdate.extras.modals.details.data});

                this.forms.entity.createUpdate.extras.modals.details.data.quantity = Number(this.fixedNumber(this.forms.entity.createUpdate.extras.modals.details.data.quantity, decimals));

                let start_date = Utils.getCurrentDate();

                this.forms.entity.createUpdate.extras.modals.details.data.extras = {
                    duration_type: data?.duration_type,
                    duration_value: data?.duration_value,
                    start_date,
                    end_date: "",
                    observation: "",
                    formatted_duration: data?.formatted_duration,
                    formatted_type: data?.formatted_type,
                    showDetail: true
                };

                this.calculateDuration({record: this.forms.entity.createUpdate.extras.modals.details.data});

            }

        }
    }
};
</script>
