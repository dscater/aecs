<script setup>
import App from "@/Layouts/App.vue";
defineOptions({
    layout: App,
});
import { computed, onMounted, ref } from "vue";
import { useApp } from "@/composables/useApp";
// componentes
import BreadBrums from "@/Components/BreadBrums.vue";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { usePage, Head } from "@inertiajs/vue3";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";

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

const props_page = defineProps({
    array_infos: {
        type: Array,
    },
});

const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
];
const { setLoading } = useApp();

const form1 = ref({
    filtro: "A la fecha",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const form2 = ref({
    filtro: "A la fecha",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const form3 = ref({
    filtro: "A la fecha",
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const form4 = ref({
    filtro: "anio",
});
const index_filtro4 = ref(0);
const listFiltro4 = ref([
    { value: "anio", label: "Anual" },
    { value: "mes", label: "Por mes" },
    { value: "dia", label: "Por día" },
]);

const cambiaIndex = (val) => {
    index_filtro4.value = index_filtro4.value + val;
    if (index_filtro4.value < 0) {
        index_filtro4.value = 2;
    }
    if (index_filtro4.value > 2) {
        index_filtro4.value = 0;
    }
    form4.value.filtro = listFiltro4.value[index_filtro4.value].value;
    generarReporte4();
};

const generarReporte1 = async () => {
    generando.value = true;

    axios
        .get(route("graf1"), {
            params: form1.value,
        })
        .then((response) => {
            console.log(response.data.data);

            // Create the chart
            Highcharts.chart("container1", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "Técnicos con solicitud de atención",
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
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:0f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:0f}</b><br/>',
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
};
const generarReporte2 = async () => {
    generando.value = true;

    axios
        .get(route("graf2"), {
            params: form2.value,
        })
        .then((response) => {
            console.log(response.data.categories);
            console.log(response.data.data);

            // Create the chart
            Highcharts.chart("container2", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "Cantidad de asignación a técnicos",
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
                    categories: response.data.categories,
                },
                yAxis: {
                    title: {
                        text: "Total",
                    },
                },
                legend: {
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:0f}",
                        },
                    },
                },
                series: response.data.data,
            });
            generando.value = false;
        });
};
const generarReporte3 = async () => {
    generando.value = true;

    axios
        .get(route("graf3"), {
            params: form3.value,
        })
        .then((response) => {
            console.log(response.data.categories);
            console.log(response.data.data);

            // Create the chart
            Highcharts.chart("container3", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "Cantidad de servicios atentidos por técnicos",
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
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:0f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:0f}</b><br/>',
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
};
const generarReporte4 = async () => {
    generando.value = true;

    axios
        .get(route("graf4"), {
            params: form4.value,
        })
        .then((response) => {
            // Create the chart 1
            Highcharts.chart("container4", {
                title: {
                    align: "center",
                    text: "Técnicos con solicitud de atención",
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
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:0f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:0f}</b><br/>',
                },
                series: [
                    {
                        name: "Total",
                        colorByPoint: true,
                        data: response.data.data1,
                    },
                ],
            });

            // Create the chart 2
            Highcharts.chart("container5", {
                title: {
                    align: "center",
                    text: "Cantidad de asignación a técnicos",
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
                    categories: response.data.categories,
                },
                yAxis: {
                    title: {
                        text: "Total",
                    },
                },
                legend: {
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:0f}",
                        },
                    },
                },
                series: response.data.data2,
            });

            // Create the chart 3
            Highcharts.chart("container6", {
                title: {
                    align: "center",
                    text: "Cantidad de servicios atendidos por técnicos",
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
                    enabled: true,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:0f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:0f}</b><br/>',
                },
                series: [
                    {
                        name: "Total",
                        colorByPoint: true,
                        data: response.data.data3,
                    },
                ],
            });
            generando.value = false;
        });
};

const generando = ref(false);

function obtenerFechaActual() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    const mes = (fecha.getMonth() + 1).toString().padStart(2, "0");
    const dia = fecha.getDate().toString().padStart(2, "0");

    return `${año}-${mes}-${dia}`;
}

