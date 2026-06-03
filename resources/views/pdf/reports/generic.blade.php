@php
    $logoPath = public_path('brand/nawras-logo.jpg');
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 26px; }
        body { direction: rtl; font-family: "DejaVu Sans", sans-serif; color: #162d3d; font-size: 11px; line-height: 1.6; }
        .header { display: table; width: 100%; border-bottom: 2px solid #153751; padding-bottom: 12px; margin-bottom: 16px; }
        .brand, .meta { display: table-cell; vertical-align: middle; }
        .meta { text-align: left; color: #60727f; font-size: 10px; }
        .logo { width: 66px; height: 66px; border-radius: 50%; object-fit: cover; vertical-align: middle; margin-left: 10px; }
        .brand-text { display: inline-block; vertical-align: middle; }
        h1 { margin: 0; font-size: 23px; color: #153751; }
        .subtitle { color: #7d878d; margin-top: 2px; }
        .report-title { text-align: center; font-size: 21px; font-weight: bold; color: #153751; margin: 10px 0 4px; }
        .report-subtitle { text-align: center; color: #60727f; margin-bottom: 18px; }
        .section { margin-bottom: 18px; page-break-inside: avoid; }
        .section-title { font-size: 14px; font-weight: bold; color: #153751; margin-bottom: 7px; border-right: 4px solid #153751; padding-right: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #153751; color: #fff; padding: 7px; border: 1px solid #153751; font-size: 10px; }
        td { padding: 7px; border: 1px solid #dfe7eb; vertical-align: top; }
        tr:nth-child(even) td { background: #f7f9fa; }
        .empty { text-align: center; color: #8a969e; padding: 12px; }
        .footer { position: fixed; bottom: 12px; left: 26px; right: 26px; text-align: center; font-size: 9px; color: #8a969e; border-top: 1px solid #e2e8ec; padding-top: 5px; }
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
            <div>{{ $subtitle }}</div>
            <div>تم إنشاؤه من نظام عيادة النورس</div>
        </div>
    </div>

    <div class="report-title">{{ $title }}</div>
    <div class="report-subtitle">{{ $subtitle }}</div>

    @foreach ($sections as $section)
        <div class="section">
            <div class="section-title">{{ $section['title'] }}</div>
            <table>
                <thead>
                    <tr>
                        @foreach ($section['headers'] as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($section['rows'] as $row)
                        <tr>
                            @foreach ($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="{{ count($section['headers']) }}">لا توجد بيانات ضمن هذا التقرير</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">هذا التقرير صادر آليا من نظام عيادة النورس.</div>
</body>
</html>
