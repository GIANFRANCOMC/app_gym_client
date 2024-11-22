<template>
    <Breadcrumb :list="[config.entity.page]"/>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="fa-solid fa-cash-register"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="'S/ '+fixedNumber(forms.entity.home.data.sales?.all?.total ?? 0)"></h4>
                    </div>
                    <p class="mb-1 fw-semibold">VENTAS EN TOTAL</p>
                    <p class="mb-0">
                        <small class="text-muted me-2">Hoy</small>
                        <span class="text-heading fw-medium me-2" v-text="forms.entity.home.data.sales?.all?.count ?? 0"></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="fa-solid fa-rectangle-xmark"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="'S/ '+fixedNumber(forms.entity.home.data.sales?.cancelled?.total ?? 0)"></h4>
                    </div>
                    <p class="mb-1 fw-semibold">VENTAS ANULADAS</p>
                    <p class="mb-0">
                        <small class="text-muted me-2">Hoy</small>
                        <span class="text-heading fw-medium me-2" v-text="forms.entity.home.data.sales?.cancelled?.count ?? 0"></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-git-fork ti-md"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="forms.entity.home.data.branches?.valid?.count ?? 0"></h4>
                    </div>
                    <p class="mb-1 fw-semibold">SUCURSALES</p>
                    <p class="mb-0">
                        <small class="text-muted">Total</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-secondary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="fa-solid fa-users"></i>
                            </span>
                        </div>
                        <h4 class="mb-0" v-text="forms.entity.home.data.users?.valid?.count ?? 0"></h4>
                    </div>
                    <p class="mb-1 fw-semibold">COLABORADORES</p>
                    <p class="mb-0">
                        <small class="text-muted">Total</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2">Últimas ventas de hoy</h5>
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
                                    <span v-text="record.currency?.sign ?? ''"></span>
                                    <span v-text="record.total" class="ms-1"></span>
                                </td>
                                <td>
                                    <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive', 'cancelled'].includes(record.status) }]" v-text="record.formatted_status"></span>
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

        Utils.navbarItem(this.config.entity.page.menu.id, {addClass: "open"});
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
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        fixedNumber(value) {

            return Utils.fixedNumber(value);

        }
    }
};
</script>
