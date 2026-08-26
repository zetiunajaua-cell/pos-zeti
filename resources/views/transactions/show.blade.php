<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Transaksi - {{ $transaction->invoice_number }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid #e5e5e5;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .page-container {
            max-width: 1100px;
            margin: 30px auto;
        }

        .success-alert {
            background: #e8f7ee;
            color: #198754;
            border: 1px solid #b7e4c7;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 25px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .detail-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .invoice {
            color: #666;
            font-size: 14px;
        }

        .info-label {
            color: #777;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .info-value {
            font-weight: 600;
        }

        .table th {
            background: #f8f9fa;
            font-size: 14px;
        }

        .summary {
            max-width: 420px;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
        }

        .summary-total {
            font-size: 20px;
            font-weight: 700;
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 12px;
        }

        .btn-back {
            border-radius: 8px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .card {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg no-print">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">
            POS
        </a>

        <div class="d-flex align-items-center gap-4">
            <a href="{{ url('/dashboard') }}"
               class="text-decoration-none text-dark">
                Dashboard
            </a>

            <a href="{{ route('transactions.index') }}"
               class="text-decoration-none text-dark">
                Transaksi
            </a>

            <span class="text-muted">
                {{ auth()->user()->name ?? 'Kasir' }}
            </span>
        </div>
    </div>
</nav>


<div class="container page-container">

    {{-- Pesan berhasil --}}
    <div class="success-alert no-print">
        ✓ <strong>Transaksi berhasil disimpan.</strong>
    </div>


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="detail-title">Detail Transaksi</h4>

            <div class="invoice">
                {{ $transaction->invoice_number }}
            </div>
        </div>

        <div class="d-flex gap-2 no-print">

            <a href="{{ route('transactions.index') }}"
               class="btn btn-light btn-back">
                ← Kembali
            </a>

            <button onclick="window.print()"
                    class="btn btn-dark btn-back">
                🖨 Cetak
            </button>

        </div>

    </div>


    {{-- Detail transaksi --}}
    <div class="card mb-4">

        <div class="card-body p-4">

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">
                        {{ $transaction->created_at->format('d F Y') }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Waktu</div>
                    <div class="info-value">
                        {{ $transaction->created_at->format('H:i') }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Kasir</div>
                    <div class="info-value">
                        {{ $transaction->user->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Pembayaran</div>
                    <div class="info-value">
                        {{ ucfirst($transaction->payment_method ?? '-') }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- Detail produk --}}
    <div class="card mb-4">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-4">Detail Produk</h5>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($transaction->items as $item)

                            <tr>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $item->product->name ?? '-' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Ringkasan pembayaran --}}
    <div class="card">

        <div class="card-body p-4">

            <div class="summary">

                <div class="summary-row">
                    <span>Subtotal</span>

                    <strong>
                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Pembayaran</span>

                    <strong>
                        {{ ucfirst($transaction->payment_method ?? '-') }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Uang Dibayar</span>

                    <strong>
                        Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Kembalian</span>

                    <strong>
                        Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>TOTAL</span>

                    <strong>
                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </strong>
                </div>

            </div>

        </div>

    </div>


    {{-- Status --}}
    <div class="mt-4 text-center no-print">

        <div class="text-success fw-semibold">
            ✓ Transaksi Berhasil
        </div>

        <small class="text-muted">
            Transaksi telah berhasil disimpan ke sistem.
        </small>

    </div>

</div>

</body>
</html>