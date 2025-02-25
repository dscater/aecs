<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Servicios y Seguimiento",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
import { useClientes } from "@/composables/clientes/useClientes";

exporting(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});

const { getClientes } = useClientes();
const { setLoading } = useApp();

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const form = ref({
    cliente_id: "todos",
    tipo_servicio: "todos",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Gráfico <i class="mdi mdi-chart-bar"></i>`;
});
const txtBtn2 = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Pdf <i class="mdi mdi-file-pdf-box"></i>`;
});

const listClientes = ref([]);
const listTipoServicios = ref([
    { value: "todos", label: "TODOS" },
    { value: "GARANTÍA", label: "GARANTÍA" },
    { value: "CONTRATO", label: "CONTRATO" },
    { value: "FACTURAR", label: "FACTURAR" },
    { value: "SOPORTE", label: "SOPORTE" },
    { value: "RELEVAMIENTO", label: "RELEVAMIENTO" },
    { value: "OTROS", label: "OTROS" },
]);
const formulario = ref(null);
const generarReporte = async () => {
    const { valid } = await formulario.value.validate();
    if (valid) {
        generando.value = true;

        axios
            .get(route("reportes.rg_servicios"), { params: form.value })
            .then((response) => {
                // Create the chart
                Highcharts.chart("container", {
                    chart: {
                        type: "column",
                    },
                    title: {
                        align: "center",
                        text: "Servicios",
                    },
                    subtitle: {
                        align: "left",
                        text: "",
                    },
                    accessibility: {
                        announceNewData: {
                            enabled: true,
                        },
                    },
                    xAxis: {
                        type: "category",
                    },
                    yAxis: {
                        title: {
                            text: "Total",
                        },
                    },
                    legend: {
                        enabled: false,
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: "{point.y:.0f}",
                            },
                        },
                    },

                    tooltip: {
                        headerFormat:
                            '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat:
                            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>',
                    },

                    series: [
                        {
                            name: "Total",
                            colorByPoint: true,
                            data: response.data.data,
                        },
                    ],
                });
                generando.value = false;
            });
    }
};

const generarReportePdf = () => {
    generando.value = true;
    const url = route("reportes.r_servicios", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};

const cargarListas = async () => {
    listClientes.value = await getClientes();

    listClientes.value.unshift({
        id: "todos",
        razon_social: "TODOS",
    });
};

function obtenerFechaActual() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
    const dia = fecha.getDate().toString().padStart(2, "0");

    return `${año}-${mes}-${dia}`;
}

onMounted(() => {
    cargarListas();
});
</script>
<template>
    <Head title="Reporte Servicios y Seguimiento"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row>
            <v-col cols="12" sm="12" md="12" xl="8" class="mx-auto">
                <v-card>
                    <v-card-item>
                        <v-container>
                            <v-form
                                @submit.prevent="generarReporte"
                                ref="formulario"
                            >
                                <v-row>
                                    <v-col cols="12">
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
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listClientes"
                                            item-value="id"
                                            item-title="razon_social"
                                            no-data-text="Sin registros..."
                                            label="Seleccionar Cliente*"
                                            v-model="form.cliente_id"
                                        ></v-autocomplete>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                    >
                                        <v-autocomplete
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
                                            variant="outlined"
                                            density="compact"
                                            required
                                            :items="listTipoServicios"
                                            item-value="value"
                                            item-title="label"
                                            no-data-text="Sin registros..."
                                            label="Seleccionar Tipo de Servicio*"
                                            v-model="form.tipo_servicio"
                                        ></v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-row>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form.errors?.fecha_ini
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form.errors?.fecha_ini
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form.errors?.fecha_ini
                                                            ? form.errors
                                                                  ?.fecha_ini
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Inicio*"
                                                    required
                                                    v-model="form.fecha_ini"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    :hide-details="
                                                        form.errors?.fecha_fin
                                                            ? false
                                                            : true
                                                    "
                                                    :error="
                                                        form.errors?.fecha_fin
                                                            ? true
                                                            : false
                                                    "
                                                    :error-messages="
                                                        form.errors?.fecha_fin
                                                            ? form.errors
                                                                  ?.fecha_fin
                                                            : ''
                                                    "
                                                    density="compact"
                                                    variant="underlined"
                                                    color="primary"
                                                    type="date"
                                                    label="Fecha Fin*"
                                                    required
                                                    v-model="form.fecha_fin"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="blue"
                                            block
                                            @click="generarReporte"
                                            :disabled="generando"
                                            v-html="txtBtn"
                                        ></v-btn>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-btn
                                            color="blue"
                                            block
                                            @click="generarReportePdf"
                                            :disabled="generando"
                                            v-html="txtBtn2"
                                        ></v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-item>
                </v-card>
            </v-col>
            <v-col cols="12">
                <div id="container"></div>
            </v-col>
        </v-row>
    </v-container>
</template>
