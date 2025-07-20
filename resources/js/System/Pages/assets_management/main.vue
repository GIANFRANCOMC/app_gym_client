<template>
    <Breadcrumb :list="breadcrumbTitles"/>

    <!-- Content -->
    <div class="row align-items-end g-3 mb-3 mb-md-4">
        <InputSlot
            hasDiv
            title="Sucursal"
            :titleClass="[config.forms.classes.title]"
            xl="6"
            lg="7">
            <template v-slot:input>
                <v-select
                    v-model="lists.entity.filters.branch"
                    :options="branches"
                    :class="config.forms.classes.select2"
                    :clearable="false"
                    :searchable="false"/>
            </template>
        </InputSlot>
        <InputSlot
            hasDiv
            :isInputGroup="false"
            :divInputClass="['d-flex flex-wrap justify-content-start gap-2 gap-md-3']"
            xl="6"
            lg="5">
            <template v-slot:input>
                <button type="button" class="btn btn-info-1 waves-effect" @click="listEntity({})" :disabled="lists.entity.extras.loading">
                    <i class="fa fa-search"></i>
                    <span class="ms-2">Buscar</span>
                </button>
            </template>
        </InputSlot>
    </div>
    <div class="row g-3" v-if="isDefined({value: lists.entity.filters.branch?.code})">
        <template v-if="!lists.entity.extras.loading">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <h5 class="fw-bold mb-2 text-uppercase ps-1">Activos disponibles</h5>
                <div class="list-group" v-if="availableAssets.length > 0">
                    <div v-for="record in availableAssets" :key="record.code" class="list-group-item bg-white">
                        <div class="d-flex justify-content-between align-items-center gap-2 gap-md-3">
                            <div>
                                <span v-text="record.internal_code" class="fw-bold d-block"></span>
                                <span v-text="record.name" class="d-block"></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-success" @click="assignAssetToBranch(record)" v-if="hasAssetToBranch(record)">Agregar</button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center">
                    <WithoutData type="image"/>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <h5 class="fw-bold mb-2 text-uppercase ps-1">Activos asignados a la sucursal</h5>
                <div class="list-group" v-if="lists.entity.records.total > 0">
                    <div v-for="record in lists.entity.records.data" :key="record.id" class="list-group-item bg-white">
                        <div class="d-flex justify-content-between align-items-center gap-2 gap-md-3">
                            <div>
                                <span v-text="record.asset?.internal_code" class="fw-bold d-block"></span>
                                <span v-text="record.asset?.name" class="d-block mb-1"></span>
                                <span :class="['badge', 'fw-semibold', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-primary': ['maintenance'].includes(record.status), 'bg-label-danger': ['retired'].includes(record.status) }]" v-text="record.formatted_status"></span>
                            </div>
                            <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 gap-md-3">
                                <button class="btn btn-sm btn-warning" @click="modalAssign(record)" v-if="['active', 'maintenance'].includes(record.status)">Editar</button>
                                <button class="btn btn-sm btn-primary" @click="modalAssignToUser(record)" v-if="record.quantity > 0 && ['active', 'maintenance'].includes(record.status)">Gestionar asignación</button>
                                <button class="btn btn-sm btn-danger" @click="unassignAssetFromBranch(record)" v-if="['active', 'maintenance'].includes(record.status)">Quitar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center">
                    <WithoutData type="image"/>
                </div>
            </div>
        </template>
    </div>

    <!-- Modals -->
    <div class="modal fade" :id="forms.entity.assign.extras.modals.default.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.assign.extras.modals.default.titles[isAssign ? 'update' : 'store']"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label class="fw-bold colon-at-end">Activo</label>
                            <div>
                                <span v-text="forms.entity.assign.data?.asset?.name"></span>
                                (<span v-text="forms.entity.assign.data?.asset?.internal_code" class="fw-bold"></span>)
                            </div>
                        </div>
                        <InputNumber
                            v-model="forms.entity.assign.data.quantity"
                            hasDiv
                            title="Cantidad"
                            hasTextBottom
                            :textBottomInfo="forms.entity.assign.errors?.quantity"
                            xl="4"
                            lg="4"/>
                        <InputNumber
                            v-model="forms.entity.assign.data.acquisition_value"
                            hasDiv
                            title="Valor de adquisición"
                            hasTextBottom
                            :textBottomInfo="forms.entity.assign.errors?.acquisition_value"
                            xl="4"
                            lg="4"/>
                        <InputDate
                            v-model="forms.entity.assign.data.acquisition_date"
                            hasDiv
                            title="Fecha de adquisición"
                            hasTextBottom
                            :textBottomInfo="forms.entity.assign.errors?.acquisition_date"
                            xl="4"
                            lg="4"/>
                        <InputText
                            v-model="forms.entity.assign.data.note"
                            hasDiv
                            title="Nota"
                            maxlength="100"
                            hasTextBottom
                            :textBottomInfo="forms.entity.assign.errors?.note"
                            xl="8"
                            lg="8"/>
                        <InputSlot
                            hasDiv
                            title="Estado"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.assign.errors?.status"
                            xl="4"
                            lg="4">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.assign.data.status"
                                    :options="statuses"
                                    :clearable="false"
                                    :searchable="false"/>
                            </template>
                        </InputSlot>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', isAssign ? 'btn-warning' : 'btn-primary']" @click="assign()">
                        <i class="fa fa-save"></i>
                        <span class="ms-2">Guardar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" :id="forms.entity.assignToUser.extras.modals.default.id" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold" v-text="forms.entity.assignToUser.extras.modals.default.titles.default"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label class="fw-bold colon-at-end">Activo</label>
                            <div>
                                <span v-text="forms.entity.assignToUser.data?.asset?.name"></span>
                                (<span v-text="forms.entity.assignToUser.data?.asset?.internal_code" class="fw-bold"></span>)
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label class="fw-bold colon-at-end">Cantidad total</label>
                            <div>
                                <span v-text="forms.entity.assignToUser.data?.quantity"></span>
                            </div>
                        </div>
                    </div>
                    <div v-if="['stock'].includes(forms.entity.assignToUser.data?.asset?.management_type)">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label class="fw-bold colon-at-end">Colaborador asignado</label>
                            <div>
                                <span v-text="forms.entity.assignToUser?.data?.assignments[0]?.user?.name"></span>
                            </div>
                        </div>
                        <InputSlot
                            hasDiv
                            title="Cambiar colaborador asignado"
                            isRequired
                            hasTextBottom
                            :textBottomInfo="forms.entity.assignToUser.errors?.user"
                            xl="12"
                            lg="12">
                            <template v-slot:input>
                                <v-select
                                    v-model="forms.entity.assignToUser.data.user"
                                    :options="users"
                                    :clearable="true"
                                    :searchable="true"/>
                            </template>
                        </InputSlot>
                    </div>
                    <div v-else-if="['unit'].includes(forms.entity.assignToUser.data?.asset?.management_type)" class="mt-2">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr class="text-center align-middle">
                                        <th class="bg-secondary text-white fw-semibold text-nowrap col-1">#</th>
                                        <th class="bg-secondary text-white fw-semibold text-nowrap col-2">COLABORADOR<br/>ASIGNADO</th>
                                        <th class="bg-secondary text-white fw-semibold text-nowrap col-1">CANTIDAD</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0 bg-white">
                                    <template v-if="(forms.entity.assignToUser?.data?.assignments ?? []).length > 0">
                                        <template v-for="(record, keyRecord) in forms.entity.assignToUser?.data?.assignments" :key="record.id">
                                            <template v-if="isNumber(record.id)">
                                                <tr class="text-center">
                                                    <td v-text="keyRecord + 1"></td>
                                                    <td v-text="record?.user?.name" class="text-start fw-bold"></td>
                                                </tr>
                                            </template>
                                            <template v-else>
                                                <tr class="text-center">
                                                    <td v-text="keyRecord + 1"></td>
                                                    <td class="text-start">
                                                        <InputSlot
                                                            :hasDiv="false">
                                                            <template v-slot:input>
                                                                <v-select
                                                                    v-model="record.user"
                                                                    :options="users"
                                                                    :clearable="false"
                                                                    :searchable="true"/>
                                                            </template>
                                                        </InputSlot>
                                                    </td>
                                                    <td class="text-start">
                                                        <InputNumber
                                                            v-model="record.quantity"
                                                            :hasDiv="false"/>
                                                    </td>
                                                </tr>
                                            </template>
                                        </template>
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
                        <div class="d-flex justify-content-start align-items-center flex-grap mt-1">
                            <a href="javascript:void(0)" @click="addAssignment({})" class="fw-bold">
                                <i class="fa fa-plus-circle"></i>
                                <span class="ms-1">Agregar asignación</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" :class="['btn waves-effect', 'btn-primary']" @click="assignToUser()">
                        <i class="fa fa-save"></i>
                        <span class="ms-2">Guardar</span>
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

