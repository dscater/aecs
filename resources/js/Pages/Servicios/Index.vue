<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Servicios",
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

const { getServiciosApi, deleteServicio } = useServicios();
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
        title: "Cliente",
        align: "start",
        sortable: false,
    },
    { title: "Responsable", align: "start", sortable: false },
    { title: "Fecha", align: "start", sortable: false },
    { title: "Hora Inicio", align: "start", sortable: false },
    { title: "Hora Final", align: "start", sortable: false },
    { title: "Total Horas", align: "start", sortable: false },
    { title: "Más", align: "start", sortable: false },
    { title: "Acción", align: "end", sortable: false },
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
        responseServicios.value = await getServiciosApi(options.value);
        listServicios.value = responseServicios.value.data;
        totalItems.value = parseInt(responseServicios.value.total);
        loading.value = false;
    }, 300);
};
const recargaServicios = async () => {
    loading.value = true;
    listServicios.value = [];
    options.value.search = search.value;
    responseServicios.value = await getServiciosApi(options.value);
    listServicios.value = responseServicios.value.data;
    totalItems.value = parseInt(responseServicios.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const editarServicio = (item) => {
    cambiarUrl(route("servicios.edit", item.id));
};

const eliminarServicio = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.nombre}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteServicio(item.id);
            if (respuesta && respuesta.sw) {
                recargaServicios();
            }
        }
    });
};
const verUbicación = async (item) => {};
</script>
<template>
    <Head title="Servicios"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    v-if="props.auth.user.permisos.includes('servicios.create')"
                    color="blue"
                    prepend-icon="mdi-plus"
                    @click="cambiarUrl(route('servicios.create'))"
                >
                    Agregar</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-blue d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4"> Servicios </v-col>
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
                                            {{ item.cliente.razon_social }}
                                        </td>
                                        <td>
                                            {{ item.personal.full_name }}
                                        </td>
                                        <td>
                                            {{ item.fecha_t }}
                                        </td>
                                        <td>{{ item.hora_ini }}</td>
                                        <td>{{ item.hora_fin }}</td>
                                        <td>{{ item.total_horas }}</td>
                                        <td>
                                            <v-btn
                                                :icon="
                                                    item.mas
                                                        ? 'mdi-chevron-down'
                                                        : 'mdi-chevron-left'
                                                "
                                                @click="item.mas = !item.mas"
                                            ></v-btn>
                                        </td>
                                        <td class="text-right" width="5%">
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'servicios.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarServicio(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'servicios.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarServicio(item)"
                                                icon="mdi-trash-can"
                                            ></v-btn>
                                        </td>
                                    </tr>
                                    <tr v-if="item.mas">
                                        <td
                                            :colspan="headers.length"
                                            class="py-5"
                                        >
                                            <v-row>
                                                <h4 class="w-100 text-center">
                                                    Datos del Equipo
                                                </h4>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Ubicación</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.ubicacion
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Tipo</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.tipo
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Marca</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.marca
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Modelo</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.modelo
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Número Serie</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.nro_serie
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Número Parte</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.nro_parte
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Número
                                                            Activo</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.nro_activo
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Tipo de
                                                            Servicio</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.tipo_servicio
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Foto</v-col
                                                        >
                                                        <v-col cols="12">
                                                            <img
                                                                v-if="
                                                                    item.url_foto
                                                                "
                                                                :src="
                                                                    item.url_foto
                                                                "
                                                                alt="Foto"
                                                                height="90px"
                                                            />
                                                            <span
                                                                v-else
                                                                class="text-caption"
                                                                >S/F</span
                                                            >
                                                        </v-col>
                                                    </v-row>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <h4 class="w-100 text-center">
                                                    Informe
                                                </h4>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Problema reportado
                                                            según Cliente</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.problema
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Trabajos
                                                            Realizados</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.trabajo_realizado
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Partes
                                                            Utilizados</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.partes
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Tipo de
                                                            Trabajo</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.tipo_trabajo
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Fecha de
                                                            Registro</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.fecha_registro_t
                                                        }}</v-col>
                                                    </v-row>
                                                </v-col>
                                            </v-row>
                                        </td>
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
                                            <template v-if="item.mas">
                                                <h4 class="w-100 text-center">
                                                    Datos del equipo
                                                </h4>
                                                <ul class="flex-content">
                                                    <li
                                                        class="flex-item"
                                                        data-label="Ubicación:"
                                                    >
                                                        {{ item.ubicacion }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Tipo:"
                                                    >
                                                        {{ item.tipo }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Marca:"
                                                    >
                                                        {{ item.marca }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Modelo:"
                                                    >
                                                        {{ item.modelo }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Número de Serie:"
                                                    >
                                                        {{ item.nro_serie }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Número de Parte:"
                                                    >
                                                        {{ item.nro_parte }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Número Activo:"
                                                    >
                                                        {{ item.nro_activo }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Tipo de Servicio:"
                                                    >
                                                        {{ item.tipo_servicio }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Foto:"
                                                    >
                                                        <img
                                                            v-if="item.url_foto"
                                                            :src="item.url_foto"
                                                            alt="Foto"
                                                            height="90px"
                                                        />
                                                        <span
                                                            v-else
                                                            class="text-caption"
                                                            >S/F</span
                                                        >
                                                    </li>
                                                </ul>
                                            </template>
                                            <template v-if="item.mas">
                                                <h4 class="w-100 text-center">
                                                    Informe
                                                </h4>
                                                <ul class="flex-content">
                                                    <li
                                                        class="flex-item"
                                                        data-label="Problema reportado según cliente:"
                                                    >
                                                        {{ item.problema }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Trabajos realizados:"
                                                    >
                                                        {{
                                                            item.trabajo_realizado
                                                        }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Partes utilizados:"
                                                    >
                                                        {{ item.partes }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Tipo de Trabajo:"
                                                    >
                                                        {{ item.tipo_trabajo }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Fecha de Registro"
                                                    >
                                                        {{
                                                            item.fecha_registro
                                                        }}
                                                    </li>
                                                </ul>
                                            </template>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'servicios.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarServicio(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'servicios.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarServicio(
                                                                item
                                                            )
                                                        "
                                                        icon="mdi-trash-can"
                                                    ></v-btn>
                                                </v-col>
                                            </v-row>
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
