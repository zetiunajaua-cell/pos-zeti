import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

// Konfigurasi SweetAlert2 Toast Modern
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showClass: {
        popup: 'swal2-show animate__animated animate__fadeInRight animate__faster'
    },
    hideClass: {
        popup: 'swal2-hide animate__animated animate__fadeOutRight animate__faster'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

window.Toast = Toast;

// Helper Global: Toast Notifikasi Halus
window.showToast = (message, icon = 'success') => {
    const isDark = document.documentElement.classList.contains('dark');
    Toast.fire({
        icon: icon,
        title: message,
        background: isDark ? '#0f172a' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
        customClass: {
            popup: 'rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl'
        }
    });
};

// Helper Global: Modal Sukses Elegan
window.showSwalSuccess = (message, title = 'Berhasil!') => {
    const isDark = document.documentElement.classList.contains('dark');
    return Swal.fire({
        title: title,
        text: message,
        icon: 'success',
        confirmButtonText: 'Selesai',
        confirmButtonColor: '#2563eb',
        background: isDark ? '#0f172a' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
        customClass: {
            popup: 'rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl p-6',
            confirmButton: 'px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition'
        }
    });
};

// Helper Global: Modal Error Elegan
window.showSwalError = (message, title = 'Terjadi Kesalahan!') => {
    const isDark = document.documentElement.classList.contains('dark');
    return Swal.fire({
        title: title,
        html: message,
        icon: 'error',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#e11d48',
        background: isDark ? '#0f172a' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
        customClass: {
            popup: 'rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl p-6',
            confirmButton: 'px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition'
        }
    });
};

// Helper Global: Dialog Konfirmasi Modern (SweetAlert2)
window.confirmDialog = ({ 
    title = 'Apakah Anda yakin?', 
    text = 'Tindakan ini tidak dapat dibatalkan.', 
    icon = 'warning', 
    confirmButtonText = 'Ya, Lanjutkan', 
    cancelButtonText = 'Batal', 
    isDanger = true 
}) => {
    const isDark = document.documentElement.classList.contains('dark');
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        confirmButtonColor: isDanger ? '#e11d48' : '#2563eb',
        cancelButtonColor: isDark ? '#334155' : '#94a3b8',
        background: isDark ? '#0f172a' : '#ffffff',
        color: isDark ? '#f8fafc' : '#0f172a',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl p-6',
            title: 'text-base font-extrabold text-slate-900 dark:text-white',
            htmlContainer: 'text-xs text-slate-500 dark:text-slate-400',
            confirmButton: `px-5 py-2.5 rounded-xl ${isDanger ? 'bg-rose-600 hover:bg-rose-700' : 'bg-blue-600 hover:bg-blue-700'} text-white font-bold text-xs shadow-md transition mx-1`,
            cancelButton: 'px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition mx-1'
        }
    });
};

// Handler Otomatis Submit Form dengan Konfirmasi SweetAlert2
window.confirmSubmit = (event, message = 'Apakah Anda yakin ingin melanjutkan tindakan ini?', title = 'Konfirmasi Tindakan', isDanger = true) => {
    event.preventDefault();
    const targetElement = event.currentTarget || event.target;
    const form = targetElement.tagName === 'FORM' ? targetElement : targetElement.closest('form');
    if (!form) return;

    window.confirmDialog({
        title: title,
        text: message,
        isDanger: isDanger,
        confirmButtonText: isDanger ? 'Ya, Lanjutkan!' : 'Ya, Setuju'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

Alpine.start();
