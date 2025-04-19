<template>
    <div>
        <!-- Contenedor donde aparece la cámara -->
        <div ref="scannerContainer" style="width: 100%; max-width: 300px;"></div>

        <!-- Botones -->
        <div class="controls">
            <button @click="startScanner" type="button" class="btn btn-success waves-effect">📷 Escanear QR</button>
            <button @click="stopScanner(false)" v-if="isScanning" type="button" class="btn btn-danger waves-effect">🛑 Detener</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "QrcodeScanner",
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
                counterScan: 0
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

                if(cameras.length) {

                    this.scanner.start(
                        cameras[0].id,
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

        }
    };
</script>

<style scoped>
    .controls {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
</style>
