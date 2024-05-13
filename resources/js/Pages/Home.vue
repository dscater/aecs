<script setup>
import App from "@/Layouts/App.vue";
defineOptions({
    layout: App,
});
import { onMounted } from "vue";
import { useApp } from "@/composables/useApp";
// componentes
import BreadBrums from "@/Components/BreadBrums.vue";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { usePage, Head } from "@inertiajs/vue3";
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
];
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const { oConfiguracion } = useConfiguracion();

const { props } = usePage();
</script>
<template>
    <Head title="Inicio"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row>
            <v-col cols="12" sm="6" md="6" xl="4">
                <v-card>
                    <v-card-text class="pa-1">
                        <v-row>
                            <v-col
                                cols="8"
                                class="bg-blue text-white d-flex flex-column justify-center align-center"
                            >
                                <h4
                                    class="text-h5 text-center font-weight-black mb-3"
                                >
                                    Personal
                                </h4>
                                <h4
                                    class="text-h4 text-center font-weight-black"
                                >
                                    0
                                </h4>
                            </v-col>
                            <v-col cols="4" class="pa-0">
                                <v-card
                                    class="pa-2 ma-0 d-flex align-center justify-center text-icon"
                                    variant="tonal"
                                    color="blue"
                                >
                                    <v-icon>mdi-account-group</v-icon>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="6" xl="4">
                <v-card>
                    <v-card-text class="pa-1">
                        <v-row>
                            <v-col
                                cols="8"
                                class="bg-blue text-white d-flex flex-column justify-center align-center"
                            >
                                <h4
                                    class="text-h5 text-center font-weight-black mb-3"
                                >
                                    Usuarios
                                </h4>
                                <h4
                                    class="text-h4 text-center font-weight-black"
                                >
                                    0
                                </h4>
                            </v-col>
                            <v-col cols="4" class="pa-0">
                                <v-card
                                    class="pa-2 ma-0 d-flex align-center justify-center text-icon"
                                    variant="tonal"
                                    color="blue"
                                >
                                    <v-icon>mdi-account-group</v-icon>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="6" xl="4">
                <v-card>
                    <v-card-text class="pa-1">
                        <v-row>
                            <v-col
                                cols="8"
                                class="bg-blue text-white d-flex flex-column justify-center align-center"
                            >
                                <h4
                                    class="text-h5 text-center font-weight-black mb-3"
                                >
                                    Solicitud de atención
                                </h4>
                                <h4
                                    class="text-h4 text-center font-weight-black"
                                >
                                    0
                                </h4>
                            </v-col>
                            <v-col cols="4" class="pa-0">
                                <v-card
                                    class="pa-2 ma-0 d-flex align-center justify-center text-icon"
                                    variant="tonal"
                                    color="blue"
                                >
                                    <v-icon>mdi-clipboard-list</v-icon>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="6" lg="6">
                <v-card>
                    <v-card-title>
                        <h4>Técnicos con solicitud de atención</h4>
                    </v-card-title>
                    <v-card-text>
                        <div style="height:140px"></div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6" lg="6">
                <v-card>
                    <v-card-title>
                        <h4>Cantidad de asignación a técnicos</h4>
                    </v-card-title>
                    <v-card-text>
                        <div style="height:140px"></div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6" lg="6">
                <v-card>
                    <v-card-title>
                        <h4>Cantidad de servicios atentidos por técnicos</h4>
                    </v-card-title>
                    <v-card-text>
                        <div style="height:140px"></div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card variant="flat" color="blue">
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h4 class="text-center text-h3">
                                    {{ oConfiguracion.nombre_sistema }}
                                </h4>
                            </v-col>
                            <v-col cols="12">
                                <h4 class="text-center text-h4">
                                    Bienvenid@ {{ props.auth.user.full_name }}
                                </h4>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
