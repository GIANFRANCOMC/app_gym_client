<template>
    <div>
        <div class="overflow-auto">
            <div id="carnet" ref="carnet" class="card carnet-card mx-auto shadow-sm position-relative overflow-hidden border border-light" style="min-width: 450px;">
                <img src="/public/System/assets/img/utils/admin/app_navbar_transparent.png" class="watermark-logo img-fluid" style="width: 22%;" />
                <div class="card-header bg-primary border-primary py-2 d-flex justify-content-center align-items-center">
                    <span class="text-white my-0 fw-bold">CARNET</span>
                </div>
                <div class="card-body d-flex align-items-center p-3 carnet-body">
                    <div class="qr-container flex-shrink-0 p-2 bg-white border rounded">
                        <img :src="qrImage" alt="QR" class="img-fluid" style="width: 100px; height: 100px;" />
                    </div>
                    <div class="info-container flex-grow-1 ms-4">
                        <p class="h5 text-dark mb-1" v-text="customer?.name"></p>
                        <p class="text-secondary fw-bold mb-0">N° {{ customer?.document_number }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center gap-2 mt-3">
            <button @click="compartirCarnet" class="btn btn-primary">
                <i class="fa-solid fa-share-nodes"></i>
                <span class="ms-2">Compartir</span>
            </button>
            <!-- <button @click="descargarCarnet" class="btn btn-success">
                <i class="fa fa-download"></i>
                <span class="ms-2">Descargar</span>
            </button> -->
        </div>
    </div>
</template>

<script>
import QRCodeStyling from "qr-code-styling";
import html2canvas from "html2canvas";

export default {
    name: "CarnetCustomer",
    props: {
        customer: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            qrImage: ""
        };
    },
    mounted() {
        this.generarQR();
    },
    methods: {
        generarQR() {

            const dataJson = {
                identificator: this.customer?.document_number ?? "",
                name: this.customer?.name ?? ""
            };

            const qr = new QRCodeStyling({
                width: 200,
                height: 200,
                data: JSON.stringify(dataJson),
                dotsOptions: { color: "#000000", type: "rounded" },
                backgroundOptions: { color: "#ffffff" },
            });

            qr.getRawData("png")
            .then((blob) => {

                this.qrImage = URL.createObjectURL(blob);

            });

        },
        async compartirCarnet() {

            const original = this.$refs.carnet;
            const cloned = original.cloneNode(true);

            Object.assign(cloned.style, {
                position: 'absolute',
                top: '0',
                left: '0',
                width: '450px',
                maxWidth: 'none',
                transform: 'scale(1)',
                backgroundColor: '#ffffff',
                backgroundImage: 'repeating-linear-gradient(45deg, rgba(13,110,253,0.03) 0px, rgba(13,110,253,0.03) 5px, transparent 5px, transparent 10px)',
                backgroundSize: '100% 100%',
                backgroundRepeat: 'repeat'
            });

            // Asegúrate de que tu logo tenga un src absoluto o base64
            const logo = cloned.querySelector('.watermark-logo');
            // Si lo cargas con import, asigna: logo.src = this.logoUrl;

            document.body.appendChild(cloned);

            // 2. Esperar a que todas las <img> (logo + QR) estén cargadas
            const imgs = Array.from(cloned.querySelectorAll('img'));
            await Promise.all(imgs.map(img => img.complete ? Promise.resolve() : new Promise(res => { img.onload = img.onerror = res; })));

            // 3. Renderizar con html2canvas a mayor resolución
            const canvas = await html2canvas(cloned, {
                scale: 2,
                useCORS: true,      // importante si tu logo viene de otra ruta
                allowTaint: false   // evita marcas de agua de CORS
            });

            // 4. Eliminar el clon
            document.body.removeChild(cloned);

            // 5. Compartir
            canvas.toBlob(async blob => {

                const file = new File([blob], 'carnet.png', { type: 'image/png' });

                if(navigator.canShare && navigator.canShare({ files: [file] })) {

                    try {

                        await navigator.share({title: 'Carnet Digital', text: this.customer?.name, files: [file]});

                    }catch (err) {

                        console.error('Error al compartir:', err);

                    }

                }else {

                    alert('Tu navegador no soporta compartir archivos.');
                }

            }, 'image/png');

        },
        async descargarCarnet() {

            const carnet = this.$refs.carnet;
            const cloned = carnet.cloneNode(true);

            cloned.style.width = "450px";
            cloned.style.transform = "scale(1)";
            cloned.style.position = "fixed";
            cloned.style.top = "-9999px";
            cloned.style.zoom = "1"; // Optional
            cloned.style.maxWidth = "none"; // Optional

            const canvas = await html2canvas(cloned, { scale: 2 });
            document.body.removeChild(cloned);

            const link = document.createElement("a");
            link.href = canvas.toDataURL("image/png");
            link.download = `carnet_${this.customer?.document_number}.png`;
            link.click();

        }
    },
    watch: {
        customer: {
            deep: true,
            handler() {

                this.generarQR();

            }
        }
    }
};
</script>

<style scoped>
.carnet-card {
    max-width: 450px;
    background-color: #ffffff;
    background-image: repeating-linear-gradient(45deg,
            rgba(13, 110, 253, 0.03) 0,
            rgba(13, 110, 253, 0.03) 5px,
            transparent 5px,
            transparent 10px);
}

.carnet-body {
    position: relative;
    z-index: 2;
}

.watermark {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 1rem;
    z-index: 1;
}

.watermark-logo {
    position: absolute;
    bottom: 14px;
    right: 10px;
    font-size: 1rem;
    z-index: 1;
}
</style>
