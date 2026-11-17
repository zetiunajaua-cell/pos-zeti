<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Gangguan Server | POS Zeti</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 rounded-3xl border border-amber-200/80 dark:border-amber-950/50 shadow-2xl p-8 text-center">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-3xl mb-5 shadow-inner">
            500
        </div>
        <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Terjadi Gangguan Server</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
            Sistem mendeteksi kendala saat memproses permintaan Anda. Silakan muat ulang halaman atau hubungi administrator sistem.
        </p>
        <div class="flex flex-col sm:flex-row gap-2 justify-center">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white shadow-md shadow-amber-500/25 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Muat Ulang Halaman
            </button>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition">
                Beranda
            </a>
        </div>
    </div>
</body>
</html>
