<script setup>
import { useMenu } from "@/composables/useMenu";
import { onMounted, ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useUser } from "@/composables/useUser";
const { oUser } = useUser();

// data
const {
    mobile,
    drawer,
    rail,
    width,
    menu_open,
    setMenuOpen,
    cambiarUrl,
    toggleDrawer,
} = useMenu();

const user_logeado = ref({
    permisos: [],
});

const submenus = {
    "reportes.usuarios": "Reportes",
    "reportes.inventario_equipos": "Reportes",
    "reportes.servicios": "Reportes",
    "reportes.hora_servicios": "Reportes",
    "reportes.solicitud_atencion": "Reportes",
    "reportes.personal": "Reportes",
};

const route_current = ref("");

router.on("navigate", (event) => {
    route_current.value = route().current();
    if (mobile.value) {
        toggleDrawer(false);
    }
});

const { props: props_page } = usePage();

onMounted(() => {
    let route_actual = route().current();
    // buscar en submenus y abrirlo si uno de sus elementos esta activo
    setMenuOpen([]);
    if (submenus[route_actual]) {
        setMenuOpen([submenus[route_actual]]);
    }

    if (props_page.auth) {
        user_logeado.value = props_page.auth?.user;
    }

    setTimeout(() => {
        scrollActive();
    }, 300);
});

