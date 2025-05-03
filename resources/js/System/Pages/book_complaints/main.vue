<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row align-items-end g-3 mb-3 mb-md-4">
        <InputSlot
            hasDiv
            title="Filtrar por"
            :titleClass="[config.forms.classes.title]"
            xl="3"
            lg="4">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.filter_by"
                    :options="filterByOptions"
                    :class="config.forms.classes.select2"
                    :clearable="false"
                    :searchable="false"/>
            </template>
        </InputSlot>
        <InputText
            v-model="lists.entity.filters.word"
            @enterKeyPressed="listEntity({})"
            hasDiv
            title="Búsqueda"
            :titleClass="[config.forms.classes.title]"
            :placeholder="'Buscar por ' + (lists.entity.filters.filter_by?.label || '...').toLowerCase()"
            xl="4"
            lg="4"/>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            :divInputClass="['d-flex flex-wrap justify-content-start gap-2 gap-md-3']"
            xl="5"
            lg="4">
            <template v-slot:input>
                <button type="button" class="btn btn-info-1 waves-effect" @click="listEntity({})" :disabled="lists.entity.extras.loading">
                    <i class="fa fa-search"></i>
                    <span class="ms-2">Buscar</span>
                </button>
            </template>
        </InputSlot>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-center align-middle">
                    <th class="bg-secondary text-white fw-semibold" style="width: 15%;"></th>
                    <th class="bg-secondary text-white fw-semibold" style="width: 20%;">NÚMERO DE DOCUMENTO</th>
                    <th class="bg-secondary text-white fw-semibold" style="width: 30%;">NOMBRE</th>
                    <th class="bg-secondary text-white fw-semibold min-w-150px" style="width: 35%;">CONTACTO</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0 bg-white">
                <template v-if="lists.entity.extras.loading">
                    <tr class="text-center">
                        <td colspan="99" class="py-4">
                            <Loader/>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <template v-if="lists.entity.records.total > 0">
                        <tr v-for="record in lists.entity.records.data" :key="record.id" class="text-center">
                            <td>
                                <div class="d-flex flex-nowrap align-items-center justify-content-start my-1">
                                    <span class="fw-semibold colon-at-end">Tipo</span>
                                    <span :class="['badge ms-2', 'bg-label-'+getType({record}).data?.color]">
                                        <i :class="['fa', getType({record}).data?.icon]"></i>
                                        <span v-text="getType({record})?.label" class="ms-2"></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-nowrap align-items-center justify-content-start my-1">
                                    <span class="fw-semibold colon-at-end">Estado</span>
                                    <span :class="['badge ms-2', 'fw-semibold', { 'bg-label-primary': ['in_progress'].includes(record.status), 'bg-label-success': ['resolved'].includes(record.status), 'bg-label-danger': ['pending'].includes(record.status) }]" v-text="record.formatted_status"></span>
                                </div>
                            </td>
                            <td>
                                <span v-text="record.document_number" class="d-block fw-bold"></span>
                                <span v-text="record.identity_document_type?.name" class="badge bg-label-primary fw-bold mt-1"></span>
                            </td>
                            <td v-text="record.name" class="text-start"></td>
                            <td class="text-start">
                                <div class="d-flex flex-nowrap align-items-center justify-content-start my-1">
                                    <i class="fa fa-envelope"></i>
                                    <span v-text="isDefined({value: record.email}) ? record.email : 'N/A'" class="ms-2"></span>
                                </div>
                                <div class="d-flex flex-nowrap align-items-center justify-content-start my-1">
                                    <i class="fa fa-phone"></i>
                                    <span v-text="isDefined({value: record.phone_number}) ? record.phone_number : 'N/A'" class="ms-2"></span>
                                </div>
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
    <div class="d-flex justify-content-center" v-if="!lists.entity.extras.loading && lists.entity.records?.total > 0">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
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

        Utils.navbarItem("menu-item-customer_care", {addClass: "open"});
        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            this.listEntity({});

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "book_complaints", type: "list"})
                    },
                    filters: {
                        filter_by: null,
                        word: ""
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
                                    id: Utils.uuid(),
                                    titles: {
                                        store: "Agregar",
                                        update: "Editar"
                                    }
                                }
                            }
                        },
                        data: {
                            id: null,
                            internal_code: "",
                            name: "",
                            description: "",
                            status: null
                        },
                        errors: {}
                    }
                }
            },
            options: {},
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "book_complaints"}),
                    page: {
                        title: "Libro de reclamaciones y sugerencias",
                        active: true,
                        menu: {
                            id: "menu-item-customer_care-book_complaints"
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

            this.options.bookComplaints = initParams.data?.config?.bookComplaints;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.lists.entity.filters.filter_by = this.filterByOptions[0];

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {filter_by: filters?.filter_by?.code, word: filters.word};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        getType({record = null}) {

            const types = this.types.filter(e => e.code === record?.type);

            return types.length > 0 ? types[0] : null;

        },
        modalCreateUpdateEntity({record = null}) {

            const functionName = "modalCreateUpdateEntity";

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                let status = this.statuses.filter(e => e.code === record?.status)[0];

                this.forms.entity.createUpdate.data.id            = record?.id;
                this.forms.entity.createUpdate.data.internal_code = record?.internal_code;
                this.forms.entity.createUpdate.data.name          = record?.name;
                this.forms.entity.createUpdate.data.description   = record?.description;
                this.forms.entity.createUpdate.data.status        = status;

            }else {

                this.setGenerateCode({length: 7, showAlert: false});
                this.forms.entity.createUpdate.data.status = this.statuses[0];

            }

            // Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.createUpdate.extras.modals.default.id});

        },
        async createUpdateEntity() {

            //

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateUpdateEntity":
                case "createUpdateEntity":
                    //
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["modalCreateUpdateEntity", "createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null, extras = null}) {

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
        tooltips({show = true, time = 10}) {

            Alerts.tooltips({show, time});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Atención al cliente"}, this.config.entity.page];

        },
        filterByOptions: function() {

            return [
                {code: "all", label: "Todos"},
                {code: "document_number", label: "Número de documento"},
                {code: "name", label: "Nombre"},
                {code: "email", label: "Correo electrónico"},
                {code: "phone_number", label: "Celular"}
            ];

        },
        types: function() {

            return this.options?.bookComplaints?.types.map(e => ({code: e.code, label: e.label, data: e}));

        },
        statuses: function() {

            return this.options?.bookComplaints?.statuses.map(e => ({code: e.code, label: e.label}));

        }
    },
    watch: {
        "lists.entity.filters.filter_by": function(newValue, oldValue) {

            // this.listEntity({});

        }
    }
};
</script>
