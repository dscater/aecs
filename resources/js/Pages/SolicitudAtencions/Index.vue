<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "SolicitudAtencion Técnico",
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
import { useSolicitudAtencions } from "@/composables/solicitud_atencions/useSolicitudAtencions";
import { ref, onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import Formulario from "./Formulario.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const {
    getSolicitudAtencionsApi,
    setSolicitudAtencion,
    limpiarSolicitudAtencion,
    deleteSolicitudAtencion,
} = useSolicitudAtencions();
const responseSolicitudAtencions = ref([]);
const listSolicitudAtencions = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
    },
    { title: "Cliente", align: "start", sortable: false },
    { title: "Técnico", align: "start", sortable: false },
    { title: "Descripción de Atención", align: "start", sortable: false },
    { title: "Fecha y Hora", align: "start", sortable: false },
    { title: "Estado", align: "start", sortable: false },
    { title: "Fecha de Registro", align: "start", sortable: false },
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
        responseSolicitudAtencions.value = await getSolicitudAtencionsApi(
            options.value
        );
        listSolicitudAtencions.value = responseSolicitudAtencions.value.data;
        totalItems.value = parseInt(responseSolicitudAtencions.value.total);
        loading.value = false;
    }, 300);
};
const recargaSolicitudAtencions = async () => {
    loading.value = true;
    listSolicitudAtencions.value = [];
    options.value.search = search.value;
    responseSolicitudAtencions.value = await getSolicitudAtencionsApi(
        options.value
    );
    listSolicitudAtencions.value = responseSolicitudAtencions.value.data;
    totalItems.value = parseInt(responseSolicitudAtencions.value.total);
    setTimeout(() => {
        loading.value = false;
        open_dialog.value = false;
    }, 300);
};
const accion_dialog = ref(0);
const open_dialog = ref(false);

const agregarRegistro = () => {
    limpiarSolicitudAtencion();
    accion_dialog.value = 0;
    open_dialog.value = true;
};
const editarSolicitudAtencion = (item) => {
    setSolicitudAtencion(item);
    accion_dialog.value = 1;
    open_dialog.value = true;
};
const eliminarSolicitudAtencion = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.id} | ${item.cliente.razon_social} | ${item.personal.full_name}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteSolicitudAtencion(item.id);
            if (respuesta && respuesta.sw) {
                recargaSolicitudAtencions();
            }
        }
    });
};
</script>
<template>
    <Head title="Solicitud de Atención"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row
            class="mt-0"
            v-if="props.auth.user.permisos.includes('solicitud_atencions.create')"
        >
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    color="blue"
                    prepend-icon="mdi-plus"
                    @click="agregarRegistro"
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
                            <v-col cols="12" sm="6" md="4">
                                SolicitudAtencion Técnico
                            </v-col>
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
                            :items="listSolicitudAtencions"
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
                                        <td>{{ item.id }}</td>
                                        <td>
                                            {{ item.cliente.razon_social }}
                                        </td>
                                        <td>{{ item.personal.full_name }}</td>
                                        <td>{{ item.descripcion }}</td>
                                        <td>{{ item.fecha_hora_t }}</td>
                                        <td>{{ item.estado }}</td>
                                        <td>{{ item.fecha_registro_t }}</td>
                                        <td class="text-right" width="5%">
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'solicitud_atencions.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="
                                                    editarSolicitudAtencion(
                                                        item
                                                    )
                                                "
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'solicitud_atencions.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="
                                                    eliminarSolicitudAtencion(
                                                        item
                                                    )
                                                "
                                                icon="mdi-trash-can"
                                            ></v-btn>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td>
                                            <ul class="flex-content">
                                                <li
                                                    class="flex-item"
                                                    data-label="Id:"
                                                >
                                                    {{ item.id }}
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
                                                    data-label="Técnico:"
                                                >
                                                    {{
                                                        item.personal.full_name
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Descripción de Atención:"
                                                >
                                                    {{ item.descripcion }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha y hora:"
                                                >
                                                    {{ item.fecha_hora_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Estado:"
                                                >
                                                    {{ item.estado }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Registro"
                                                >
                                                    {{ item.fecha_registro_t }}
                                                </li>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'solicitud_atencions.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarSolicitudAtencion(
                                                                item
                                                            )
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'solicitud_atencions.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarSolicitudAtencion(
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
        <Formulario
            :open_dialog="open_dialog"
            :accion_dialog="accion_dialog"
            @envio-formulario="recargaSolicitudAtencions"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
    </v-container>
</template>
