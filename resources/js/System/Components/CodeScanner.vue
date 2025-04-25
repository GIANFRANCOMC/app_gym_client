<template>
    <div>
        <select v-model="selectedCameraId" class="form-select mb-2" v-if="cameras.length > 0">
            <template v-for="camera in cameras" :key="camera.id">
                <option :value="camera.id" v-text="camera.label || 'Cámara ' + camera.id"></option>
            </template>
        </select>
        <div ref="scannerContainer" style="width: 100%;"></div>
        <template v-if="cameras.length > 0">
            <div class="controls mt-2">
                <button @click="startScanner()" v-if="!isScanning" type="button" class="btn btn-success waves-effect">📷 Escanear QR</button>
                <button @click="stopScanner(false)" v-if="isScanning" type="button" class="btn btn-danger waves-effect">🛑 Detener</button>
            </div>
        </template>
        <template v-else>
            <div class="d-flex">
                <span class="alert alert-danger w-100">Sin alguna cámara detectada.</span>
            </div>
        </template>
    </div>
</template>

<script>
    import * as Alerts  from "../Helpers/Alerts.js";

    export default {
        name: "CodeScanner",
        emits: ["result"],
        computed: {
            canScan: function() {

                return this.limitScan == -1 || this.counterScan < this.limitScan;

            }
        },
        props: {
            qrbox: {
                type: Number,
                default: 250
            },
            fps: {
                type: Number,
                default: 10
            },
            limitScan: {
                type: Number,
                default: -1
            }
        },
        data() {
            return {
                scanner: null,
                isScanning: false,
                counterScan: 0,
                cameras: [],
                selectedCameraId: null
            };
        },
        methods: {
            async startScanner() {

                const config = {
                    fps: this.fps,
                    qrbox: { width: this.qrbox, height: this.qrbox }
                };

                const scannerId = this.$refs.scannerContainer.id || "html5qr-code";

                if(!this.scanner) {

                    this.scanner = new window.Html5Qrcode(scannerId);

                }

                const cameras = await window.Html5Qrcode.getCameras();

                if(cameras.length > 0) {

                    this.scanner.start(
                        this.selectedCameraId,
                        config,
                        (decodedText, decodedResult) => {

                            if(this.limitScan != -1) {

                                this.counterScan++;

                            }

                            this.$emit("result", decodedText, decodedResult);
                            this.stopScanner(true);

                        }
                    );

                    this.isScanning = true;

                }else {

                    Alerts.generateAlert({type: "error", msgContent: `No se detectaron cámaras.`});

                }

            },
            stopScanner(initPostValidated = false) {

                if(this.scanner && this.isScanning) {

                    this.scanner.stop()
                    .then(() => {

                        this.isScanning = false;

                    })
                    .catch(err => {

                        console.error("Error al detener escáner", err);

                    });

                    if(initPostValidated && this.canScan) {

                        this.startScanner();

                    }

                }

            },
            decrementScanCounter() {

                if(this.counterScan != -1) {

                    this.counterScan--;

                }

            }
        },
        mounted() {

            // Set force: ID
            if(!this.$refs.scannerContainer.id) {

                this.$refs.scannerContainer.id = "html5qr-code";

            }

            window.Html5Qrcode
            .getCameras()
            .then(cameras => {

                this.cameras = cameras;

                if(cameras.length > 0) {

                    let camerasBack = cameras.filter(e => (e.label.toLowerCase()).includes("back"));

                    this.selectedCameraId = camerasBack.length > 0 ? camerasBack[0].id :cameras[0].id;

                }

            })
            .catch(err => {

                console.error("Error al obtener cámaras:", err);
                Alerts.generateAlert({ type: "error", msgContent: `Error al obtener cámaras.` });

            });

        },
        watch: {
            selectedCameraId(newVal, oldVal) {

                if(this.isScanning && newVal !== oldVal) {

                    this.stopScanner(true);

                }

            }
        }
    };
</script>

<style scoped>
    .controls {
        display: flex;
        gap: 10px;
    }
</style>
