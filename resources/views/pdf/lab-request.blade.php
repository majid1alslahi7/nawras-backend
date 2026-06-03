@php
    $logoPath = public_path('brand/nawras-logo.jpg');
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px; }
        body { direction: rtl; font-family: "DejaVu Sans", sans-serif; color: #162d3d; font-size: 12px; line-height: 1.7; }
        .header { display: table; width: 100%; border-bottom: 2px solid #153751; padding-bottom: 14px; margin-bottom: 18px; }
        .brand, .meta { display: table-cell; vertical-align: middle; }
        .meta { text-align: left; color: #60727f; font-size: 11px; }
        .logo { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; vertical-align: middle; margin-left: 12px; }
        .brand-text { display: inline-block; vertical-align: middle; }
        h1 { margin: 0; font-size: 25px; color: #153751; }
        .subtitle { color: #7d878d; }
        .title { text-align: center; font-size: 22px; font-weight: bold; margin: 14px 0; color: #153751; }
        .info { display: table; width: 100%; margin-bottom: 14px; }
        .row { display: table-row; }
        .cell { display: table-cell; border: 1px solid #e2e8ec; padding: 7px 9px; width: 50%; }
        .label { color: #73818a; font-size: 10px; display: block; }
        .value { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #153751; color: #fff; padding: 8px; border: 1px solid #153751; font-size: 11px; }
        td { padding: 8px; border: 1px solid #dfe7eb; }
        .urgent { color: #C85C5C; font-weight: bold; }
        .notes { min-height: 60px; border: 1px solid #e2e8ec; padding: 10px; margin-top: 16px; }
        .signature { margin-top: 36px; text-align: left; color: #60727f; }
        .line { border-top: 1px solid #8d9aa3; width: 170px; display: inline-block; margin-top: 26px; }
        .footer { position: fixed; bottom: 14px; left: 28px; right: 28px; text-align: center; font-size: 10px; color: #8a969e; border-top: 1px solid #e2e8ec; padding-top: 6px; }
    </style>
</head>
<body>
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
            <div>رقم الطلب: {{ $labRequest->request_number }}</div>
            <div>التاريخ: {{ optional($labRequest->request_date)->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    <div class="title">طلب فحوصات</div>

    <div class="info">
        <div class="row">
            <div class="cell"><span class="label">المريض</span><span class="value">{{ $labRequest->patient?->full_name ?? '-' }}</span></div>
            <div class="cell"><span class="label">رقم الملف</span><span class="value">{{ $labRequest->patient?->file_number ?? '-' }}</span></div>
        </div>
        <div class="row">
            <div class="cell"><span class="label">الطبيب</span><span class="value">{{ $labRequest->doctor?->full_name ?? '-' }}</span></div>
            <div class="cell"><span class="label">الأولوية</span><span class="{{ $labRequest->urgency === 'عادي' ? 'value' : 'urgent' }}">{{ $labRequest->urgency }}</span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الفحص</th>
                <th>التصنيف</th>
            </tr>
        </thead>
        <tbody>
            @foreach (($labRequest->tests_list_json ?? []) as $index => $test)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $test['test_name'] ?? '-' }}</td>
                    <td>{{ $test['category'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="notes">
        <span class="label">التشخيص السريري والملاحظات</span>
        <div>{{ $labRequest->clinical_diagnosis ?? '-' }}</div>
        <div>{{ $labRequest->notes ?? '' }}</div>
    </div>

    <div class="signature">
        توقيع الطبيب
        <br>
        <span class="line"></span>
    </div>

    <div class="footer">يرجى إحضار هذا الطلب عند مراجعة المختبر وتسليم النتيجة للعيادة بعد إكمال الفحوصات.</div>
</body>
</html>
