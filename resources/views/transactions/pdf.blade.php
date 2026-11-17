<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        @page {
            margin: 8px 10px 10px 10px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5px;
            line-height: 1.25;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .header {
            text-align: center;
            padding-bottom: 5px;
            border-bottom: 1px dashed #64748b;
        }
        .store-title {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .store-subtitle {
            font-size: 7.5px;
            color: #475569;
            margin-bottom: 2px;
        }
        .store-contact {
            font-size: 7.5px;
            color: #64748b;
        }
        .greeting {
            margin-top: 4px;
            font-size: 8px;
            font-weight: bold;
            color: #0f172a;
            font-style: italic;
        }
        .order-type-badge {
            margin-top: 4px;
            font-size: 8.5px;
            font-weight: bold;
            display: inline-block;
            padding: 1px 4px;
            border: 1px solid #0f172a;
        }

        .status-box {
            margin-top: 5px;
            padding: 4px;
            text-align: center;
            border: 1px dashed #dc2626;
            color: #991b1b;
            font-weight: bold;
            font-size: 8px;
        }
        .status-box.refund {
            border-color: #d97706;
            color: #92400e;
        }
        
        .meta-table {
            width: 100%;
            margin-top: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #64748b;
            font-size: 8px;
        }
        .meta-table td {
            padding: 1px 0;
        }

        .items-table {
            width: 100%;
            margin-top: 5px;
            border-collapse: collapse;
            font-size: 8px;
        }
        .items-table th {
            border-bottom: 1px dashed #64748b;
            padding-bottom: 3px;
            text-transform: uppercase;
            font-size: 7.5px;
            color: #475569;
        }
        .items-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }
        .item-name {
            font-weight: bold;
            color: #0f172a;
        }
        .item-variant {
            font-size: 7.5px;
            font-weight: bold;
            color: #2563eb;
        }
        .item-note {
            font-size: 7px;
            color: #64748b;
            font-style: italic;
        }
        .item-sub {
            font-size: 7px;
            color: #94a3b8;
        }

        .total-section {
            margin-top: 5px;
            padding-top: 3px;
            border-top: 1px dashed #64748b;
            font-size: 8px;
        }
        .total-table {
            width: 100%;
        }
        .total-table td {
            padding: 1.5px 0;
        }
        .grand-total {
            font-size: 10px;
            font-weight: bold;
            border-top: 1px dashed #0f172a;
            border-bottom: 1px dashed #0f172a;
            padding: 3px 0 !important;
        }

        .footer {
            margin-top: 8px;
            padding-top: 5px;
            border-top: 1px dashed #64748b;
            text-align: center;
        }
        .footer-thanks {
            font-size: 8.5px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .footer-come-again {
            font-size: 7.5px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 3px;
        }
        .footer-note {
            font-size: 6.5px;
            color: #94a3b8;
            line-height: 1.2;
        }
    </style>
</head>
<body>

    <!-- IDENTITAS TOKO & SALAM PEMBUKA (Task 10 & 31) -->
    <div class="header">
        <div class="store-title">{{ setting('store_name', 'POS ZETI') }}</div>
        @if(setting('store_slogan'))
            <div class="store-subtitle">{{ setting('store_slogan') }}</div>
        @endif
        @if(setting('store_address'))
            <div class="store-contact">{{ setting('store_address') }}</div>
        @endif
        <div class="store-contact">
            @if(setting('store_phone')) Telp: {{ setting('store_phone') }} @endif
            @if(setting('store_website')) | {{ setting('store_website') }} @endif
        </div>
        <div class="greeting">*** {{ setting('receipt_header_msg', 'Selamat Datang & Selamat Berbelanja') }} ***</div>
        
        <!-- TIPE PESANAN (Task 19) -->
        <div class="order-type-badge">
            {{ ($transaction->order_type ?? 'dine_in') === 'take_away' ? 'TAKE AWAY (BAWA PULANG)' : 'DINE IN (MAKAN DI TEMPAT)' }}
        </div>
    </div>

    <!-- BANNER STATUS JIKA VOID / REFUND (Task 17 & 22) -->
    @if($transaction->status === 'voided')
        <div class="status-box">
            *** TRANSAKSI DIBATALKAN (VOID) ***<br>
            <span style="font-size: 7px; font-weight: normal;">Alasan: {{ $transaction->void_reason }}</span>
        </div>
    @elseif($transaction->status === 'refunded')
        <div class="status-box refund">
            *** PENGEMBALIAN DANA (REFUND) ***<br>
            <span style="font-size: 7px; font-weight: normal;">Dana Dikembalikan: Rp {{ number_format($transaction->refund_amount ?? $transaction->total_amount, 0, ',', '.') }}<br>Alasan: {{ $transaction->refund_reason }}</span>
        </div>
    @endif

    <!-- META INFORMASI & KASIR (Task 10) -->
    <table class="meta-table">
        <tr>
            <td class="text-left font-bold" style="width: 50%;">No: {{ $transaction->invoice_number }}</td>
            <td class="text-right" style="width: 50%;">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="text-left">Kasir: <strong class="uppercase">{{ $transaction->user->name ?? 'Kasir Utama' }}</strong></td>
            <td class="text-right">Bayar: <strong class="uppercase">{{ $transaction->paymentMethod->name ?? ($transaction->payment_method ?? 'CASH') }}</strong></td>
        </tr>
        @if($transaction->customer)
            <tr>
                <td colspan="2" class="text-left" style="color: #047857; font-weight: bold;">
                    Member: {{ $transaction->customer->name }} ({{ $transaction->customer->membership_tier }} - {{ $transaction->customer->member_code }})
                </td>
            </tr>
        @endif
        @if($transaction->note)
            <tr>
                <td colspan="2" class="text-left" style="font-style: italic; color: #475569;">
                    Note: {{ $transaction->note }}
                </td>
            </tr>
        @endif
    </table>

    <!-- RINCIAN PRODUK DENGAN VARIAN & CATATAN KHUSUS (Task 20 & 21) -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-left" style="width: 50%;">Item</th>
                <th class="text-center" style="width: 12%;">Qty</th>
                <th class="text-right" style="width: 18%;">Harga</th>
                <th class="text-right" style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td class="text-left">
                        <div class="item-name">{{ $item->product->name ?? 'Item' }}</div>
                        @if($item->variant_name || $item->variant)
                            <div class="item-variant">Varian: {{ $item->variant_name ?? $item->variant->name }}</div>
                        @endif
                        @if($item->note)
                            <div class="item-note">Note: {{ $item->note }}</div>
                        @endif
                        <div class="item-sub">{{ $item->product->sku ?? '-' }}</div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PERHITUNGAN TOTAL -->
    <div class="total-section">
        <table class="total-table">
            <tr>
                <td class="text-left">Subtotal Item ({{ $transaction->items->sum('quantity') }} pcs)</td>
                <td class="text-right">Rp {{ number_format($transaction->items->sum('subtotal'), 0, ',', '.') }}</td>
            </tr>
            @if($transaction->discount > 0)
                <tr>
                    <td class="text-left">
                        Diskon
                        @if($transaction->promotion)
                            ({{ $transaction->promotion->code }})
                        @endif
                        @if($transaction->customer && $transaction->customer->discount_percent > 0)
                            (Member {{ (float)$transaction->customer->discount_percent }}%)
                        @endif
                    </td>
                    <td class="text-right">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($transaction->tax > 0)
                <tr>
                    <td class="text-left">{{ setting('tax_name', 'PPN') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($transaction->service_charge > 0)
                <tr>
                    <td class="text-left">{{ setting('service_charge_name', 'Biaya Layanan') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->service_charge, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td class="text-left font-bold uppercase">TOTAL</td>
                <td class="text-right font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->points_earned > 0)
                <tr>
                    <td class="text-left" style="color: #047857; font-weight: bold;">+ Poin Loyalitas</td>
                    <td class="text-right" style="color: #047857; font-weight: bold;">+{{ number_format($transaction->points_earned, 0, ',', '.') }} Poin</td>
                </tr>
            @endif
            <tr>
                <td class="text-left">Jumlah Dibayar</td>
                <td class="text-right">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-left font-bold">Kembalian</td>
                <td class="text-right font-bold">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- FOOTER: UCAPAN TERIMA KASIH & AJAKAN BERKUNJUNG KEMBALI (Task 10 & 31) -->
    <div class="footer">
        <div class="footer-thanks">{{ setting('receipt_footer_msg', 'TERIMA KASIH ATAS KUNJUNGAN ANDA!') }}</div>
        <div class="footer-note">
            Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa struk ini.<br>
            {{ setting('store_name', 'POS Zeti') }} &bull; Layanan: {{ setting('store_email', 'cs@poszeti.com') }}
        </div>
    </div>

</body>
</html>
