@php
    $logoPath = public_path('brand/nawras-logo.jpg');
    $isIncome = $transaction->type === 'إيراد';
    $title = $isIncome ? 'سند قبض' : 'سند صرف';
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 24px; }
        body { direction: rtl; font-family: "DejaVu Sans", sans-serif; color: #162d3d; font-size: 12px; line-height: 1.7; }
        .sheet { border: 1px solid #cfd8dc; padding: 22px; min-height: 92%; }
        .header { display: table; width: 100%; border-bottom: 2px solid #153751; padding-bottom: 14px; margin-bottom: 18px; }
        .brand, .meta { display: table-cell; vertical-align: middle; }
        .meta { text-align: left; color: #60727f; font-size: 11px; }
        .logo { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; vertical-align: middle; margin-left: 12px; }
        .brand-text { display: inline-block; vertical-align: middle; }
        h1 { margin: 0; font-size: 24px; color: #153751; }
        .subtitle { color: #7d878d; margin-top: 2px; }
        .doc-title { text-align: center; font-size: 22px; font-weight: bold; color: #153751; margin: 12px 0 18px; }
        .grid { display: table; width: 100%; margin-bottom: 14px; }
        .row { display: table-row; }
        .cell { display: table-cell; padding: 7px 9px; border: 1px solid #e2e8ec; width: 50%; }
        .label { color: #73818a; font-size: 10px; display: block; }
        .value { font-weight: bold; color: #162d3d; }
        .amount { text-align: center; border: 2px solid #153751; padding: 16px; margin: 18px 0; font-size: 18px; font-weight: bold; color: {{ $isIncome ? '#2E8B73' : '#C85C5C' }}; }
        .notes { min-height: 70px; border: 1px solid #e2e8ec; padding: 10px; margin-top: 10px; }
        .signatures { display: table; width: 100%; margin-top: 38px; }
        .sig { display: table-cell; width: 33%; text-align: center; color: #60727f; }
        .line { border-top: 1px solid #8d9aa3; margin: 28px 18px 4px; }
        .footer { position: fixed; bottom: 14px; left: 24px; right: 24px; text-align: center; font-size: 10px; color: #8a969e; border-top: 1px solid #e2e8ec; padding-top: 6px; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <div class="brand">
                @if (file_exists($logoPath))
                    <img class="logo" src="{{ $logoPath }}" alt="Nawras">
                @endif
                <div class="brand-text">
                    <h1>عيادة النورس</h1>
                    <div class="subtitle">رعايتكم رسالتنا</div>
                </div>
            </div>
            <div class="meta">
                <div>رقم السند: {{ $transaction->receipt_number }}</div>
                <div>التاريخ: {{ optional($transaction->transaction_date)->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="doc-title">{{ $title }}</div>

        <div class="grid">
            <div class="row">
                <div class="cell"><span class="label">التصنيف</span><span class="value">{{ $transaction->category?->name_ar ?? '-' }}</span></div>
                <div class="cell"><span class="label">طريقة الدفع</span><span class="value">{{ $transaction->payment_method ?? '-' }}</span></div>
            </div>
            <div class="row">
                <div class="cell"><span class="label">المريض</span><span class="value">{{ $transaction->patient?->full_name ?? 'غير مرتبط بمريض' }}</span></div>
                <div class="cell"><span class="label">رقم الموعد</span><span class="value">{{ $transaction->appointment_id ?? '-' }}</span></div>
            </div>
        </div>

        <div class="amount">
            المبلغ: {{ number_format((float) $transaction->total_amount, 2) }} ر.ي
        </div>

        <div class="notes">
            <span class="label">البيان</span>
            <div>{{ $transaction->description ?? $transaction->notes ?? '-' }}</div>
        </div>

        <div class="signatures">
            <div class="sig"><div class="line"></div>المحاسب</div>
            <div class="sig"><div class="line"></div>المستلم</div>
            <div class="sig"><div class="line"></div>الختم</div>
        </div>
    </div>

    <div class="footer">هذا المستند صادر آليا من نظام عيادة النورس ولا يحتاج إلى ختم إلكتروني عند طباعته من النظام.</div>
</body>
</html>
