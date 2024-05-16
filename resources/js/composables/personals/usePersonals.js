import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oPersonal = ref({
    id: 0,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    estado_civil: "",
    fecha_nac: "",
    cel: "",
    domicilio: "",
    especialidad: "",
    record: "",
    hoja_vida: "",
    foto: "",
    // appends
    url_hoja_vida: "",
    url_foto: "",
    _method: "POST",
});

export const usePersonals = () => {
    const { flash } = usePage().props;
    const getPersonals = async () => {
        try {
            const response = await axios.get(route("personals.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.personals;
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

    const getPersonalsApi = async (data) => {
        try {
            const response = await axios.get(
                route("personals.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.personals;
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
    const savePersonal = async (data) => {
        try {
            const response = await axios.post(route("personals.store", data), {
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

    const deletePersonal = async (id) => {
        try {
            const response = await axios.delete(
                route("personals.destroy", id),
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

    const setPersonal = (item = null, hoja_vida = false) => {
        if (item) {
            oPersonal.value.id = item.id;
            oPersonal.value.nombre = item.nombre;
            oPersonal.value.paterno = item.paterno;
            oPersonal.value.materno = item.materno;
            oPersonal.value.ci = item.ci;
            oPersonal.value.ci_exp = item.ci_exp;
            oPersonal.value.estado_civil = item.estado_civil;
            oPersonal.value.fecha_nac = item.fecha_nac;
            oPersonal.value.cel = item.cel;
            oPersonal.value.domicilio = item.domicilio;
            oPersonal.value.especialidad = item.especialidad;
            oPersonal.value.record = item.record;
            oPersonal.value.hoja_vida = item.hoja_vida;
            oPersonal.value.foto = item.foto;
            if (hoja_vida) {
                oPersonal.value.url_hoja_vida = item.url_hoja_vida;
            }
            oPersonal.value._method = "PUT";
            return oPersonal;
        }
        return false;
    };

    const limpiarPersonal = () => {
        oPersonal.value.id = 0;
        oPersonal.value.nombre = "";
        oPersonal.value.paterno = "";
        oPersonal.value.materno = "";
        oPersonal.value.ci = "";
        oPersonal.value.ci_exp = "";
        oPersonal.value.estado_civil = "";
        oPersonal.value.fecha_nac = "";
        oPersonal.value.cel = "";
        oPersonal.value.domicilio = "";
        oPersonal.value.especialidad = "";
        oPersonal.value.record = "";
        oPersonal.value.hoja_vida = "";
        oPersonal.value.foto = "";
        oPersonal.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oPersonal,
        getPersonals,
        getPersonalsApi,
        savePersonal,
        deletePersonal,
        setPersonal,
        limpiarPersonal,
    };
};
