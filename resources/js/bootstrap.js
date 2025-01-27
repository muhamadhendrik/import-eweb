import 'bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Menetapkan Pusher ke window
window.Pusher = Pusher;

// Inisialisasi Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // key Pusher dari Vite
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // cluster Pusher dari Vite
    forceTLS: import.meta.env.VITE_PUSHER_SCHEME === 'https', // menggunakan TLS
    enabledTransports: ['ws', 'wss'], // untuk koneksi WebSocket
});

window.Echo.channel("import-progress").listen("ImportProgressUpdated", (e) => {
    const progressBar = document.getElementById("progress-bar-import");
    const progressText = document.getElementById("progress-bar-import-text");

    progressBar.style.width = e.progress + "%";
    progressText.innerText = e.progress + "% completed";
});
