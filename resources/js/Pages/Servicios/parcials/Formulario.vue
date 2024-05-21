<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useServicios } from "@/composables/servicios/useServicios";
import { useClientes } from "@/composables/clientes/useClientes";
import { usePersonals } from "@/composables/personals/usePersonals";
import { useMenu } from "@/composables/useMenu";
import { watch, ref, reactive, computed, onMounted } from "vue";

const { mobile, cambiarUrl } = useMenu();
const { oServicio, limpiarServicio } = useServicios();
let form = useForm(oServicio);

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { getClientes } = useClientes();
const { getPersonals } = usePersonals();

const listClientes = ref([]);
const listPersonals = ref([]);
const listTipoServicios = ref([
    { value: "GARANTÍA", label: "GARANTÍA" },
    { value: "CONTRATO", label: "CONTRATO" },
    { value: "FACTURAR", label: "FACTURAR" },
    { value: "SOPORTE", label: "SOPORTE" },
    { value: "RELEVAMIENTO", label: "RELEVAMIENTO" },
    { value: "OTROS", label: "OTROS" },
]);
const listTipoTrabajos = ref([
    { value: "MANTENIMIENTO PREVENTIVO", label: "MANTENIMIENTO PREVENTIVO" },
    { value: "MANTENIMIENTO CORRECTIVO", label: "MANTENIMIENTO CORRECTIVO" },
]);

