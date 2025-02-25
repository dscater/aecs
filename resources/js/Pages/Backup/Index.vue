<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Backup",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { Head, usePage } from "@inertiajs/vue3";
import { useClientes } from "@/composables/clientes/useClientes";
import { ref, onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();

const generarBackup = () => {
    axios
        .post(route("backup.generarBackup"), {}, { responseType: "blob" })
        .then((response) => {
            // Extraer el nombre del archivo desde la cabecera
            let disposition = response.headers["content-disposition"];
            let fileName = "backup.sql"; // Nombre por defecto

            if (disposition) {
                let matches = disposition.match(/filename=([^;]+)/);
                if (matches && matches.length > 1) {
                    fileName = matches[1];
                }
            }

            // Crear la URL del archivo
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", fileName); // Usar el nombre del servidor
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch((error) => {
            console.error("Error al generar el backup:", error);
        });
};

onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Backup"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" lg="6">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-blue d-flex align-center pa-3">
                            <v-col cols="12">
                                Backup
                                <p class="text-caption d-block w-100">
                                    Generar copia de seguridad de la base de
                                    datos
                                </p>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row class="mt-2">
                            <v-col cols="12" class="text-center">
                                <v-btn
                                    prepend-icon="mdi-download"
                                    @click="generarBackup"
                                    >Generar Backup</v-btn
                                >
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
