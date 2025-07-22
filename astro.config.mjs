// @ts-check
import { defineConfig } from "astro/config";
import icon from "astro-icon";
import tailwindcss from "@tailwindcss/vite";

// https://astro.build/config
export default defineConfig({
  // Añadir la base URL para resolver correctamente las rutas en producción
  // Cambia '/ingecaal' por la ruta donde se aloja tu sitio en el servidor
  // Si está en la raíz del dominio, usa '/' o deja base vacío
  base: "/v1",

  integrations: [
    icon({
      include: {
        solar: ["*"], // (Default) Loads entire Material Design Icon set
      },
    }),
  ],
  vite: {
    plugins: [tailwindcss()],
  },
  output: "static",
});
