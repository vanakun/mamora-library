import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true,   // Bisa diakses publik
        port: 5173,   // Port default atau bebas
        strictPort: true, // Kalau port 5173 dipakai, error, tidak auto ganti
    },
});
