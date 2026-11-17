<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Riwayat Aktivitas Pengguna (Audit Log - Task 32)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Log pencatatan aktivitas real-time admin dan kasir: buka terminal kasir, transaksi penjualan, void, refund, pengeluaran, login & logout.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="activityLogsWatcher({
            initialLogs: {{ Js::from($initialItems) }},
            feedUrl: '{{ route('activity-logs.index', request()->query()) }}'
        })" 
        x-init="initWatcher()"
        class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <!-- Real-Time Status & Controls Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white rounded-3xl p-4 sm:p-5 shadow-lg border border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-3.5 w-3.5">
                        <span x-show="autoSync" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3.5 w-3.5" :class="autoSync ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black tracking-tight" x-text="autoSync ? 'Real-Time Monitoring Aktif' : 'Real-Time Monitoring Dijeda'">Real-Time Monitoring Aktif</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider" :class="autoSync ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'" x-text="autoSync ? 'Live Sync' : 'Paused'">Live Sync</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">
                            Menangkap setiap aktivitas kasir (membuka kasir, checkout transaksi, void, refund) dan admin secara sinkron dan otomatis.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start md:self-auto">
                    <div class="text-[11px] font-mono text-slate-300 bg-slate-800/80 px-3 py-1.5 rounded-xl border border-slate-700">
                        Disinkronkan: <span class="font-bold text-white" x-text="lastSyncTime">Baru saja</span>
                    </div>

                    <button @click="toggleAutoSync()" type="button" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5"
                            :class="autoSync ? 'bg-slate-700 hover:bg-slate-600 text-slate-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white'">
                        <span x-show="autoSync">⏸ Jeda</span>
                        <span x-show="!autoSync">▶ Lanjut</span>
                    </button>

                    <button @click="fetchLogs(true)" type="button" :disabled="isFetching" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5 disabled:opacity-50">
                        <svg class="w-3.5 h-3.5" :class="{'animate-spin': isFetching}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="isFetching ? 'Sinkron...' : 'Segarkan'">Segarkan</span>
                    </button>
                </div>
            </div>

            <!-- Toast Flash Alert saat ada aktivitas kasir/admin baru -->
            <div x-show="hasNewActivity"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                 class="bg-emerald-600 text-white px-4 py-3 rounded-2xl shadow-lg flex items-center justify-between font-bold text-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-200 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Aktivitas baru kasir/admin baru saja masuk dan disinkronkan ke tabel di bawah!</span>
                </div>
                <button @click="hasNewActivity = false" class="text-emerald-100 hover:text-white">&times;</button>
            </div>

            <!-- Filter Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-slate-100 dark:border-slate-700/60">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Cari Keterangan / IP</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aksi, no. invoice..."
                               class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Pengguna / User</label>
                        <select name="user_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ (string)request('user_id') === (string)$u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->role?->name ?? 'Kasir' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Tipe Tindakan</label>
                        <select name="action" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tindakan</option>
                            @foreach($actionTypes as $code => $label)
                                <option value="{{ $code }}" {{ request('action') === $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-1">Tanggal</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md transition self-end">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'user_id', 'action', 'date']))
                            <a href="{{ route('activity-logs.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 transition self-end">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table of Activity Logs (Dynamic Real-Time via Alpine) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700/60 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/80 dark:bg-slate-900/60 text-[11px] font-black uppercase text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-700">
                            <tr>
                                <th class="px-5 py-3.5">Waktu</th>
                                <th class="px-5 py-3.5">Pengguna</th>
                                <th class="px-5 py-3.5">Aksi / Tindakan</th>
                                <th class="px-5 py-3.5">Deskripsi Aktivitas</th>
                                <th class="px-5 py-3.5">IP & Perangkat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <!-- Alpine Loop for Dynamic Real-Time Updates -->
                            <template x-for="log in items" :key="log.id">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/40 transition">
                                    <td class="px-5 py-4 font-mono text-[11px] text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        <span class="font-bold text-slate-800 dark:text-white block" x-text="log.created_at_date"></span>
                                        <span x-text="log.created_at_time"></span>
                                        <span class="text-[10px] text-slate-400 block" x-text="'(' + log.diff_for_humans + ')'"></span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-[10px]"
                                                 :class="log.user_role === 'Admin' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300'"
                                                 x-text="log.user_initials">
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-800 dark:text-white block" x-text="log.user_name"></span>
                                                <span class="text-[10px] text-slate-400" x-text="log.user_role"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider"
                                              :class="log.badge_class"
                                              x-text="log.action">
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-800 dark:text-slate-200 font-medium max-w-md">
                                        <span x-text="log.description"></span>
                                        <template x-if="log.properties_json">
                                            <div class="mt-1 text-[10px] text-slate-400 font-mono bg-slate-50 dark:bg-slate-900/80 p-1.5 rounded-lg border border-slate-100 dark:border-slate-800"
                                                 x-text="log.properties_json">
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-5 py-4 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                                        <span x-text="log.ip_address"></span>
                                        <template x-if="log.user_agent">
                                            <span class="block text-[10px] text-slate-400 max-w-xs truncate" :title="log.user_agent" x-text="log.user_agent"></span>
                                        </template>
                                    </td>
                                </tr>
                            </template>

                            <!-- State Kosong jika belum ada log -->
                            <tr x-show="items.length === 0">
                                <td colspan="5" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-10 h-10 mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Belum ada riwayat aktivitas yang tercatat sesuai filter.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        function activityLogsWatcher(config) {
            return {
                items: config.initialLogs || [],
                feedUrl: config.feedUrl || window.location.href,
                autoSync: true,
                isFetching: false,
                hasNewActivity: false,
                lastSyncTime: 'Baru saja',
                latestId: (config.initialLogs && config.initialLogs.length > 0) ? config.initialLogs[0].id : 0,
                timer: null,

                initWatcher() {
                    this.updateTimeLabel();
                    this.startPolling();

                    // Bersihkan interval saat halaman ditinggalkan
                    window.addEventListener('beforeunload', () => {
                        this.stopPolling();
                    });
                },

                updateTimeLabel() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const mins = String(now.getMinutes()).padStart(2, '0');
                    const secs = String(now.getSeconds()).padStart(2, '0');
                    this.lastSyncTime = hours + ':' + mins + ':' + secs + ' WIB';
                },

                startPolling() {
                    this.stopPolling();
                    if (!this.autoSync) return;
                    // Polling halus setiap 4 detik
                    this.timer = setInterval(() => {
                        if (this.autoSync && !document.hidden) {
                            this.fetchLogs(false);
                        }
                    }, 4000);
                },

                stopPolling() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },

                toggleAutoSync() {
                    this.autoSync = !this.autoSync;
                    if (this.autoSync) {
                        this.startPolling();
                        this.fetchLogs(true);
                    } else {
                        this.stopPolling();
                    }
                },

                fetchLogs(isManual = false) {
                    if (this.isFetching) return;
                    this.isFetching = true;

                    const separator = this.feedUrl.includes('?') ? '&' : '?';
                    const targetUrl = this.feedUrl + separator + 'feed=1&_t=' + Date.now();

                    fetch(targetUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network error');
                        return res.json();
                    })
                    .then(data => {
                        if (data && data.logs) {
                            // Cek jika ada log baru yang masuk
                            if (data.latest_id > this.latestId && this.latestId > 0) {
                                this.hasNewActivity = true;
                                setTimeout(() => {
                                    this.hasNewActivity = false;
                                }, 5000);
                            }

                            this.items = data.logs;
                            if (data.latest_id) {
                                this.latestId = data.latest_id;
                            }
                            this.updateTimeLabel();
                        }
                    })
                    .catch(err => {
                        console.warn('Sync activity logs error:', err);
                    })
                    .finally(() => {
                        this.isFetching = false;
                    });
                }
            };
        }
    </script>
</x-app-layout>
