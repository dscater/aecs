<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useSolicitudAtencions } from "@/composables/solicitud_atencions/useSolicitudAtencions";
import { useClientes } from "@/composables/clientes/useClientes";
import { usePersonals } from "@/composables/personals/usePersonals";
import { watch, ref, computed, defineEmits } from "vue";
const props = defineProps({
    open_dialog: {
        type: Boolean,
        default: false,
    },
    accion_dialog: {
        type: Number,
        default: 0,
    },
});
const { props: props_page } = usePage();

const { oSolicitudAtencion, limpiarSolicitudAtencion } =
    useSolicitudAtencions();
const { getClientes } = useClientes();
const { getPersonals } = usePersonals();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const listClientes = ref([]);
const listPersonal = ref([]);

let form = useForm(oSolicitudAtencion.value);
watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarListas();
            form = useForm(oSolicitudAtencion.value);
        }
    }
);
watch(
    () => props.accion_dialog,
    (newValue) => {
        accion.value = newValue;
    }
);

const { flash } = usePage().props;

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `Agregar Solicitud de Atención`
        : `Editar Solicitud de Atención`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("solicitud_atencions.store")
            : route("solicitud_atencions.update", form.id);
    if (props_page.auth.user.tipo != "GERENTE TÉCNICO") {
        url = route("solicitud_atencions.update_estado", form.id);
    }
    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            limpiarSolicitudAtencion();
            emits("envio-formulario");
        },
        onError: (err) => {
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.error
                        ? err.error
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        },
    });
};

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

const cargarListas = async () => {
    listClientes.value = await getClientes();
    listPersonal.value = await getPersonals();
};

watch(dialog, (newVal) => {
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
};
</script>

<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="1024" persistent scrollable>
            <v-card>
                <v-card-title class="border-b bg-blue pa-5">
                    <v-icon
                        icon="mdi-close"
                        class="float-right"
                        @click="cerrarDialog"
                    ></v-icon>

                    <v-icon
                        :icon="accion == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form
                            v-if="
                                props_page.auth.user.tipo == 'GERENTE TÉCNICO'
                            "
                        >
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.cliente_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.cliente_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.cliente_id
                                                ? form.errors?.cliente_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        clearable
                                        :items="listClientes"
                                        no-data-text="Sin registros"
                                        item-value="id"
                                        item-title="razon_social"
                                        label="Seleccionar Cliente*"
                                        v-model="form.cliente_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-autocomplete
                                        :hide-details="
                                            form.errors?.personal_id
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.personal_id
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.personal_id
                                                ? form.errors?.personal_id
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        clearable
                                        :items="listPersonal"
                                        no-data-text="Sin registros"
                                        item-value="id"
                                        item-title="full_name"
                                        label="Seleccionar Técnico*"
                                        v-model="form.personal_id"
                                        required
                                    ></v-autocomplete>
                                </v-col>

                                <v-col cols="12" sm="6" md="4">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.descripcion
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.descripcion
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.descripcion
                                                ? form.errors?.descripcion
                                                : ''
                                        "
                                        variant="underlined"
                                        density="compact"
                                        color="blue"
                                        label="Descripción de Atención*"
                                        v-model="form.descripcion"
                                        rows="1"
                                        auto-grow
                                        required
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fecha ? false : true
                                        "
                                        :error="
                                            form.errors?.fecha ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.fecha
                                                ? form.errors?.fecha
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Fecha*"
                                        type="date"
                                        required
                                        density="compact"
                                        v-model="form.fecha"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.hora ? false : true
                                        "
                                        :error="
                                            form.errors?.hora ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.hora
                                                ? form.errors?.hora
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Hora*"
                                        type="time"
                                        required
                                        density="compact"
                                        v-model="form.hora"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </form>
                        <form
                            v-if="
                                props_page.auth.user.tipo != 'GERENTE TÉCNICO'
                            "
                        >
                            <v-row>
                                <v-col cols="12">
                                    <p>
                                        <strong>Cliente: </strong
                                        >{{
                                            oSolicitudAtencion.cliente
                                                .razon_social
                                        }}
                                    </p>
                                    <p>
                                        <strong>Personal Técnico: </strong
                                        >{{
                                            oSolicitudAtencion.personal
                                                .full_name
                                        }}
                                    </p>
                                    <p>
                                        <strong
                                            >Descripción de atención: </strong
                                        >{{ oSolicitudAtencion.descripcion }}
                                    </p>
                                    <p>
                                        <strong>Fecha y Hora: </strong
                                        >{{ oSolicitudAtencion.fecha_hora_t }}
                                    </p>
                                    <p class="mt-3">
                                        <v-select
                                            :hide-details="true"
                                            :error-messages="
                                                form.errors?.estado
                                                    ? form.errors?.estado
                                                    : ''
                                            "
                                            density="compact"
                                            variant="outlined"
                                            color="blue"
                                            :items="[
                                                'PENDIENTE',
                                                'EN PROCESO',
                                                'ATENDIDO',
                                            ]"
                                            label="Estado*"
                                            v-model="form.estado"
                                        ></v-select>
                                    </p>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
                <v-card-actions class="border-t">
                    <v-spacer></v-spacer>
                    <v-btn
                        color="grey-darken-4"
                        variant="text"
                        @click="cerrarDialog"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        class="bg-blue"
                        prepend-icon="mdi-content-save"
                        @click="enviarFormulario"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>
