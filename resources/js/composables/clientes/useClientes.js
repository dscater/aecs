import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oCliente = ref({
    id: 0,
    razon_social: "",
    tipo: "",
    descripcion: "",
    nit: "",
    dir: "",
    fono: "",
    correo: "",
    nivel: "",
    _method: "POST",
});

export const useClientes = () => {
    const { flash } = usePage().props;
    const getClientes = async (data) => {
        try {
            const response = await axios.get(route("clientes.listado"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.clientes;
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

    const getClientesApi = async (data) => {
        try {
            const response = await axios.get(
                route("clientes.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.clientes;
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
    const saveCliente = async (data) => {
        try {
            const response = await axios.post(route("clientes.store", data), {
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteCliente = async (id) => {
        try {
            const response = await axios.delete(
                route("clientes.destroy", id),
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

    const setCliente = (item = null) => {
        if (item) {
            oCliente.value.id = item.id;
            oCliente.value.razon_social = item.razon_social;
            oCliente.value.tipo = item.tipo;
            oCliente.value.descripcion = item.descripcion;
            oCliente.value.nit = item.nit;
            oCliente.value.dir = item.dir;
            oCliente.value.fono = item.fono;
            oCliente.value.correo = item.correo;
            oCliente.value.nivel = item.nivel;
            oCliente.value._method = "PUT";
            return oCliente;
        }
        return false;
    };

    const limpiarCliente = () => {
        oCliente.value.id = 0;
        oCliente.value.razon_social = "";
        oCliente.value.tipo = "";
        oCliente.value.descripcion = "";
        oCliente.value.nit = "";
        oCliente.value.dir = "";
        oCliente.value.fono = "";
        oCliente.value.correo = "";
        oCliente.value.nivel = "";
        oCliente.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oCliente,
        getClientes,
        getClientesApi,
        saveCliente,
        deleteCliente,
        setCliente,
        limpiarCliente,
    };
};
