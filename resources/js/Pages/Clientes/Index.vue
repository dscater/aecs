<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Clientes",
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

const { getClientesApi, setCliente, limpiarCliente, deleteCliente } =
    useClientes();
const responseClientes = ref([]);
const listClientes = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
    },
    { title: "Razón Social", align: "start", sortable: false },
    { title: "Tipo", align: "start", sortable: false },
    { title: "Descripción", align: "start", sortable: false },
    { title: "Nit", align: "start", sortable: false },
    { title: "Dirección", align: "start", sortable: false },
    { title: "Nivel de importancia", align: "start", sortable: false },
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
        responseClientes.value = await getClientesApi(options.value);
        listClientes.value = responseClientes.value.data;
        totalItems.value = parseInt(responseClientes.value.total);
        loading.value = false;
    }, 300);
};
const recargaClientes = async () => {
    loading.value = true;
    listClientes.value = [];
    options.value.search = search.value;
    responseClientes.value = await getClientesApi(options.value);
    listClientes.value = responseClientes.value.data;
    totalItems.value = parseInt(responseClientes.value.total);
    setTimeout(() => {
        loading.value = false;
        open_dialog.value = false;
    }, 300);
};
const accion_dialog = ref(0);
const open_dialog = ref(false);

const agregarRegistro = () => {
    limpiarCliente();
    accion_dialog.value = 0;
    open_dialog.value = true;
};
const editarCliente = (item) => {
    setCliente(item);
    accion_dialog.value = 1;
    open_dialog.value = true;
};
const eliminarCliente = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.razon_social}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteCliente(item.id);
            if (respuesta && respuesta.sw) {
                recargaClientes();
            }
        }
    });
};
</script>
<template>
    <Head title="Clientes"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
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
                                Cliente Técnico
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
                            :items="listClientes"
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
                                            {{ item.razon_social }}
                                        </td>
                                        <td>{{ item.tipo }}</td>
                                        <td>{{ item.descripcion }}</td>
                                        <td>{{ item.nit }}</td>
                                        <td>{{ item.dir }}</td>
                                        <td>{{ item.nivel }}</td>
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
                                                        'clientes.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarCliente(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'clientes.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarCliente(item)"
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
                                                <v-col
                                                    cols="3"
                                                    class="text-center"
                                                >
                                                    <v-row>
                                                        <v-col
                                                            cols="12"
                                                            class="pb-0 text-caption font-weight-black"
                                                            >Teléfono</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.fono
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
                                                            >Correo</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.correo
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
                                                    data-label="Id:"
                                                >
                                                    {{ item.id }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Razón Social:"
                                                >
                                                    {{ item.razon_social }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Tipo:"
                                                >
                                                    {{ item.tipo }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Descripción:"
                                                >
                                                    {{ item.descripcion }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Nit:"
                                                >
                                                    {{ item.nit }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Dirección:"
                                                >
                                                    {{ item.dir }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Nivel de importancia:"
                                                >
                                                    {{ item.nivel }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Mas:"
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
                                                <template v-if="item.mas">
                                                    <li
                                                        class="flex-item"
                                                        data-label="Teléfono:"
                                                    >
                                                        {{ item.fono }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Correo:"
                                                    >
                                                        {{ item.correo }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Fecha de Registro"
                                                    >
                                                        {{
                                                            item.fecha_registro
                                                        }}
                                                    </li>
                                                </template>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'clientes.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarCliente(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'clientes.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarCliente(
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
            @envio-formulario="recargaClientes"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
    </v-container>
</template>
