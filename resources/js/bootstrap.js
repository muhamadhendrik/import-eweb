import "bootstrap";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// Menetapkan Pusher ke window
window.Pusher = Pusher;

// Inisialisasi Echo
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: import.meta.env.VITE_PUSHER_SCHEME === "https",
    enabledTransports: ["ws", "wss"],
});

// Fungsi untuk mengatur ulang progress bar
const resetProgress = () => {
    const progressBar = document.getElementById("progress-bar-import");
    const progressText = document.getElementById("progress-bar-import-text");
    const submitButton = document.getElementById("submit-button");

    // Atur ulang progress bar dan teks
    progressBar.style.width = "0%";
    progressBar.setAttribute("aria-valuenow", "0");
    progressText.innerText = "0% completed";

    // Pastikan tombol aktif saat halaman dimuat
    submitButton.disabled = false;
    submitButton.innerText = "Submit";
};

// Reset progress saat halaman pertama kali dibuka
window.onload = resetProgress;

window.Echo.channel("import-progress").listen("ImportProgressUpdated", (e) => {
    const progressBar = document.getElementById("progress-bar-import");
    const progressText = document.getElementById("progress-bar-import-text");
    const submitButton = document.getElementById("submit-button");

    // Update progress bar dan teks
    progressBar.style.width = e.progress + "%";
    progressBar.setAttribute("aria-valuenow", e.progress);
    progressText.innerText = e.progress + "% completed";

    // Nonaktifkan tombol selama proses berjalan
    if (e.progress < 100) {
        submitButton.disabled = true;
        submitButton.innerText = "Mohon Tunggu...";
    }

    // Jika progress mencapai 100%
    if (e.progress === 100) {
        submitButton.disabled = false; // Aktifkan tombol kembali
        submitButton.innerText = "Submit"; // Ubah teks tombol

        // Tampilkan SweetAlert dengan durasi 5 detik
        Swal.fire({
            title: "Selesai!",
            text: "Proses import selesai.",
            icon: "success",
            confirmButtonText: "OK",
            timer: 5000, // Durasi alert dalam milidetik
            timerProgressBar: true, // Menampilkan progress bar di dalam alert
        });
    }
});