export default {
    components: {
        //
    },
    mounted: async function() {

        Utils.navbarItem("menu-item-infrastructure", {addClass: "open"});
        Utils.navbarItem(this.config.entity.page.menu.id, {});
        Alerts.swals({type: "initParams"});

        let initParams = await this.initParams({}),
            initOthers = await this.initOthers({});

        if(initParams && initOthers) {

            Alerts.swals({show: false});
            // this.listEntity({});

        }

    },
    data() {
        return {
            lists: {
                entity: {
                    extras: {
                        loading: false,
                        route: Requests.config({entity: "assets_management", type: "list"})
                    },
                    filters: {
                        branch: null
                    },
                    records: {
                        total: 0
                    }
                }
            },
            forms: {
                entity: {
                    createUpdate: {
                        data: {},
                        errors: {}
                    },
                    assign: {
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
                        data: {},
                        errors: {}
                    },
                    assignToUser: {
                        extras: {
                            modals: {
                                default: {
                                    id: Utils.uuid(),
                                    titles: {
                                        default: "Gestionar asignación"
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
                    ...Requests.config({entity: "assets_management"}),
                    page: {
                        title: "Gestión de activos",
                        active: true,
                        menu: {
                            id: "menu-item-infrastructure-assets_management"
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

            this.options.assets       = initParams.data?.config?.assets;
            this.options.branches     = initParams.data?.config?.branches;
            this.options.branchAssets = initParams.data?.config?.branchAssets;
            this.options.users        = initParams.data?.config?.users;

            return Requests.valid({result: initParams});

        },
        async initOthers({}) {

            return new Promise(resolve => {

                this.lists.entity.filters.branch = this.branches[0];

                resolve(true);

            });

        },
        // Entity forms
        async listEntity({url = null}) {

            let filters = Utils.cloneJson(this.lists.entity.filters);
            const filterJson = {branch_id: filters?.branch?.code};

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: filterJson}))?.data;
            this.lists.entity.extras.loading = false;

        },
        // Forms
        hasAssetToBranch(record) {

            return !this.lists.entity.records.data.some(e => e.asset_id == record.id && ["active", "maintenance"].includes(e.status));

        },
        async assignAssetToBranch(record) {

            const functionName = "assignAssetToBranch";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson({branch_id: this.lists.entity.filters.branch?.code, items: [{id: record.id}]});

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.items.forEach(item => {

                    item.asset_id = item.id;

                    delete item.id;

                });

                let assignAssetToBranch = await Requests.post({route: this.config.entity.routes.assignAssetToBranch, data: form});

                if(Requests.valid({result: assignAssetToBranch})) {

                    // Alerts.toastrs({type: "success", subtitle: assignAssetToBranch?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", msgContent: assignAssetToBranch?.data?.msg});

                    // this.clearForm({functionName});
                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: assignAssetToBranch?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: assignAssetToBranch?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                // Alerts.swals({show: false});
                Alerts.generateAlert({messages: validateForm?.msg, msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        async unassignAssetFromBranch(record) {

            const functionName = "unassignAssetFromBranch";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson({branch_id: this.lists.entity.filters.branch?.code, items: [{id: record.asset_id}]});

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.items.forEach(item => {

                    item.asset_id = item.id;

                    delete item.id;

                });

                let unassignAssetFromBranch = await Requests.post({route: this.config.entity.routes.unassignAssetFromBranch, data: form});

                if(Requests.valid({result: unassignAssetFromBranch})) {

                    // Alerts.toastrs({type: "success", subtitle: unassignAssetFromBranch?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", msgContent: unassignAssetFromBranch?.data?.msg});

                    // this.clearForm({functionName});
                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: unassignAssetFromBranch?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: unassignAssetFromBranch?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                // Alerts.swals({show: false});
                Alerts.generateAlert({messages: validateForm?.msg, msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        modalAssign(record) {

            const functionName = "modalAssign";

            // Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                let status = this.statuses.find(e => e.code === record?.status);

                this.forms.entity.assign.data = {...record, status};

            }else {

                //

            }

            // Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.assign.extras.modals.default.id});
            this.tooltips({show: true, time: 500});

        },
        async assign() {

            const functionName = "assign";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.assign.data);

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.status = form?.status?.code;

                delete form.asset;
                delete form.formatted_status;
                delete form.created_at;
                delete form.created_by;
                delete form.updated_at;
                delete form.updated_by;

                let assign = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.assign, data: form, id: form.id}) :
                                                                       Requests.post({route: this.config.entity.routes.assign, data: form}));

                if(Requests.valid({result: assign})) {

                    Alerts.modals({type: "hide", id: this.forms.entity.assign.extras.modals.default.id});
                    // Alerts.toastrs({type: "success", subtitle: assign?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", msgContent: assign?.data?.msg});

                    this.clearForm({functionName});
                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: assign?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: assign?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                // Alerts.swals({show: false});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        // Forms - To user
        addAssignment() {

            this.forms.entity.assignToUser.data.assignments.push({id: Utils.uuid(), user: {code: "", label: ""}, quantity: 0});

        },
        async modalAssignToUser(record) {

            const functionName = "modalAssignToUser";

            Alerts.swals({});
            this.clearForm({functionName});
            this.formErrors({functionName, type: "clear"});

            if(this.isDefined({value: record})) {

                const filterJson = {branch_id: record?.branch_id, asset_id: record?.asset_id};

                let getAssetAssignments = (await Requests.get({route: this.config.entity.routes.getAssetAssignments, data: filterJson}));
                let assignments = Requests.valid({result: getAssetAssignments}) ? getAssetAssignments?.data?.list : [];

                let status = this.statuses.find(e => e.code === record?.status);

                this.forms.entity.assignToUser.data = {...record, status, assignments};

            }else {

                //

            }

            Alerts.swals({show: false});
            Alerts.modals({type: "show", id: this.forms.entity.assignToUser.extras.modals.default.id, timeout: 300});
            this.tooltips({show: true, time: 500});

        },
        async assignToUser() {

            const functionName = "assignToUser";

            Alerts.swals({});
            this.formErrors({functionName, type: "clear"});

            let form = Utils.cloneJson(this.forms.entity.assignToUser.data);

            const validateForm = this.validateForm({functionName, form, extras: {type: "descriptive"}});

            if(validateForm?.bool) {

                form.user_id = form.user?.code;
                form.status  = form?.status?.code;

                delete form.asset;
                delete form.user;
                delete form.formatted_status;
                delete form.created_at;
                delete form.created_by;
                delete form.updated_at;
                delete form.updated_by;

                let assignToUser = await (this.isDefined({value: form.id}) ? Requests.patch({route: this.config.entity.routes.assignToUser, data: form, id: form.id}) :
                                                                             Requests.post({route: this.config.entity.routes.assignToUser, data: form}));

                if(Requests.valid({result: assignToUser})) {

                    Alerts.modals({type: "hide", id: this.forms.entity.assignToUser.extras.modals.default.id});
                    // Alerts.toastrs({type: "success", subtitle: assignToUser?.data?.msg});
                    // Alerts.swals({show: false});
                    Alerts.generateAlert({type: "success", msgContent: assignToUser?.data?.msg});

                    this.clearForm({functionName});
                    this.listEntity({url: `${this.lists.entity.extras.route}?page=${this.lists.entity.records?.current_page ?? 1}`});

                }else {

                    this.formErrors({functionName, type: "set", errors: assignToUser?.errors ?? []});
                    Alerts.toastrs({type: "error", subtitle: assignToUser?.data?.msg});
                    Alerts.swals({show: false});

                }

            }else {

                // this.formErrors({functionName, type: "set", errors: validateForm});
                // Alerts.toastrs({type: "error", subtitle: this.config.messages.errorValidate});
                // Alerts.swals({show: false});
                Alerts.generateAlert({messages: Utils.getErrors({errors: validateForm}), msgContent: `<div class="fw-semibold mb-2">${this.config.messages.errorValidate}</div>`});

            }

        },
        // Forms utils
        clearForm({functionName}) {

            switch(functionName) {
                case "createUpdateEntity":
                    //
                    break;
            }

        },
        formErrors({functionName, type = "clear", errors = []}) {

            if(["createUpdateEntity"].includes(functionName)) {

                this.forms.entity.createUpdate.errors = ["set"].includes(type) ? errors : [];

            }

        },
        validateForm({functionName, form = null, extras = null}) {

            let result = {
                bool: true
            };

            if(["assignAssetToBranch"].includes(functionName)) {

                result.msg = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.branch_id})) {

                    result.msg.push(`${isDescriptive ? "Sucursal:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.items}) || (form.items).length === 0) {

                    result.msg.push(`${isDescriptive ? "Activo:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

            }else if(["unassignAssetFromBranch"].includes(functionName)) {

                result.msg = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.branch_id})) {

                    result.msg.push(`${isDescriptive ? "Sucursal:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.items}) || (form.items).length === 0) {

                    result.msg.push(`${isDescriptive ? "Activo:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

            }else if(["assign"].includes(functionName)) {

                result.msg = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.branch_id})) {

                    result.msg.push(`${isDescriptive ? "Sucursal:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.asset_id})) {

                    result.msg.push(`${isDescriptive ? "Activo:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(Number(form?.quantity) < 0) {

                    result.msg.push(`${isDescriptive ? "Cantidad:" : ""} ${this.config.forms.errors.functions.min.numeric(0)}`);
                    result.bool = false;

                }

                if(Number(form?.acquisition_value) < 0) {

                    result.msg.push(`${isDescriptive ? "Valor de adquisición:" : ""} ${this.config.forms.errors.functions.min.numeric(0)}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.status})) {

                    result.msg.push(`${isDescriptive ? "Estado:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

            }else if(["assignToUser"].includes(functionName)) {

                result.msg = [];

                const isDescriptive = ["descriptive"].includes(extras?.type);

                if(!this.isDefined({value: form?.branch_id})) {

                    result.msg.push(`${isDescriptive ? "Sucursal:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(!this.isDefined({value: form?.asset_id})) {

                    result.msg.push(`${isDescriptive ? "Activo:" : ""} ${this.config.forms.errors.labels.required}`);
                    result.bool = false;

                }

                if(["stock"].includes(form?.asset?.management_type)) {

                    if(!this.isDefined({value: form?.user})) {

                        result.msg.push(`${isDescriptive ? "Colaborador:" : ""} ${this.config.forms.errors.labels.required}`);
                        result.bool = false;

                    }

                }else if(["unit"].includes(form?.asset?.management_type)) {

                    let totalQuantity = form.assignments.reduce((acumulator, currentValue) => acumulator + Number(currentValue?.quantity), 0);

                    if(totalQuantity == 0) {

                        result.msg.push(`La suma de las cantidades asignadas debe ser mayor a 0.`);
                        result.bool = false;

                    }

                    if(totalQuantity > Number(form.quantity)) {

                        result.msg.push(`La suma de las cantidades asignadas debe ser menor o igual a la cantidad total.`);
                        result.bool = false;

                    }

                    for(let [keyDetail, detail] of Object.entries(form?.assignments)) {

                        let errorDetails = [];

                        if(!this.isDefined({value: detail?.user?.code})) {

                            errorDetails.push(`Colaborador: ${this.config.forms.errors.labels.required}`);
                            result.bool = false;

                        }

                        if(Number(detail?.quantity) < 0) {

                            errorDetails.push(`${isDescriptive ? "Cantidad:" : ""} ${this.config.forms.errors.functions.min.numeric(0)}`);
                            result.bool = false;

                        }

                        if(errorDetails.length > 0) {

                            result.msg.push(`<p class="mb-1">Asignación <b>#${parseInt(keyDetail) + 1}</b>:</p>${errorDetails.map(e => "<li>"+e+"</li>")}`);

                        }

                    }

                }

            }

            return result;

        },
        // Others
        isDefined({value}) {

            return Utils.isDefined({value});

        },
        tooltips({show = true, time = 10}) {

            Alerts.tooltips({show, time});

        },
        isNumber({value, minValue = 0}) {

            return Utils.isNumber({value, minValue});

        }
    },
    computed: {
        breadcrumbTitles: function() {

            return [{title: "Infraestructura"}, this.config.entity.page];

        },
        assets: function() {

            return this.options?.assets?.records.map(e => ({code: e.id, label: e.name, data: e}));

        },
        availableAssets: function() {

            return this.options?.assets?.records;

        },
        branches: function() {

            return this.options?.branches?.records.map(e => ({code: e.id, label: e.name, data: e}));

        },
        users: function() {

            return this.options?.users?.records.map(e => ({code: e.id, label: e.name, data: e}));

        },
        statuses: function() {

            return this.options?.branchAssets?.statuses.map(e => ({code: e.code, label: e.label}));

        },
        isAssign: function() {

            return this.isDefined({value: this.forms.entity.assign.data?.id});

        }
    },
    watch: {
        "lists.entity.filters.branch": function(newValue, oldValue) {

            if(this.isDefined({value: this.lists.entity.filters.branch?.code})) {

                this.listEntity({});

            }else {

                this.lists.entity.records = {
                    total: 0,
                    data: []
                };

            }

        }
    }
};
</script>
