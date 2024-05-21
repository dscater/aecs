import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oServicio = reactive({
    id: 0,
    cliente_id: "",
    personal_id: "",
    fecha: "",
    hora_ini: "",
    hora_fin: "",
    total_horas: "",
    ubicacion: "",
    tipo: "",
    marca: "",
    modelo: "",
    nro_serie: "",
    nro_parte: "",
    nro_activo: "",
    tipo_servicio: "",
    foto: "",
    problema: "",
    trabajo_realizado: "",
    partes: "",
    tipo_trabajo: "",
    _method: "POST",
});

export const useServicios = () => {
    const { flash } = usePage().props;
    const getServicios = async (data) => {
        try {
            const response = await axios.get(route("servicios.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.servicios;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getServiciosApi = async (data) => {
        try {
            const response = await axios.get(
                route("servicios.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.servicios;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getServiciosEquiposApi = async (data) => {
        try {
            const response = await axios.get(
                route("equipo_accesorios.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.servicios;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };
    const saveServicio = async (data) => {
        try {
            const response = await axios.post(route("servicios.store", data), {
                headers: { Accept: "application/json" },
            });
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteServicio = async (id) => {
        try {
            const response = await axios.delete(
                route("servicios.destroy", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setServicio = (item = null) => {
        if (item) {
            oServicio.id = item.id;
            oServicio.cliente_id = item.cliente_id;
            oServicio.personal_id = item.personal_id;
            oServicio.fecha = item.fecha;
            oServicio.hora_ini = item.hora_ini;
            oServicio.hora_fin = item.hora_fin;
            oServicio.total_horas = item.total_horas;
            oServicio.ubicacion = item.ubicacion;
            oServicio.tipo = item.tipo;
            oServicio.marca = item.marca;
            oServicio.modelo = item.modelo;
            oServicio.nro_serie = item.nro_serie;
            oServicio.nro_parte = item.nro_parte;
            oServicio.nro_activo = item.nro_activo;
            oServicio.tipo_servicio = item.tipo_servicio;
            oServicio.foto = item.foto;
            oServicio.problema = item.problema;
            oServicio.trabajo_realizado = item.trabajo_realizado;
            oServicio.partes = item.partes;
            oServicio.tipo_trabajo = item.tipo_trabajo;
            oServicio._method = "PUT";
            return oServicio;
        }
        return false;
    };

    const limpiarServicio = () => {
        oServicio.id = 0;
        oServicio.cliente_id = "";
        oServicio.personal_id = "";
        oServicio.fecha = "";
        oServicio.hora_ini = "";
        oServicio.hora_fin = "";
        oServicio.total_horas = "";
        oServicio.ubicacion = "";
        oServicio.tipo = "";
        oServicio.marca = "";
        oServicio.modelo = "";
        oServicio.nro_serie = "";
        oServicio.nro_parte = "";
        oServicio.nro_activo = "";
        oServicio.tipo_servicio = "";
        oServicio.foto = "";
        oServicio.problema = "";
        oServicio.trabajo_realizado = "";
        oServicio.partes = "";
        oServicio.tipo_trabajo = "";
        oServicio._method = "POST";
    };

    onMounted(() => {});

    return {
        oServicio,
        getServicios,
        getServiciosApi,
        getServiciosEquiposApi,
        saveServicio,
        deleteServicio,
        setServicio,
        limpiarServicio,
    };
};
