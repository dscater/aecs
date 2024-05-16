<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
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

const { oPersonal, limpiarPersonal } = usePersonals();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oPersonal.value);
watch(
    () => props.open_dialog,
    (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            form = useForm(oPersonal.value);
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

const listEstadoCivil = ["SOLTERO", "CASADO", "DIVORCIADO", "VIUDO"];
const listExpedido = [
    { value: "LP", label: "La Paz" },
    { value: "CB", label: "Cochabamba" },
    { value: "SC", label: "Santa Cruz" },
    { value: "CH", label: "Chuquisaca" },
    { value: "OR", label: "Oruro" },
    { value: "PT", label: "Potosi" },
    { value: "TJ", label: "Tarija" },
    { value: "PD", label: "Pando" },
    { value: "BN", label: "Beni" },
];

const hoja_vida = ref(null);
const foto = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const tituloDialog = computed(() => {
    return accion.value == 0 ? `Agregar Personal` : `Editar Personal`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("personals.store")
            : route("personals.update", form.id);

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
            limpiarPersonal();
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
                                            form.errors?.nombre ? false : true
                                        "
                                        :error="
                                            form.errors?.nombre ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.nombre
                                                ? form.errors?.nombre
                                                : ''
                                        "
                                        variant="underlined"
                                        color="blue"
                                        label="Nombre*"
                                        required
                                        density="compact"
                                        v-model="form.nombre"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.paterno ? false : true
                                        "
                                        :error="
                                            form.errors?.paterno ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.paterno
                                                ? form.errors?.paterno
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="Apellido Paterno*"
                                        v-model="form.paterno"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.materno ? false : true
                                        "
                                        :error="
                                            form.errors?.materno ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.materno
                                                ? form.errors?.materno
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="Apellido Materno"
                                        v-model="form.materno"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.ci ? false : true
                                        "
                                        :error="form.errors?.ci ? true : false"
                                        :error-messages="
                                            form.errors?.ci
                                                ? form.errors?.ci
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="C.I.*"
                                        v-model="form.ci"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-select
                                        :hide-details="
                                            form.errors?.ci_exp ? false : true
                                        "
                                        :error="
                                            form.errors?.ci_exp ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.ci_exp
                                                ? form.errors?.ci_exp
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        clearable
                                        :items="listExpedido"
                                        item-value="value"
                                        item-title="label"
                                        label="Expedido*"
                                        v-model="form.ci_exp"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-select
                                        :hide-details="
                                            form.errors?.estado_civil
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.estado_civil
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.estado_civil
                                                ? form.errors?.estado_civil
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        clearable
                                        :items="listEstadoCivil"
                                        label="Estado Civil*"
                                        v-model="form.estado_civil"
                                        required
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.fecha_nac
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.fecha_nac
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.fecha_nac
                                                ? form.errors?.fecha_nac
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        type="date"
                                        label="Fecha de Nacimiento*"
                                        v-model="form.fecha_nac"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.cel ? false : true
                                        "
                                        :error="form.errors?.cel ? true : false"
                                        :error-messages="
                                            form.errors?.cel
                                                ? form.errors?.cel
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="Celular*"
                                        v-model="form.cel"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.domicilio
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.domicilio
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.domicilio
                                                ? form.errors?.domicilio
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="Domicilio"
                                        v-model="form.domicilio"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.especialidad
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.especialidad
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.especialidad
                                                ? form.errors?.especialidad
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="Especialidad*"
                                        v-model="form.especialidad"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.record ? false : true
                                        "
                                        :error="
                                            form.errors?.record ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.record
                                                ? form.errors?.record
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        label="Record"
                                        v-model="form.record"
                                        required
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-file-input
                                        :hide-details="
                                            form.errors?.hoja_vida
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.hoja_vida
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.hoja_vida
                                                ? form.errors?.hoja_vida
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        placeholder="Hoja de Vida"
                                        prepend-icon="mdi-file-account-outline"
                                        label="Hoja de Vida"
                                        @change="
                                            cargaArchivo($event, 'hoja_vida')
                                        "
                                        ref="hoja_vida"
                                    ></v-file-input>
                                </v-col>
                                <v-col cols="12" sm="6" md="4">
                                    <v-file-input
                                        :hide-details="
                                            form.errors?.foto ? false : true
                                        "
                                        :error="
                                            form.errors?.foto ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.foto
                                                ? form.errors?.foto
                                                : ''
                                        "
                                        density="compact"
                                        variant="underlined"
                                        color="blue"
                                        accept="image/png, image/jpeg, image/bmp"
                                        placeholder="Foto"
                                        prepend-icon="mdi-camera"
                                        label="Foto"
                                        @change="cargaArchivo($event, 'foto')"
                                        ref="foto"
                                    ></v-file-input>
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
