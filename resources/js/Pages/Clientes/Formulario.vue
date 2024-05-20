<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useClientes } from "@/composables/clientes/useClientes";
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

const { oCliente, limpiarCliente } = useClientes();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oCliente.value);
watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            form = useForm(oCliente.value);
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

const listNivel = [
    { value: "ALTA", label: "ALTA" },
    { value: "MEDIA", label: "MEDIA" },
    { value: "BAJA", label: "BAJA" },
];
const listTipos = [
    { value: "PÚBLICO", label: "PÚBLICO" },
    { value: "PRIVADO", label: "PRIVADO" },
];

const hoja_vida = ref(null);
const foto = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const tituloDialog = computed(() => {
    return accion.value == 0 ? `Agregar Cliente` : `Editar Cliente`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("clientes.store")
            : route("clientes.update", form.id);

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
            limpiarCliente();
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
                        <form>
                            <v-row>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.razon_social
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.razon_social
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.razon_social
                                                ? form.errors?.razon_social
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Razón Social*"
                                        required
                                        density="compact"
                                        v-model="form.razon_social"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-select
                                        :hide-details="
                                            form.errors?.tipo ? false : true
                                        "
                                        :error="
                                            form.errors?.tipo ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.tipo
                                                ? form.errors?.tipo
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        clearable
                                        :items="listTipos"
                                        item-value="value"
                                        item-title="label"
                                        label="Tipo*"
                                        v-model="form.tipo"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
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
                                        color="blue"
                                        label="Descripción"
                                        required
                                        density="compact"
                                        v-model="form.descripcion"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.nit ? false : true
                                        "
                                        :error="form.errors?.nit ? true : false"
                                        :error-messages="
                                            form.errors?.nit
                                                ? form.errors?.nit
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Nit"
                                        required
                                        density="compact"
                                        v-model="form.nit"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.dir ? false : true
                                        "
                                        :error="form.errors?.dir ? true : false"
                                        :error-messages="
                                            form.errors?.dir
                                                ? form.errors?.dir
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Dirección"
                                        required
                                        density="compact"
                                        v-model="form.dir"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fono ? false : true
                                        "
                                        :error="
                                            form.errors?.fono ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.fono
                                                ? form.errors?.fono
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Teléfono"
                                        required
                                        density="compact"
                                        v-model="form.fono"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.correo ? false : true
                                        "
                                        :error="
                                            form.errors?.correo ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.correo
                                                ? form.errors?.correo
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Correo electrónico"
                                        required
                                        density="compact"
                                        v-model="form.correo"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-select
                                        :hide-details="
                                            form.errors?.nivel ? false : true
                                        "
                                        :error="
                                            form.errors?.nivel ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.nivel
                                                ? form.errors?.nivel
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        clearable
                                        :items="listNivel"
                                        item-value="value"
                                        item-title="label"
                                        label="Nivel de Importancia*"
                                        v-model="form.nivel"
                                        required
                                    ></v-select>
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
