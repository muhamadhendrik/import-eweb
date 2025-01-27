import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/bootstrap.js", "resources/css/app.css"],
            refresh: true,
        }),
    ],
    build: {
        outDir: "public/build", // Pastikan output diatur ke folder build di public
    },
});
