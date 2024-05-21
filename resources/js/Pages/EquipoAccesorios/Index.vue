<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Equipos y Accesorios",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { useMenu } from "@/composables/useMenu";
import { Head, usePage } from "@inertiajs/vue3";
import { useServicios } from "@/composables/servicios/useServicios";
import { ref, onMounted } from "vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getServiciosEquiposApi, deleteServicio } = useServicios();
const responseServicios = ref([]);
const listServicios = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Código Servicio",
        align: "start",
        sortable: false,
    },
    {
        title: "Tipo de Equipo",
        align: "start",
        sortable: false,
    },
    { title: "Marca", align: "start", sortable: false },
    { title: "Modelo", align: "start", sortable: false },
    { title: "Número de Serie", align: "start", sortable: false },
    { title: "Número de Parte", align: "start", sortable: false },
    { title: "Número de Activo", align: "start", sortable: false },
    { title: "Ubicación", align: "start", sortable: false },
]);

const search = ref("");
const options = ref({
    page: 1,
    itemsPerPage: itemsPerPage,
    sortBy: "",
    sortOrder: "desc",
    search: "",
});

const loading = ref(true);
const totalItems = ref(0);
let setTimeOutLoadData = null;
const loadItems = async ({ page, itemsPerPage, sortBy }) => {
    loading.value = true;
    options.value.page = page;
    if (sortBy.length > 0) {
        options.value.sortBy = sortBy[0].key;
        options.value.sortOrder = sortBy[0].order;
    }
    options.value.search = search.value;

    clearInterval(setTimeOutLoadData);
    setTimeOutLoadData = setTimeout(async () => {
        responseServicios.value = await getServiciosEquiposApi(options.value);
        listServicios.value = responseServicios.value.data;
        totalItems.value = parseInt(responseServicios.value.total);
        loading.value = false;
    }, 300);
};
const recargaServicios = async () => {
    loading.value = true;
    listServicios.value = [];
    options.value.search = search.value;
    responseServicios.value = await getServiciosEquiposApi(options.value);
    listServicios.value = responseServicios.value.data;
    totalItems.value = parseInt(responseServicios.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const verUbicación = async (item) => {};
</script>
<template>
    <Head title="Equipos y Accesorios"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-blue d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4">Inventario de Equipos y Accesorios</v-col>
                            <v-col cols="12" sm="6" md="4" offset-md="4">
                                <v-text-field
                                    v-model="search"
                                    label="Buscar"
                                    append-inner-icon="mdi-magnify"
                                    variant="underlined"
                                    clearable
                                    hide-details
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-data-table-server
                            v-model:items-per-page="itemsPerPage"
                            :headers="!mobile ? headers : []"
                            :class="[mobile ? 'mobile' : '']"
                            :items-length="totalItems"
                            :items="listServicios"
                            :loading="loading"
                            :search="search"
                            @update:options="loadItems"
                            height="auto"
                            no-data-text="No se encontrarón registros"
                            loading-text="Cargando..."
                            page-text="{0} - {1} de {2}"
                            items-per-page-text="Registros por página"
                            :items-per-page-options="[
                                { value: 10, title: '10' },
                                { value: 25, title: '25' },
                                { value: 50, title: '50' },
                                { value: 100, title: '100' },
                                {
                                    value: -1,
                                    title: 'Todos',
                                },
                            ]"
                        >
                            <template v-slot:item="{ item }">
                                <template v-if="!mobile">
                                    <tr>
                                        <td>{{ item.cod }}</td>
                                        <td>
                                            {{ item.tipo }}
                                        </td>
                                        <td>
                                            {{ item.marca }}
                                        </td>
                                        <td>
                                            {{ item.modelo }}
                                        </td>
                                        <td>{{ item.nro_serie }}</td>
                                        <td>{{ item.nro_parte }}</td>
                                        <td>{{ item.nro_activo }}</td>
                                        <td>{{ item.ubicacion }}</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td>
                                            <ul class="flex-content">
                                                <li
                                                    class="flex-item"
                                                    data-label="Código Servicio:"
                                                >
                                                    {{ item.cod }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Cliente:"
                                                >
                                                    {{
                                                        item.cliente
                                                            .razon_social
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Responsable:"
                                                >
                                                    {{
                                                        item.personal.full_name
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha"
                                                >
                                                    {{ item.fecha_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Hora Inicio:"
                                                >
                                                    {{ item.hora_ini }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Hora Final:"
                                                >
                                                    {{ item.hora_fin }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Total Horas:"
                                                >
                                                    {{ item.total_horas }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Más:"
                                                >
                                                    <v-btn
                                                        :icon="
                                                            item.mas
                                                                ? 'mdi-chevron-down'
                                                                : 'mdi-chevron-left'
                                                        "
                                                        @click="
                                                            item.mas = !item.mas
                                                        "
                                                    ></v-btn>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </v-data-table-server>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
