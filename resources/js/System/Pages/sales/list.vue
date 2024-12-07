<template>
    <Breadcrumb :list="[config.entity.page, { title: 'Listado' }]"/>

    <!-- Records -->
    <div class="row align-items-end g-3 mb-4">
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
                    :class="config.forms.classes.select2"
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
                    :class="config.forms.classes.select2"
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
            title="Estado"
            :titleClass="['fw-bold', 'colon-at-end', 'fs-5']"
            xl="3"
            lg="6">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.status"
                    :options="statusses"
                    :class="config.forms.classes.select2"
                    :clearable="true">
                </v-select>
            </template>
        </InputSlot>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            xl="3"
            lg="6">
            <template v-slot:input>
                <button class="btn btn-primary waves-effect" type="button" @click="listEntity({})">
                    <i class="fa fa-search"></i>
                    <span class="ms-1">Buscar</span>
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
                            <td>
                                <span v-text="record.formatted_issue_date" class="d-block"></span>
                                <span :class="['badge', 'd-block', 'mt-1',{ 'bg-label-success': record.diff_days_issue_date == 0, 'bg-label-warning': record.diff_days_issue_date != 0 }]" v-text="diffDaysLegible({diff: record.diff_days_issue_date})"></span>
                            </td>
                            <td>
                                <span v-text="record.currency?.sign ?? ''"></span>
                                <span v-text="record.total" class="ms-1"></span>
                            </td>
                            <td>
                                <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive', 'cancelled'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm rounded-pill btn-primary waves-effect m-1" @click="modalActionsEntity({record})" data-bs-toggle="tooltip" data-bs-placement="top" title="Acciones">
                                    <i class="fa fa-gear"></i>
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

    <PrintSale :modalId="forms.entity.createUpdate.extras.modals.finished.id" :data="forms.entity.createUpdate.extras.modals.finished.data">
        <template v-slot:extraGroupAppend>
            <div v-if="['active'].includes(forms.entity.createUpdate.extras.modals.finished.data?.status)" class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="text-center cursor-pointer p-1" @click="cancelEntity({})">
                    <div class="badge bg-danger p-3 rounded mb-1">
                        <i class="fa-solid fa-rectangle-xmark fs-3"></i>
                    </div>
                    <br/>
                    <span class="fw-semibold">Anular venta</span>
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
                        holder: null,
                        status: null
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

            let initParams = await Requests.get({route: this.config.entity.routes.initParams, data: {page: "list"}, showAlert: true});

            this.options.branches    = initParams.data?.config?.branches;
            this.options.holders     = initParams.data?.config?.customers;
            this.options.salesHeader = initParams.data?.config?.salesHeader;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {serie_id: filters?.serie?.code, sequential: filters?.sequential, holder_id: filters?.holder?.code, issue_date: filters.issue_date, status: filters?.status?.code};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: {...filterJson}}))?.data;
            this.lists.entity.extras.loading = false;

        },
        modalActionsEntity({record = null}) {

            this.forms.entity.createUpdate.extras.modals.finished.data = record;

            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.finished.id});

        },
        cancelEntity({}) {

            const functionName = "cancelEntity";

            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.createUpdate.extras.modals.finished.data);

            const validateForm = this.validateForm({functionName, form});

            if(validateForm?.bool) {

                Alerts.modals({type: "hide", id: this.forms.entity.createUpdate.extras.modals.finished.id});

                let el = this;

                Swal.fire({
                    html: `<span>¿Desea eliminar la venta con documento <b>${form?.serie_sequential}</b>?</span>`,
                    icon: "warning",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect",
                        cancelButton: "btn btn-secondary waves-effect ms-3"
                    }
                }).then(async function(result) {

                    if(result.isConfirmed) {

                        Alerts.swals({});

                        let cancel = await Requests.patch({route: el.config.entity.routes.cancel, id: form.id});

                        if(cancel?.bool && cancel?.data?.bool) {

                            Alerts.toastrs({type: "success", subtitle: cancel?.data?.msg});
                            Alerts.swals({show: false});

                            el.listEntity({})

                        }else {

                            Alerts.toastrs({type: "error", subtitle: cancel?.data?.msg});
                            Alerts.swals({show: false});

                        }

                    }else if(result.isDismissed) {

                        //

                    }

                })

            }

            Alerts.tooltips({show: false});

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

        },
        diffDaysLegible({diff}) {

            return Utils.diffDaysLegible({diff});

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

        },
        statusses: function() {

            return this.options?.salesHeader?.statusses.map(e => ({code: e.code, label: e.label}));

        }
    }
};
</script>