const tituloDialog = computed(() => {
    return oServicio.id == 0 ? `Agregar Servicio` : `Editar Servicio`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("servicios.store")
            : route("servicios.update", form.id);

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
            limpiarServicio();
            cambiarUrl(route("servicios.index"));
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

const cargarListas = async () => {
    listClientes.value = await getClientes();
    listPersonals.value = await getPersonals();
};
const foto = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const calcularHoras = () => {
    form.total_horas = "";
    if (form.hora_ini && form.hora_fin) {
        // Convertir las horas a objetos Date
        const inicio = new Date(`1970-01-01T${form.hora_ini}:00`);
        const fin = new Date(`1970-01-01T${form.hora_fin}:00`);

        // Calcular la diferencia en milisegundos
        let diferenciaMilisegundos = fin - inicio;

        // Si la diferencia es negativa, significa que el tiempo fin es al día siguiente
        if (diferenciaMilisegundos < 0) {
            diferenciaMilisegundos += 24 * 60 * 60 * 1000; // Agregar 24 horas en milisegundos
        }

        // Convertir la diferencia de milisegundos a horas
        const diferenciaHoras = diferenciaMilisegundos / (1000 * 60 * 60);

        form.total_horas = parseFloat(diferenciaHoras).toFixed(2);
    }
};

onMounted(() => {
    if (form.id && form.id != "") {
    } else {
    }
    cargarListas();
});
</script>

<template>
    <v-row class="mt-0">
        <v-col cols="12" class="d-flex justify-end">
            <template v-if="mobile">
                <v-btn
                    icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('servicios.index'))"
                ></v-btn>
                <v-btn icon="mdi-content-save" color="blue"></v-btn>
            </template>
            <template v-if="!mobile">
                <v-btn
                    prepend-icon="mdi-arrow-left"
                    class="mr-2"
                    @click="cambiarUrl(route('servicios.index'))"
                >
                    Volver</v-btn
                >
                <v-btn
                    :prepend-icon="
                        oServicio.id != 0 ? 'mdi-sync' : 'mdi-content-save'
                    "
                    color="blue"
                    @click="enviarFormulario"
                >
                    <span
                        v-text="
                            oServicio.id != 0
                                ? 'Actualizar Servicio'
                                : 'Guardar Servicio'
                        "
                    ></span
                ></v-btn>
            </template>
        </v-col>
    </v-row>
    <v-row>
        <v-col cols="12">
            <v-card>
                <v-card-title class="border-b bg-blue pa-5">
                    <v-icon
                        :icon="form.id == 0 ? 'mdi-plus' : 'mdi-pencil'"
                    ></v-icon>
                    <span class="text-h5" v-html="tituloDialog"></span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <form @submit.prevent="enviarFormulario">
                            <v-row>
                                <v-col cols="12">
                                    <h3 class="w-100 text-center">
                                        DATOS DEL CLIENTE
                                    </h3>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
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
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Cliente*"
                                        :items="listClientes"
                                        item-value="id"
                                        item-title="razon_social"
                                        required
                                        density="compact"
                                        v-model="form.cliente_id"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
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
                                        clearable
                                        variant="outlined"
                                        label="Seleccionar Responsable*"
                                        :items="listPersonals"
                                        item-value="id"
                                        item-title="full_name"
                                        required
                                        density="compact"
                                        v-model="form.personal_id"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
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
                                        variant="outlined"
                                        type="date"
                                        label="Fecha*"
                                        required
                                        density="compact"
                                        v-model="form.fecha"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.hora_ini ? false : true
                                        "
                                        :error="
                                            form.errors?.hora_ini ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.hora_ini
                                                ? form.errors?.hora_ini
                                                : ''
                                        "
                                        variant="outlined"
                                        type="time"
                                        label="Hora Inicio*"
                                        required
                                        density="compact"
                                        v-model="form.hora_ini"
                                        @change="calcularHoras"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.hora_fin ? false : true
                                        "
                                        :error="
                                            form.errors?.hora_fin ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.hora_fin
                                                ? form.errors?.hora_fin
                                                : ''
                                        "
                                        variant="outlined"
                                        type="time"
                                        label="Hora Final*"
                                        required
                                        density="compact"
                                        v-model="form.hora_fin"
                                        @change="calcularHoras"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.total_horas
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.total_horas
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.total_horas
                                                ? form.errors?.total_horas
                                                : ''
                                        "
                                        variant="outlined"
                                        type="number"
                                        readonly
                                        label="Total Horas*"
                                        required
                                        density="compact"
                                        v-model="form.total_horas"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <h3 class="w-100 text-center">
                                        DATOS DEL EQUIPO
                                    </h3>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.ubicacion
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.ubicacion
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.ubicacion
                                                ? form.errors?.ubicacion
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Ubicación*"
                                        required
                                        density="compact"
                                        v-model="form.ubicacion"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
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
                                        variant="outlined"
                                        label="Tipo"
                                        required
                                        density="compact"
                                        v-model="form.tipo"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.marca ? false : true
                                        "
                                        :error="
                                            form.errors?.marca ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.marca
                                                ? form.errors?.marca
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Marca"
                                        required
                                        density="compact"
                                        v-model="form.marca"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.modelo ? false : true
                                        "
                                        :error="
                                            form.errors?.modelo ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.modelo
                                                ? form.errors?.modelo
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Modelo"
                                        required
                                        density="compact"
                                        v-model="form.modelo"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.nro_serie
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.nro_serie
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.nro_serie
                                                ? form.errors?.nro_serie
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Número Serie"
                                        required
                                        density="compact"
                                        v-model="form.nro_serie"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.nro_parte
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.nro_parte
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.nro_parte
                                                ? form.errors?.nro_parte
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Número Parte"
                                        required
                                        density="compact"
                                        v-model="form.nro_parte"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-text-field
                                        :hide-details="
                                            form.errors?.nro_activo
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.nro_activo
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.nro_activo
                                                ? form.errors?.nro_activo
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Número Activo"
                                        required
                                        density="compact"
                                        v-model="form.nro_activo"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-select
                                        :hide-details="
                                            form.errors?.tipo_servicio
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.tipo_servicio
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.tipo_servicio
                                                ? form.errors?.tipo_servicio
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Tipo de servicio*"
                                        :items="listTipoServicios"
                                        item-value="value"
                                        item-title="label"
                                        required
                                        density="compact"
                                        v-model="form.tipo_servicio"
                                    ></v-select>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
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
                                        variant="outlined"
                                        accept="image/png, image/jpeg, image/bmp"
                                        placeholder="Foto"
                                        prepend-icon="mdi-camera"
                                        label="Foto"
                                        @change="cargaArchivo($event, 'foto')"
                                        ref="foto"
                                    ></v-file-input>
                                </v-col>
                                <v-col cols="12">
                                    <h3 class="w-100 text-center">INFORME</h3>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.problema ? false : true
                                        "
                                        :error="
                                            form.errors?.problema ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.problema
                                                ? form.errors?.problema
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Problema reportado según cliente*"
                                        rows="2"
                                        auto-grow
                                        density="compact"
                                        v-model="form.problema"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.trabajo_realizado
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.trabajo_realizado
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.trabajo_realizado
                                                ? form.errors?.trabajo_realizado
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Trabajos realizados*"
                                        rows="2"
                                        auto-grow
                                        density="compact"
                                        v-model="form.trabajo_realizado"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-textarea
                                        :hide-details="
                                            form.errors?.partes ? false : true
                                        "
                                        :error="
                                            form.errors?.partes ? true : false
                                        "
                                        :error-messages="
                                            form.errors?.partes
                                                ? form.errors?.partes
                                                : ''
                                        "
                                        variant="outlined"
                                        label="Partes utilizados"
                                        rows="2"
                                        auto-grow
                                        density="compact"
                                        v-model="form.partes"
                                    ></v-textarea>
                                </v-col>
                                <v-col cols="12" sm="6" md="4" xl="3">
                                    <v-select
                                        :hide-details="
                                            form.errors?.tipo_trabajo
                                                ? false
                                                : true
                                        "
                                        :error="
                                            form.errors?.tipo_trabajo
                                                ? true
                                                : false
                                        "
                                        :error-messages="
                                            form.errors?.tipo_trabajo
                                                ? form.errors?.tipo_trabajo
                                                : ''
                                        "
                                        clearable
                                        variant="outlined"
                                        label="Tipo de Trabajo*"
                                        :items="listTipoTrabajos"
                                        item-value="value"
                                        item-title="label"
                                        required
                                        density="compact"
                                        v-model="form.tipo_trabajo"
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </form>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<style scoped>
#google_map {
    width: 100%;
    height: 500px;
}
</style>
