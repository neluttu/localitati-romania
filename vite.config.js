import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import fs from "node:fs";
import path from "node:path";

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
        https: {
            key: fs.readFileSync("/var/www/_ssl/vite.key"),
            cert: fs.readFileSync("/var/www/_ssl/vite.crt"),
        },
        origin: "https://localitati.devserver.ro",

        // HMR BEHIND REVERSE PROXY
        hmr: {
            clientPort: 443,
        },
    },
});
