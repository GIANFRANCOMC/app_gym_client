<template>
    <Breadcrumb :list="[config.entity.page]"/>
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <InputText
                v-model="lists.entity.filters.general"
                :showDiv="true"
                title="Buscar"
                :titleClass="['fw-bold', 'colon-at-end', 'ms-1', 'fs-5']"
                placeholder="Ingrese la búsqueda"
                @enter-key-pressed="listEntity({})">
                <template v-slot:inputGroupAppend>
                    <button class="btn btn-primary waves-effect" type="button" @click="listEntity({})" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Nombre, Descripción.">
                        <i class="fa fa-search"></i>
                    </button>
                </template>
            </InputText>
        </div>
        <div class="align-self-end ms-3">
            <button class="btn btn-primary waves-effect" @click="modalCreateEntity({})">
                <i class="fa fa-plus"></i>
                <span class="ms-1">Agregar</span>
            </button>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr class="text-uppercase text-center">
                        <th class="fw-bold col-1">NOMBRE</th>
                        <th class="fw-bold col-1">DESCRIPCIÓN</th>
                        <th class="fw-bold col-1">PRECIO</th>
                        <th class="fw-bold col-1">ESTADO</th>
                        <th class="fw-bold col-1">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <template v-if="lists.entity.extras.loading">
                        <tr class="text-center">
                            <td colspan="99">
                                <i class="fas fa-spinner fa-spin fa-3x my-3"></i>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <template v-if="lists.entity.records.data && lists.entity.records.data.length > 0">
                            <tr v-for="record in lists.entity.records.data" :key="record.id" class="text-center">
                                <td v-text="record.name"></td>
                                <td v-text="record.description"></td>
                                <td v-text="record.price"></td>
                                <td>
                                    <span :class="['badge', 'text-capitalize', { 'bg-label-success': ['active'].includes(record.status), 'bg-label-danger': ['inactive'].includes(record.status) }]" v-text="record.formatted_status"></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect waves-light">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td class="text-center" colspan="99" v-text="config.messages.withoutResults"></td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center" v-if="!lists.entity.extras.loading">
        <Paginator :links="lists.entity.records.links" @clickPage="listEntity"/>
    </div>
</template>

<script>
import * as Constants from "../Helpers/constants.js";
import * as Requests from "../Helpers/Requests.js";
import * as Alerts from "../Helpers/alerts.js";
import * as Utils from "../Helpers/utils.js";

import Breadcrumb from "../Components/Breadcrumb.vue";
import Paginator from "../Components/Paginator.vue";

import InputText from "../Components/InputText.vue";
import InputSelect from "../Components/InputSelect.vue";
import InputSelect2 from "../Components/InputSelect2.vue";
import InputDate from "../Components/InputDate.vue";
import InputNumber from "../Components/InputNumber.vue";

export default {
    components: {
        Breadcrumb,
        Paginator,
        InputDate,
        InputText,
        InputSelect,
        InputSelect2,
        InputNumber
    },
    mounted: async function () {

        Utils.openNavbarItem("menu-list-items");
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
                        loading: true,
                        route: Requests.config({entity: "items", type: "list"})
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
                    add: {
                        modals: {
                            default: "addItemModal"
                        },
                        select2: {},
                        data: {
                            name: "",
                            description: "",
                            price: "",
                            status: ""
                        },
                        options: {
                            status: [
                                {code: "active", label: "Activo"},
                                {code: "inactive", label: "Inactivo"}
                            ]
                        },
                        errors: {}
                    }
                }
            },
            config: {
                ...Constants.generalConfig,
                entity: {
                    ...Requests.config({entity: "items"}),
                    page: {
                        title: "Productos",
                        active: true
                    }
                }
            }
        };
    },
    methods: {
        async initParams({}) {

            let initParams = await Requests.get({route: this.config.entity.routes.initParams});

            return initParams?.bool && initParams?.data?.bool;

        },
        async initOthers({}) {

            return true;

        },
        async listEntity({url = null}) {

            this.lists.entity.extras.loading = true;
            this.lists.entity.records        = (await Requests.get({route: url || this.lists.entity.extras.route, data: this.lists.entity.filters}))?.data;
            this.lists.entity.extras.loading = false;

        },
        modalCreateEntity() {

            //

        },
        createEntity() {

            //

        },
        modalUpdateEntity() {

            //

        },
        updateEntity() {

            //

        },
        // Forms
        clearForm({functionName}) {

            switch(functionName) {
                case "modalCreateEntity":
                case "createEntity":
                    break;

                case "modalUpdateEntity":
                case "updateEntity":
                    break;
            }

        },
        clearFormErrors({functionName}) {

            switch(functionName) {
                case "modalCreateEntity":
                case "createEntity":
                    break;

                case "modalUpdateEntity":
                case "updateEntity":
                    break;
            }

        },
        setFormErrors({functionName, errors = []}) {

            switch(functionName) {
                case "modalCreateEntity":
                case "createEntity":
                    break;

                case "modalUpdateEntity":
                case "updateEntity":
                    break;
            }

        },
        // Utils
        isDefined({value}) {

            return isDefined({value});

        }
    }
};
</script>
