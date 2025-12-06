import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import path from "path";
import vueI18n from "@intlify/vite-plugin-vue-i18n";
import { copy } from 'vite-plugin-copy';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        vueI18n({
            include: path.resolve(__dirname, "./src/locales/**"),
        }),
        copy({
            targets: [
              { src: '.htaccess', dest: 'dist' } // Adjust 'dist' to your build output directory
            ],
            hook: 'writeBundle' // Run the copy operation after the build is complete
          })
    ],
    optimizeDeps: {
        include: ["quill", "nouislider"],
    },
    resolve: {
        alias: [
            {
                find: /^~(.*)$/,
                replacement: "node_modules/$1",
            },
            {
                find: "@",
                replacement: path.resolve(__dirname, "./src/"), // Corrected from ".src/" to "./src/"
            },
        ],
    },
});
