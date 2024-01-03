<template>
    <div class="d-flex flex-row mb-4">
        <div class="align-self-start">
            <div class="fw-bold">
                <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Búsqueda por: Número de documento, Nombres, Apellidos." role="button"></i>
                <span class="ms-1">Buscar:</span>
            </div>
            <div class="input-group">
                <inputText
                    v-model="filters.general"
                    @enter-key-pressed="list()"
                    placeholder="Ingrese la búsqueda">
                </inputText>
                <button class="btn btn-primary waves-effect" type="button" @click="list()">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div class="align-self-end ms-3">
            <button class="btn btn-primary">
                <i class="fa fa-plus"></i>
                <span class="ms-1">Agregar</span>
            </button>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr class="text-nowrap">
                        <th class="align-middle text-center fw-bold col-1">Número de<br/>documento</th>
                        <th class="align-middle text-center fw-bold col-2">Nombres</th>
                        <th class="align-middle text-center fw-bold col-2">Apellidos</th>
                        <th class="align-middle text-center fw-bold col-1" colspan="2">Fecha de Nacimiento</th>
                        <th class="align-middle text-center fw-bold col-1">Estado</th>
                        <th class="align-middle text-center fw-bold col-1">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr v-for="record in records.data" :key="record.id" class="align-middle">
                        <td class="text-center" v-text="record.number_document"></td>
                        <td class="text-center" v-text="record.last_name"></td>
                        <td class="text-center" v-text="record.first_name"></td>
                        <td class="text-center">
                            <span v-text="record.formatted_birth_date"></span>
                        </td>
                        <td class="text-start">
                            <span class="badge rounded-pill bg-label-info">{{ record.age }} años</span>
                        </td>
                        <td class="text-center">
                            <span :class="['badge', 'text-capitalize', { 'bg-label-primary': record.status === 'active', 'bg-label-danger': record.status === 'inactive' }]" v-text="record.formatted_status"></span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm rounded-pill btn-warning waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm rounded-pill btn-danger waves-effect waves-light ms-1">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <template v-if="records.data && records.data.length > 0">
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination">
                    <template v-for="link in records.links">
                        <li :class="['page-item', link.active ? 'active' : (link.url ? '' : 'disabled')]">
                            <a class="page-link waves-effect me-1" href="javascript:void(0);" @click="list(link.url)" v-html="link.label"></a>
                        </li>
                    </template>
                </ul>
            </nav>
        </template>
    </div>
</template>

<script>
import { requestRoute } from "../helpers/constants.js";
import { showLoading, hideSwal } from "../helpers/sweetalert2.js";
import { initTooltips, hideTooltips } from "../helpers/tooltips.js";
import {  } from "../helpers/utils.js";

import axios from "axios";
import inputText from "../componentes/inputText.vue";

export default {
    components: {
        inputText
    },
    mounted: async function () {

        await this.list();
        initTooltips();

    },
    data() {
        return {
            filters: {
                general: "",
            },
            records: {
                total: 0
            }
        };
    },
    methods: {
        async list(url) {

            return new Promise(resolve => {

                showLoading("Cargando listado.");

                let params = {};
                params.general = this.filters.general;

                let requestUrl    = url || `${requestRoute}/customers/list`,
                    requestConfig = { params: params };

                axios
                .get(requestUrl, requestConfig)
                .then((response) => {

                    this.records = response.data;

                })
                .catch((error) => {

                })
                .finally(function () {

                    hideSwal();
                    initTooltips();
                    resolve(true);

                });

            });

        }
    }
};
</script>
