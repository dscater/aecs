import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oSolicitudAtencion = ref({
    id: 0,
    cliente_id: null,
    personal_id: null,
    descripcion: "",
    fecha: "",
    hora: "",
    _method: "POST",
});

export const useSolicitudAtencions = () => {
    const { flash } = usePage().props;
    const getSolicitudAtencions = async (data) => {
        try {
            const response = await axios.get(
                route("solicitud_atencions.listado"),
                {
                    headers: { Accept: "application/json" },
                    params: data,
                }
            );
            return response.data.solicitud_atencions;
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

    const getSolicitudAtencionsApi = async (data) => {
        try {
            const response = await axios.get(
                route("solicitud_atencions.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.solicitud_atencions;
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
    const saveSolicitudAtencion = async (data) => {
        try {
            const response = await axios.post(
                route("solicitud_atencions.store", data),
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteSolicitudAtencion = async (id) => {
        try {
            const response = await axios.delete(
                route("solicitud_atencions.destroy", id),
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

    const setSolicitudAtencion = (item = null) => {
        if (item) {
            oSolicitudAtencion.value.id = item.id;
            oSolicitudAtencion.value.cliente_id = item.cliente_id;
            oSolicitudAtencion.value.personal_id = item.personal_id;
            oSolicitudAtencion.value.descripcion = item.descripcion;
            oSolicitudAtencion.value.fecha = item.fecha;
            oSolicitudAtencion.value.hora = item.hora;
            oSolicitudAtencion.value._method = "PUT";
            return oSolicitudAtencion;
        }
        return false;
    };

    const limpiarSolicitudAtencion = () => {
        oSolicitudAtencion.value.id = 0;
        oSolicitudAtencion.value.cliente_id = null;
        oSolicitudAtencion.value.personal_id = null;
        oSolicitudAtencion.value.descripcion = "";
        oSolicitudAtencion.value.fecha = "";
        oSolicitudAtencion.value.hora = "";
        oSolicitudAtencion.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oSolicitudAtencion,
        getSolicitudAtencions,
        getSolicitudAtencionsApi,
        saveSolicitudAtencion,
        deleteSolicitudAtencion,
        setSolicitudAtencion,
        limpiarSolicitudAtencion,
    };
};
