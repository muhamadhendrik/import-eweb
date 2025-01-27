import "bootstrap";
import axios from "axios";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // key
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // cluster
    forceTLS: import.meta.env.VITE_PUSHER_SCHEME === 'https', // HTTPS
    enabledTransports: ['ws', 'wss'],
});

Echo.channel("import-progress").listen("ImportProgressUpdated", (e) => {
    console.log("Event received:", e);
    const progressBar = document.getElementById("progress-bar");
    const progressText = document.getElementById("progress-text");

    if (progressBar && progressText) {
        progressBar.style.width = `${e.progress}%`;
        progressText.innerText = `${e.progress}% completed`;
    } else {
        console.error("Progress bar elements not found in DOM.");
    }
});
