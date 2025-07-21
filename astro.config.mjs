// @ts-check
import { defineConfig } from "astro/config";
import icon from "astro-icon";
import tailwindcss from "@tailwindcss/vite";

// https://astro.build/config
export default defineConfig({
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
});
