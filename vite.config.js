import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import fs from "node:fs";

const sslKeyPath = "/var/www/_ssl/vite.key";
const sslCertPath = "/var/www/_ssl/vite.crt";
const useHttps = fs.existsSync(sslKeyPath) && fs.existsSync(sslCertPath);

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
            ],
            refresh: {
                paths: [
                    "resources/views/**",
                    "resources/css/**",
                    "resources/js/**",
                    "app/Http/Controllers/**",
                    "routes/**",
                ],
                exclude: [
                    "storage/logs/**",
                    "**/*.log",
                    "access.log",
                ],
            },
        }),
    ],
    build: {
        minify: "esbuild",
        target: "es2017",
    },
    server: {
        host: "0.0.0.0",
        port: 5257,
        https: useHttps
            ? {
                  key: fs.readFileSync(sslKeyPath),
                  cert: fs.readFileSync(sslCertPath),
              }
            : false,
        origin: useHttps ? "https://localitati.devserver.ro" : undefined,

        // HMR BEHIND REVERSE PROXY
        hmr: useHttps
            ? {
                  clientPort: 443,
              }
            : true,
    },
});
