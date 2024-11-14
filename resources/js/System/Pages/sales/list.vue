<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Listado' }]"/>

    <!-- Records -->
    <div class="row row align-items-end g-3 mb-4">
        <InputSlot
            hasDiv
            title="Serie"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            xl="6"
            lg="6">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.serie"
                    :options="series"
                    :class="'bg-white'"
                    :clearable="true">
                    <template #option="{ data }">
                        <span v-text="`${data?.legible_serie} - ${data?.document_type?.name}`" class="d-block fw-bold"></span>
                        <small v-text="data?.branch?.name" class="d-block"></small>
                    </template>
                    <template #selected-option="{ label }">
                        <span v-text="truncate({value: label, length: 50})"></span>
                    </template>
                </v-select>
            </template>
        </InputSlot>
        <InputText
            v-model="lists.entity.filters.sequential"
            @enterKeyPressed="listEntity({})"
            hasDiv
            title="Secuencia"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            placeholder="Ejemplo: 203"
            xl="3"
            lg="6"/>
        <InputDate
            v-model="lists.entity.filters.issue_date"
            @enterKeyPressed="listEntity({})"
            hasDiv
            title="Fecha de emisión"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            xl="3"
            lg="6"/>
        <InputSlot
            hasDiv
            title="Cliente"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            xl="6"
            lg="6">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.holder"
                    :options="holders"
                    :class="'bg-white'"
                    :clearable="true">
                    <template #option="{ label }">
                        <span v-text="truncate({value: label, length: 50})" class="d-block"></span>
                    </template>
                    <template #selected-option="{ label }">
                        <span v-text="truncate({value: label, length: 50})"></span>
                    </template>
                </v-select>
            </template>
        </InputSlot>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            xl="6"
            lg="6">
            <template v-slot:input>
                <button class="btn btn-primary waves-effect" type="button" @click="listEntity({})">
                    <i class="fa fa-search"></i>
                    <span class="ms-1">Buscar</span>
                </button>
                <button class="btn btn-primary waves-effect ms-3" @click="modalCreateUpdateEntity({})">
                    <i class="fa-solid fa-cash-register"></i>
                    <span class="ms-1">Nuevo</span>
                </button>
            </template>
        </InputSlot>
    </div>
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
                <template v-if="lists.entity.extras.loading">
                    <tr class="text-center">
                        <td colspan="99">
                            <Loader/>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <template v-if="lists.entity.records.total > 0">
                        <tr v-for="record in lists.entity.records.data" :key="record.id" class="text-center">
                            <td class="text-start">
                                <span v-text="record.serie_sequential" class="fw-bold d-block"></span>
                                <small v-text="record.serie?.document_type?.name" class="d-block"></small>
                            </td>
                            <td class="text-start">
                                <span v-text="record.holder?.name" class="fw-bold d-block"></span>
                                <small v-text="record.holder?.document_number" class="d-block"></small>
                            </td>
                            <td v-text="record.formatted_issue_date"></td>
                            <td>
                                <span v-text="record.currency?.sign ?? ''"></span>
                                <span v-text="record.total" class="ms-1"></span>
                            </td>
                            <td>
                                <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm rounded-pill btn-success waves-effect m-1" @click="modalPrintEntity({record})" data-bs-toggle="tooltip" data-bs-placement="top" title="Imprimir">
                                    <i class="fa fa-print"></i>
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
                </template>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center" v-if="!lists.entity.extras.loading">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
    </div>

    <PrintSale :modalId="forms.entity.createUpdate.extras.modals.finished.id" :data="forms.entity.createUpdate.extras.modals.finished.data"/>
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
        Utils.navbarItem("menu-item-list-sales", {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            await this.listEntity({});

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
                        serie: null,
                        sequential: "",
                        issue_date: "",
                        holder: null
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
                                finished: {
                                    id: Utils.uuid(),
                                    data: {
                                        id: null
                                    }
                                }
                            }
                        },
                        data: {},
                        errors: {}
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
        // Init
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, showAlert: true});

            this.options.branches    = initParams.data?.config?.branches;
            this.options.currencies  = initParams.data?.config?.currencies;
            this.options.holders     = initParams.data?.config?.customers;
            this.options.items       = initParams.data?.config?.items;
            this.options.salesHeader = initParams.data?.config?.salesHeader;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return true;

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {serie_id: filters?.serie?.code, sequential: filters?.sequential, holder_id: filters?.holder?.code, issue_date: filters.issue_date};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: {...filterJson}}))?.data;
            this.lists.entity.extras.loading = false;

        },
        modalCreateUpdateEntity({record = null}) {

            window.location.href = this.config.entity.routes.create;

        },
        modalPrintEntity({record = null}) {

            this.forms.entity.createUpdate.extras.modals.finished.data = record;

            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.finished.id});

        },
        // Utils forms
        clearForm({functionName}) {

            switch(functionName) {
                case "createUpdateEntity":
                    //
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["createUpdateEntity"].includes(functionName)) {

                //

            }

        },
        validateForm({functionName, form = null}) {

            let result = {
                bool: true
            };

            if(["createUpdateEntity"].includes(functionName)) {

                //

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        truncate({value, length}) {

            return Utils.truncate({value, length});

        }
    },
    computed: {
        series: function() {

            let series = [];

            let branches = (this.options?.branches?.records ?? []);

            for(let branch of branches) {

                for(let branchSerie of branch.series) {

                    series.push({code: branchSerie.id, label: `(${branch?.name}) ${branchSerie.legible_serie} - ${branchSerie?.document_type?.name}`, data: {...branchSerie, branch}});

                }

            }

            return series;

        },
        holders: function() {

            return this.options?.holders?.records.map(e => ({code: e.id, label: `${e.document_number} - ${e.name}`, data: e}));

        }
    }
};
</script>
