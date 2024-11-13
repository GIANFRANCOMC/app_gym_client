<template>
    <div class="modal fade" :id="modalId" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase fw-bold"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-1">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-start">
                            <span class="fw-semibold fs-5 ms-2" v-text="'Documento:'"></span>
                            <span class="fw-bold text-uppercase fs-5 ms-2" v-text="title ?? data?.serie_sequential"></span>
                        </div>
                    </div>
                    <div class="row justify-content-center g-1 mt-4">
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3" v-if="a4">
                            <div class="text-center cursor-pointer p-1" @click="exportpp({type: 'a4'})">
                                <div class="badge bg-primary p-3 rounded mb-1">
                                    <i class="fa fa-print fs-3"></i>
                                </div>
                                <br/>
                                <span class="fw-semibold">A4</span>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3" v-if="mm80">
                            <div class="text-center cursor-pointer p-1" @click="exportpp({type: 'mm80'})">
                                <div class="badge bg-primary p-3 rounded mb-1">
                                    <i class="fa-solid fa-note-sticky fs-3"></i>
                                </div>
                                <br/>
                                <span class="fw-semibold">80MM</span>
                            </div>
                        </div>
                        <slot name="extraGroupAppend"></slot>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import * as Requests  from "../../Helpers/Requests.js";

export default {
    name: "PrintSale",
    emits: [],
    props: {
        modalId: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: false
        },
        a4: {
            type: Boolean,
            required: false,
            default: true
        },
        mm80: {
            type: Boolean,
            required: false,
            default: true
        },
        data: {
            required: false
        }
    },
    computed: {
        //
    },
    methods: {
        // export({type, resource}) {
        exportpp({type}) {

            let data = this.data;

            // Requests.getFile({route: Requests.config({entity: "exports", type: "default"}), data: {id: data.id}, config: {responseType: 'blob'}});

            window.open(`${Requests.config({entity: "exports", type: "default"})}?document=${data?.hash_id}&type=${type}`, "_blank");

        }
    }
};
</script>
