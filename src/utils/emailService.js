// Servicio para enviar emails usando un script PHP en el servidor

/**
 * Envía un email con los datos del formulario de contacto
 * @param {Object} formData - Datos del formulario
 * @param {string} formData.nombre - Nombre del remitente
 * @param {string} formData.empresa - Empresa del remitente (opcional)
 * @param {string} formData.email - Email del remitente
 * @param {string} formData.telefono - Teléfono del remitente
 * @param {string} formData.mensaje - Mensaje del remitente
 * @returns {Promise<Object>} - Resultado de la operación
 */
export async function enviarEmail(formData) {
  try {
    // Validar que los campos requeridos estén presentes
    if (
      !formData.nombre ||
      !formData.email ||
      !formData.telefono ||
      !formData.mensaje
    ) {
      return {
        success: false,
        message: "Todos los campos marcados con * son obligatorios",
      };
    }

    // Usar el script PHP para enviar el email
    // La URL debe ser relativa a la raíz del sitio web, teniendo en cuenta la base URL
    const response = await fetch(
      import.meta.env.BASE_URL + "/enviar-email.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          nombre: formData.nombre,
          empresa: formData.empresa || "No especificada",
          email: formData.email,
          telefono: formData.telefono,
          mensaje: formData.mensaje,
        }),
      },
    );

    if (response.ok) {
      // Intentar analizar la respuesta como JSON
      try {
        const data = await response.json();
        return {
          success: true,
          message: data.message || "Email enviado correctamente",
        };
      } catch (parseError) {
        console.error("Error al analizar la respuesta JSON:", parseError);
        return {
          success: true,
          message: "Email enviado correctamente",
        };
      }
    } else {
      // Intentar obtener el mensaje de error
      try {
        const errorData = await response.json();
        return {
          success: false,
          message: errorData.message || "Error al enviar el email",
        };
      } catch (parseError) {
        console.error("Error al analizar la respuesta de error:", parseError);
        return {
          success: false,
          message: `Error al enviar el email (${response.status}: ${response.statusText})`,
        };
      }
    }
  } catch (error) {
    console.error("Error al enviar el email:", error);
    return {
      success: false,
      message: "Error al enviar el email",
      error: error.message,
    };
  }
}
