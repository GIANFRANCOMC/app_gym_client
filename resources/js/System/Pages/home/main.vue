<template>
    <!-- <Breadcrumb :list="breadcrumbTitles"/> -->

    <!-- Content -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="fa-solid fa-cash-register"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="'S/ '+separatorNumber(fixedNumber(forms.entity.home.data.sales?.all?.total ?? 0))"></h4>
                    </div>
                    <span class="fw-semibold">
                        VENTAS EN TOTAL
                        <small class="text-muted">| Hoy</small>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="fa-solid fa-rectangle-xmark"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="'S/ '+separatorNumber(fixedNumber(forms.entity.home.data.sales?.cancelled?.total ?? 0))"></h4>
                    </div>
                    <span class="fw-semibold">
                        VENTAS ANULADAS
                        <small class="text-muted">| Hoy</small>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-git-fork ti-md"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="separatorNumber(forms.entity.home.data.branches?.valid?.count ?? 0)"></h4>
                    </div>
                    <span class="fw-semibold">SUCURSALES</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-secondary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="fa-solid fa-users"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="separatorNumber(forms.entity.home.data.users?.valid?.count ?? 0)"></h4>
                    </div>
                    <span class="fw-semibold">COLABORADORES</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                <h5 class="m-0 fw-semibold badge bg-primary text-uppercase">Últimas ventas de hoy</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr class="text-center align-middle">
                            <th class="fw-bold col-1">DOCUMENTO</th>
                            <th class="fw-bold col-1">CLIENTE</th>
                            <th class="fw-bold col-1">FECHA DE EMISIÓN</th>
                            <th class="fw-bold col-1">TOTAL</th>
                            <th class="fw-bold col-1">ESTADO</th>
                            <th class="fw-bold col-1">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0 bg-white">
                        <template v-if="(forms.entity.home.data.sales?.all?.latest ?? []).length > 0">
                            <tr v-for="record in forms.entity.home.data.sales.all.latest" :key="record.id" class="text-center">
                                <td class="text-start">
                                    <span v-text="record.serie_sequential" class="fw-bold d-block"></span>
                                    <small v-text="record.serie?.document_type?.name" class="d-block"></small>
                                </td>
                                <td class="text-start">
                                    <span v-text="record.holder?.name" class="fw-bold d-block"></span>
                                    <small v-text="record.holder?.document_number" class="d-block"></small>
                                </td>
                                <td>
                                    <span v-text="record.formatted_issue_date" class="d-block"></span>
                                </td>
                                <td>
                                    <span v-text="record.currency?.sign ?? ''" class="fw-semibold"></span>
                                    <span v-text="separatorNumber(record.total)" class="fw-semibold ms-1"></span>
                                </td>
                                <td>
                                    <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive', 'cancelled'].includes(record.status) }]" v-text="record.formatted_status"></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary waves-effect" @click="modalActionsEntity({record})">
                                        <i class="fa fa-gear"></i>
                                        <span class="ms-2">Acciones</span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td class="text-center" colspan="99">
                                    <WithoutData type="image"/>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <PrintSale :modalId="forms.entity.home.extras.modals.actions.id" :data="forms.entity.home.extras.modals.actions.data">
        <template v-slot:extraGroupAppend>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-5 mb-3">
                <InputText
                    v-model="forms.entity.home.extras.modals.actions.data.whatsapp">
                    <template v-slot:inputGroupAppend>
                        <button class="btn btn-success waves-effect" type="button" @click="sendWhatsapp({data: forms.entity.home.extras.modals.actions.data})" :disabled="!isDefined({value: forms.entity.home.extras.modals.actions.data.whatsapp})">
                            <i class="ti ti-brand-whatsapp"></i>
                            <span class="ms-2">Enviar a Whatsapp</span>
                        </button>
                    </template>
                </InputText>
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

        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            this.initData({});

        }

    },
    data() {
        return {
            forms: {
                entity: {
                    home: {
                        extras: {
                            modals: {
                                actions: {
                                    id: Utils.uuid(),
                                    data: {
                                        id: null,
                                        extras: {},
                                        whatsapp: ""
                                    },
                                    errors: {}
                                }
                            }
                        },
                        data: {
                            sales: null,
                            branches: null,
                            users: null
                        }
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "home"}),
                    page: {
                        title: "Inicio",
                        active: true,
                        menu: {
                            id: "menu-item-home"
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

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                resolve(true);

            });

        },
        async initData({}) {

            let initData = await Requests.get({route: this.config.entity.routes.initData, data: {page: "main"}, showAlert: true});

            this.forms.entity.home.data.sales    = initData.data?.data?.sales;
            this.forms.entity.home.data.branches = initData.data?.data?.branches;
            this.forms.entity.home.data.users    = initData.data?.data?.users;

            return Requests.valid({result: initData});

        },
        // Entity forms
        modalActionsEntity({record = null}) {

            this.forms.entity.home.extras.modals.actions.data = {...record, extras: {}, whatsapp: ""};

            Alerts.modals({type: "show", id: this.forms.entity.home.extras.modals.actions.id});

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        fixedNumber(value) {

            return Utils.fixedNumber(value);

        },
        separatorNumber(value) {

            return Utils.separatorNumber(value);

        },
        sendWhatsapp({data = null, action = "reportSale"}) {

            const phoneNumber = this.forms.entity.home.extras.modals.actions.data.whatsapp;
            const message     = Utils.getMessageWhatsapp({data, action});

            Utils.sendWhatsapp({phoneNumber, message});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [this.config.entity.page];

        }
    }
};
</script>