const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return `Generar Reporte Gráfico <i class="mdi mdi-chart-bar"></i>`;
});

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);

    generarReporte1();
    generarReporte2();
    generarReporte3();
    generarReporte4();
});
const { oConfiguracion } = useConfiguracion();

const { props } = usePage();
const user = props.auth.user;
</script>
<template>
    <Head title="Inicio"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row>
            <v-col
                cols="12"
                sm="6"
                md="6"
                xl="4"
                v-for="info_box in props_page.array_infos"
            >
                <v-card>
                    <v-card-text class="pa-1">
                        <v-row>
                            <v-col
                                cols="8"
                                class="bg-blue text-white d-flex flex-column justify-center align-center"
                            >
                                <h4
                                    class="text-h5 text-center font-weight-black mb-3"
                                >
                                    {{ info_box.label }}
                                </h4>
                                <h4
                                    class="text-h4 text-center font-weight-black"
                                >
                                    {{ info_box.cantidad }}
                                </h4>
                            </v-col>
                            <v-col cols="4" class="pa-0">
                                <v-card
                                    class="pa-2 ma-0 d-flex align-center justify-center text-icon"
                                    variant="tonal"
                                    color="blue"
                                >
                                    <v-icon>{{ info_box.icon }}</v-icon>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="12" lg="6">
                <v-card>
                    <v-card-title>
                        <h4>Técnicos con solicitud de atención</h4>
                    </v-card-title>
                    <v-card-text>
                        <v-row v-if="user.tipo == 'GERENTE TÉCNICO'">
                            <v-col cols="12">
                                <form @submit.prevent="generarReporte1">
                                    <v-row>
                                        <v-col cols="12" class="pb-0">
                                            <v-select
                                                :hide-details="true"
                                                variant="outlined"
                                                density="compact"
                                                required
                                                :items="[
                                                    'A la fecha',
                                                    'Rango de Fechas',
                                                ]"
                                                no-data-text="Sin registros..."
                                                label="Seleccionar"
                                                v-model="form1.filtro"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            class="pb-0 pt-3"
                                            v-if="
                                                form1.filtro ==
                                                'Rango de Fechas'
                                            "
                                        >
                                            <v-row>
                                                <v-col cols="6">
                                                    <v-text-field
                                                        :hide-details="true"
                                                        density="compact"
                                                        variant="underlined"
                                                        color="primary"
                                                        type="date"
                                                        label="Fecha Inicio*"
                                                        required
                                                        v-model="
                                                            form1.fecha_ini
                                                        "
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-text-field
                                                        :hide-details="true"
                                                        density="compact"
                                                        variant="underlined"
                                                        color="primary"
                                                        type="date"
                                                        label="Fecha Fin*"
                                                        required
                                                        v-model="
                                                            form1.fecha_fin
                                                        "
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col cols="12" class="pb-5 pt-1">
                                            <v-btn
                                                color="blue"
                                                block
                                                @click="generarReporte1"
                                                :disabled="generando"
                                                v-html="txtBtn"
                                            ></v-btn>
                                        </v-col>
                                    </v-row>
                                </form>
                            </v-col>
                        </v-row>
                        <div id="container1"></div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="12" lg="6">
                <v-card>
                    <v-card-title>
                        <h4>Cantidad de asignación a técnicos</h4>
                    </v-card-title>
                    <v-card-text>
                        <v-row v-if="user.tipo == 'GERENTE TÉCNICO'">
                            <v-col cols="12">
                                <form @submit.prevent="generarReporte2">
                                    <v-row>
                                        <v-col cols="12" class="pb-0">
                                            <v-select
                                                :hide-details="true"
                                                variant="outlined"
                                                density="compact"
                                                required
                                                :items="[
                                                    'A la fecha',
                                                    'Rango de Fechas',
                                                ]"
                                                no-data-text="Sin registros..."
                                                label="Seleccionar"
                                                v-model="form2.filtro"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            class="pb-0 pt-3"
                                            v-if="
                                                form2.filtro ==
                                                'Rango de Fechas'
                                            "
                                        >
                                            <v-row>
                                                <v-col cols="6">
                                                    <v-text-field
                                                        :hide-details="true"
                                                        density="compact"
                                                        variant="underlined"
                                                        color="primary"
                                                        type="date"
                                                        label="Fecha Inicio*"
                                                        required
                                                        v-model="
                                                            form2.fecha_ini
                                                        "
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-text-field
                                                        :hide-details="true"
                                                        density="compact"
                                                        variant="underlined"
                                                        color="primary"
                                                        type="date"
                                                        label="Fecha Fin*"
                                                        required
                                                        v-model="
                                                            form2.fecha_fin
                                                        "
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col cols="12" class="pb-5 pt-1">
                                            <v-btn
                                                color="blue"
                                                block
                                                @click="generarReporte2"
                                                :disabled="generando"
                                                v-html="txtBtn"
                                            ></v-btn>
                                        </v-col>
                                    </v-row>
                                </form>
                            </v-col>
                        </v-row>
                        <div id="container2"></div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="12" lg="6">
                <v-card>
                    <v-card-title>
                        <h4>Cantidad de servicios atentidos por técnicos</h4>
                    </v-card-title>
                    <v-card-text>
                        <v-row v-if="user.tipo == 'GERENTE TÉCNICO'">
                            <v-col cols="12">
                                <form @submit.prevent="generarReporte3">
                                    <v-row>
                                        <v-col cols="12" class="pb-0">
                                            <v-select
                                                :hide-details="true"
                                                variant="outlined"
                                                density="compact"
                                                required
                                                :items="[
                                                    'A la fecha',
                                                    'Rango de Fechas',
                                                ]"
                                                no-data-text="Sin registros..."
                                                label="Seleccionar"
                                                v-model="form3.filtro"
                                            ></v-select>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            class="pb-0 pt-3"
                                            v-if="
                                                form3.filtro ==
                                                'Rango de Fechas'
                                            "
                                        >
                                            <v-row>
                                                <v-col cols="6">
                                                    <v-text-field
                                                        :hide-details="true"
                                                        density="compact"
                                                        variant="underlined"
                                                        color="primary"
                                                        type="date"
                                                        label="Fecha Inicio*"
                                                        required
                                                        v-model="
                                                            form3.fecha_ini
                                                        "
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-text-field
                                                        :hide-details="true"
                                                        density="compact"
                                                        variant="underlined"
                                                        color="primary"
                                                        type="date"
                                                        label="Fecha Fin*"
                                                        required
                                                        v-model="
                                                            form3.fecha_fin
                                                        "
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                        <v-col cols="12" class="pb-5 pt-1">
                                            <v-btn
                                                color="blue"
                                                block
                                                @click="generarReporte3"
                                                :disabled="generando"
                                                v-html="txtBtn"
                                            ></v-btn>
                                        </v-col>
                                    </v-row>
                                </form>
                            </v-col>
                        </v-row>
                        <div id="container3"></div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h4
                                    class="w-100 text-h5 text-center font-weight-bold"
                                >
                                    Resumen de solicitudes y servicios
                                </h4>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12 pt-0">
                                <div class="filtro_resumen w-100 text-center">
                                    <span
                                        ><v-btn
                                            icon="mdi-arrow-left"
                                            size="sm"
                                            class="pa-1 mx-2"
                                            @click="cambiaIndex(-1)"
                                        ></v-btn
                                    ></span>
                                    <v-chip color="primary"
                                        >{{ listFiltro4[index_filtro4].label }}
                                    </v-chip>
                                    <span
                                        ><v-btn
                                            icon="mdi-arrow-right"
                                            size="sm"
                                            class="pa-1 mx-2"
                                            @click="cambiaIndex(1)"
                                        ></v-btn
                                    ></span>
                                </div>
                            </v-col>
                            <v-col cols="12">
                                <div id="container4"></div>
                            </v-col>
                            <v-col cols="12">
                                <div id="container5"></div>
                            </v-col>
                            <v-col cols="12">
                                <div id="container6"></div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-card variant="flat" color="blue">
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h4 class="text-center text-h3">
                                    {{ oConfiguracion.nombre_sistema }}
                                </h4>
                            </v-col>
                            <v-col cols="12">
                                <h4 class="text-center text-h4">
                                    Bienvenid@ {{ props.auth.user.full_name }}
                                </h4>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
