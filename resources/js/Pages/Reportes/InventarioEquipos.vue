<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Inventario de Equipos",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useServicios } from "@/composables/servicios/useServicios";
const { setLoading } = useApp();
const { getServicios } = useServicios();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const form = ref({
    servicio_id: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const listServicios = ref([]);

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_inventario_equipos", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

function obtenerFechaActual() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
    const dia = fecha.getDate().toString().padStart(2, "0");

    return `${año}-${mes}-${dia}`;
}

const cargarListas = async () => {
    listServicios.value = await getServicios({
        order: true,
    });
    listServicios.value.unshift({
        id: "todos",
        nom_equipo: "TODOS",
    });
};

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Reporte Inventario de Equipos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <form @submit.prevent="generarReporte">
                                <v-row>
                                    <v-col cols="12">
                                        <v-autocomplete
                                            :hide-details="
                                                form.errors?.servicio_id
                                                    ? false
                                                    : true
                                            "
                                            :error="
                                                form.errors?.servicio_id
                                                    ? true
                                                    : false
                                            "
                                            :error-messages="
                                                form.errors?.servicio_id
                                                    ? form.errors?.servicio_id
                                                    : ''
                                            "
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listServicios"
                                            item-value="id"
                                            item-title="nom_equipo"
                                            label="Tipo*"
                                            v-model="form.servicio_id"
                                        ></v-autocomplete>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        v-if="form.servicio_id == 'todos'"
                                    >
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form.errors?.fecha_ini
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form.errors?.fecha_ini
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form.errors?.fecha_ini
                                                            ? form.errors
                                                                  ?.fecha_ini
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Inicio*"
                                                    required
                                                    v-model="form.fecha_ini"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form.errors?.fecha_fin
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form.errors?.fecha_fin
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form.errors?.fecha_fin
                                                            ? form.errors
                                                                  ?.fecha_fin
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Fin*"
                                                    required
                                                    v-model="form.fecha_fin"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="blue"
                                            block
                                            @click="generarReporte"
                                            :disabled="generando"
                                            v-text="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                            </form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
