<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Personal Técnico",
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
import { usePersonals } from "@/composables/personals/usePersonals";
import { ref, onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import Formulario from "./Formulario.vue";
import HojaVida from "./HojaVida.vue";
const { mobile, identificaDispositivo } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getPersonalsApi, setPersonal, limpiarPersonal, deletePersonal } =
    usePersonals();
const responsePersonals = ref([]);
const listPersonals = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
    },
    { title: "Nombre", align: "start", sortable: false },
    { title: "C.I.", align: "start", sortable: false },
    { title: "Especialidad", align: "start", sortable: false },
    { title: "Domicilio", align: "start", sortable: false },
    { title: "Celular", align: "start", sortable: false },
    { title: "Foto", align: "start", sortable: false },
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
        responsePersonals.value = await getPersonalsApi(options.value);
        listPersonals.value = responsePersonals.value.data;
        totalItems.value = parseInt(responsePersonals.value.total);
        loading.value = false;
    }, 300);
};
const recargaPersonals = async () => {
    loading.value = true;
    listPersonals.value = [];
    options.value.search = search.value;
    responsePersonals.value = await getPersonalsApi(options.value);
    listPersonals.value = responsePersonals.value.data;
    totalItems.value = parseInt(responsePersonals.value.total);
    setTimeout(() => {
        loading.value = false;
        open_dialog.value = false;
    }, 300);
};
const accion_dialog = ref(0);
const open_dialog = ref(false);
const accion_dialog_hv = ref(0);
const open_dialog_hv = ref(false);

const agregarRegistro = () => {
    limpiarPersonal();
    accion_dialog.value = 0;
    open_dialog.value = true;
};
const editarPersonal = (item) => {
    setPersonal(item);
    accion_dialog.value = 1;
    open_dialog.value = true;
};
const verHojaVida = (item) => {
    setPersonal(item, true);
    accion_dialog_hv.value = 0;
    open_dialog_hv.value = true;
};
const eliminarPersonal = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.full_name}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deletePersonal(item.id);
            if (respuesta && respuesta.sw) {
                recargaPersonals();
            }
        }
    });
};
</script>
<template>
    <Head title="Personals"></Head>
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
                                Personal Técnico
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
                            :items="listPersonals"
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
                                            {{ item.full_name }}
                                        </td>
                                        <td>{{ item.full_ci }}</td>
                                        <td>{{ item.especialidad }}</td>
                                        <td>{{ item.domicilio }}</td>
                                        <td>{{ item.cel }}</td>
                                        <td>
                                            <v-avatar color="blue">
                                                <v-img
                                                    v-if="item.url_foto"
                                                    :src="item.url_foto"
                                                    cover
                                                    :lazy-src="item.url_foto"
                                                ></v-img>
                                                <span v-else>{{
                                                    item.iniciales_nombre
                                                }}</span>
                                            </v-avatar>
                                        </td>
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
                                                        'personals.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarPersonal(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'personals.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarPersonal(item)"
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
                                                            >Estado civil</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.estado_civil
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
                                                            Nacimiento</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.fecha_nac_t
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
                                                            >Record</v-col
                                                        >
                                                        <v-col cols="12">{{
                                                            item.record
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
                                                            >Hoja de vida</v-col
                                                        >
                                                        <v-col cols="12">
                                                            <v-btn
                                                                color="primary"
                                                                size="small"
                                                                class="pa-1 ma-1"
                                                                @click="
                                                                    verHojaVida(
                                                                        item
                                                                    )
                                                                "
                                                                icon="mdi-file-account-outline"
                                                            ></v-btn>
                                                        </v-col>
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
                                                    data-label="Nombre:"
                                                >
                                                    {{ item.full_name }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="C.I.:"
                                                >
                                                    {{ item.full_ci }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Especialidad:"
                                                >
                                                    {{ item.especialidad }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Domicilio:"
                                                >
                                                    {{ item.domicilio }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Celular:"
                                                >
                                                    {{ item.cel }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Foto:"
                                                >
                                                    <v-avatar color="blue">
                                                        <v-img
                                                            v-if="item.url_foto"
                                                            :src="item.url_foto"
                                                            cover
                                                            :lazy-src="
                                                                item.url_foto
                                                            "
                                                        ></v-img>
                                                        <span v-else>{{
                                                            item.iniciales_nombre
                                                        }}</span>
                                                    </v-avatar>
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
                                                        data-label="Estado civil:"
                                                    >
                                                        {{ item.estado_civil }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Fecha de Nacimiento:"
                                                    >
                                                        {{ item.fecha_nac }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Record:"
                                                    >
                                                        {{ item.record }}
                                                    </li>
                                                    <li
                                                        class="flex-item"
                                                        data-label="Hoja de vida"
                                                    >
                                                        {{ item.hoja_vida }}
                                                    </li>
                                                </template>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Registro"
                                                >
                                                    {{ item.fecha_registro }}
                                                </li>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <!-- <v-btn
                                                        color="primary"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            verUbicación(item)
                                                        "
                                                        icon="mdi-map-marker"
                                                    ></v-btn> -->
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'personals.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarPersonal(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'personals.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarPersonal(
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
            @envio-formulario="recargaPersonals"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
        <HojaVida
            :open_dialog="open_dialog_hv"
            :accion_dialog="accion_dialog_hv"
            @cerrar-dialog="open_dialog_hv = false"
        ></HojaVida>
    </v-container>
</template>
