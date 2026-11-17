<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | POS Zeti</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl p-8 text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-3xl mb-5 shadow-inner">
            404
        </div>
        <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
            Halaman atau tautan yang Anda cari tidak tersedia, telah dipindahkan, atau alamat URL salah.
        </p>
        <div class="flex flex-col sm:flex-row gap-2 justify-center">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/25 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Dashboard
            </a>
            <button onclick="window.history.back()" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition">
                Kembali Sebelumnya
            </button>
        </div>
    </div>
</body>
</html>