const scrollActive = () => {
    let sidebar = document.querySelector("#sidebar");
    let menu = null;
    let activeChild = null;
    if (sidebar) {
        menu = sidebar.querySelector(".v-navigation-drawer__content");
        activeChild = sidebar.querySelector(".active");
    }
    // Verifica si se encontró un hijo activo
    if (activeChild) {
        let offsetTop = activeChild.offsetTop - sidebar.offsetTop;
        setTimeout(() => {
            menu.scrollTo({
                top: offsetTop,
                behavior: "smooth", // desplazamiento suave
            });
        }, 500);
    }
};
</script>
<template>
    <v-navigation-drawer
        :permanent="!mobile"
        :temporary="mobile"
        v-model="drawer"
        class="border-0 elevation-2 __sidebar bg-blue-lighten-5"
        :width="width"
        id="sidebar"
    >
        <v-sheet>
            <div
                class="w-100 d-flex flex-column align-center elevation-1 bg-blue-lighten-5 pa-2 __info_usuario"
            >
                <v-avatar
                    class="mb-1"
                    color="blue-darken-4"
                    :size="`${!rail ? '64' : '32'}`"
                >
                    <v-img
                        cover
                        v-if="oUser.url_foto"
                        :src="oUser.url_foto"
                    ></v-img>
                    <span v-else class="text-h5">
                        {{ oUser.iniciales_nombre }}
                    </span>
                </v-avatar>
                <div v-show="!rail" class="text-caption font-weight-bold">
                    {{ oUser.tipo }}
                </div>
                <div v-show="!rail" class="text-body">
                    {{ oUser.full_name }}
                </div>
            </div>
        </v-sheet>

        <v-list class="mt-1 px-0" v-model:opened="menu_open">
            <v-list-item class="text-caption bg-blue-lighten-1 mb-2">
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>INICIO</span></v-list-item
            >
            <v-list-item
                class="mx-3"
                prepend-icon="mdi-home-outline"
                :class="[
                    route_current == 'inicio' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                @click="cambiarUrl(route('inicio'))"
                link
            >
                <v-list-item-title>Inicio</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Inicio</v-tooltip
                >
            </v-list-item>
            <v-list-item
                class="text-caption bg-blue-lighten-1 mb-1 pa-0 px-5 my-2"
                v-if="
                    oUser.permisos.includes('usuarios.index') ||
                    oUser.permisos.includes('personals.index') ||
                    oUser.permisos.includes('clientes.index') ||
                    oUser.permisos.includes('solicitud_atencions.index') ||
                    oUser.permisos.includes('servicios.geolocalizacion') ||
                    oUser.permisos.includes('equipo_accesorios.index') ||
                    oUser.permisos.includes('ingreso_productos.index') ||
                    oUser.permisos.includes('tipo_salidas.index') ||
                    oUser.permisos.includes('salidas_productos.index') ||
                    oUser.permisos.includes('notificacions.index')
                "
            >
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>ADMINISTRACIÓN</span></v-list-item
            >
            <v-list-item
                :class="[
                    route_current == 'clientes.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('clientes.index')"
                prepend-icon="mdi-playlist-edit"
                @click="cambiarUrl(route('clientes.index'))"
                link
            >
                <v-list-item-title>Clientes</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Clientes</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'solicitud_atencions.index'
                        ? 'active'
                        : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('solicitud_atencions.index')"
                prepend-icon="mdi-tag-multiple"
                @click="cambiarUrl(route('solicitud_atencions.index'))"
                link
            >
                <v-list-item-title>Solicitud de Atención</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Solicitud de Atención</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'servicios.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('servicios.index')"
                prepend-icon="mdi-clipboard-list"
                @click="cambiarUrl(route('servicios.index'))"
                link
            >
                <v-list-item-title>Servicios y Seguimiento</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Servicios y Seguimiento</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'equipo_accesorios.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('equipo_accesorios.index')"
                prepend-icon="mdi-view-list"
                @click="cambiarUrl(route('equipo_accesorios.index'))"
                link
            >
                <v-list-item-title
                    >Inventario de Equipos y Accesorios</v-list-item-title
                >
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Inventario de Equipos y Accesorios</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'personals.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('personals.index')"
                prepend-icon="mdi-account-badge"
                @click="cambiarUrl(route('personals.index'))"
                link
            >
                <v-list-item-title>Personal Técnico</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Personal Técnico</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'usuarios.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('usuarios.index')"
                prepend-icon="mdi-account-group"
                @click="cambiarUrl(route('usuarios.index'))"
                link
            >
                <v-list-item-title>Usuarios</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Usuarios</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'backup.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('backup.index')"
                prepend-icon="mdi-database"
                @click="cambiarUrl(route('backup.index'))"
                link
            >
                <v-list-item-title>Backup</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Backup</v-tooltip
                >
            </v-list-item>

            <v-list-item
                class="text-caption bg-blue-lighten-1 mb-1 my-2"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.avance_obras')
                "
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>REPORTES</span></v-list-item
            >
            <!-- SUBGROUP -->
            <v-list-group
                value="Reportes"
                class="mx-3"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.inventario_equipos')||
                    oUser.permisos.includes('reportes.servicios')||
                    oUser.permisos.includes('reportes.hora_servicios')||
                    oUser.permisos.includes('reportes.solicitud_atencion')||
                    oUser.permisos.includes('reportes.personal')
                "
            >
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-file-document-multiple"
                        title="Reportes"
                        :class="[
                            route_current == 'reporutes.usuarios' ||
                            route_current == 'reportes.inventario_equipos'||
                            route_current == 'reportes.servicios'||
                            route_current == 'reportes.hora_servicios'||
                            route_current == 'reportes.solicitud_atencion'||
                            route_current == 'reportes.personal'
                                ? 'active'
                                : '',
                        ]"
                    >
                        <v-tooltip
                            v-if="rail && !mobile"
                            color="white"
                            activator="parent"
                            location="end"
                            >Reportes</v-tooltip
                        ></v-list-item
                    >
                </template>
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.usuarios')"
                    prepend-icon="mdi-chevron-right"
                    title="Usuarios"
                    :class="[
                        route_current == 'reportes.usuarios' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.usuarios'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Usuarios</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.inventario_equipos')"
                    prepend-icon="mdi-chevron-right"
                    title="Inventario de Equipos"
                    :class="[
                        route_current == 'reportes.inventario_equipos' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.inventario_equipos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Inventario de Equipos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.servicios')"
                    prepend-icon="mdi-chevron-right"
                    title="Servicios y Seguimiento"
                    :class="[
                        route_current == 'reportes.servicios' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.servicios'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Servicios y Seguimiento</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.hora_servicios')"
                    prepend-icon="mdi-chevron-right"
                    title="Horas por Servicios"
                    :class="[
                        route_current == 'reportes.hora_servicios' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.hora_servicios'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Horas por Servicios</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.solicitud_atencion')"
                    prepend-icon="mdi-chevron-right"
                    title="Solicitud de Atención"
                    :class="[
                        route_current == 'reportes.solicitud_atencion' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.solicitud_atencion'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Solicitud de Atención</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.personal')"
                    prepend-icon="mdi-chevron-right"
                    title="Personal"
                    :class="[
                        route_current == 'reportes.personal' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.personal'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Personal</v-tooltip
                    ></v-list-item
                >
            </v-list-group>
            <v-list-item class="text-caption bg-blue-lighten-1 mb-1 my-2"
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>OTROS</span></v-list-item
            >
            <v-list-item
                v-if="oUser.permisos.includes('configuracions.index')"
                prepend-icon="mdi-cog-outline"
                class="mx-3"
                :class="[
                    route_current == 'configuracions.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                @click="cambiarUrl(route('configuracions.index'))"
                link
            >
                <v-list-item-title>Configuración</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Configuración</v-tooltip
                >
            </v-list-item>
            <v-list-item
                prepend-icon="mdi-account-circle"
                class="mx-3"
                :class="[
                    route_current == 'profile.edit' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                @click="cambiarUrl(route('profile.edit'))"
                link
            >
                <v-list-item-title>Perfil</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Perfil</v-tooltip
                >
            </v-list-item>
            <v-list-item
                prepend-icon="mdi-logout"
                class="mx-3"
                @click="cambiarUrl(route('logout'), 'post')"
                link
            >
                <v-list-item-title>Salir</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Salir</v-tooltip
                >
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<style scoped></style>
