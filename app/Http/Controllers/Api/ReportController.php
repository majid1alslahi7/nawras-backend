<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Transaction;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financial(Request $request): JsonResponse
    {
        return response()->json($this->financialPayload($request));
    }

    public function patientStats(): JsonResponse
    {
        return response()->json($this->patientStatsPayload());
    }

    public function doctorStats(): JsonResponse
    {
        return response()->json($this->doctorStatsPayload());
    }

    public function patients(): JsonResponse
    {
        return response()->json($this->patientsPayload());
    }

    public function visits(): JsonResponse
    {
        return response()->json($this->visitsPayload());
    }

    public function export(Request $request, string $report)
    {
        $format = strtolower($request->get('format', 'pdf'));
        abort_unless(in_array($format, ['pdf', 'csv'], true), 422, 'صيغة التصدير غير مدعومة');

        $payload = $this->exportPayload($report, $request);
        $fileName = $payload['file_name'] . '-' . now()->format('Ymd-His');

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($payload) {
                echo "\xEF\xBB\xBF";
                $output = fopen('php://output', 'w');

                foreach ($payload['sections'] as $section) {
                    fputcsv($output, [$section['title']]);
                    fputcsv($output, $section['headers']);
                    foreach ($section['rows'] as $row) {
                        fputcsv($output, $row);
                    }
                    fputcsv($output, []);
                }

                fclose($output);
            }, "{$fileName}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        $pdf = Pdf::loadView('pdf.reports.generic', $payload)
            ->setPaper('a4', $report === 'all' ? 'portrait' : 'portrait');

        return $pdf->stream("{$fileName}.pdf");
    }

    private function financialPayload(Request $request): array
    {
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        $summary = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->selectRaw("
                SUM(CASE WHEN type = 'إيراد' THEN total_amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'مصروف' THEN total_amount ELSE 0 END) as total_expense
            ")->first();

        $dailyBreakdown = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->selectRaw("
                DATE(transaction_date) as date,
                SUM(CASE WHEN type = 'إيراد' THEN total_amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'مصروف' THEN total_amount ELSE 0 END) as expense
            ")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($day) => [
                'date' => $day->date,
                'income' => (float) $day->income,
                'expense' => (float) $day->expense,
                'net' => (float) $day->income - (float) $day->expense,
            ]);

        $totalIncome = (float) ($summary->total_income ?? 0);
        $totalExpense = (float) ($summary->total_expense ?? 0);

        return [
            'month' => $month,
            'year' => $year,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $totalIncome - $totalExpense,
            'daily_breakdown' => $dailyBreakdown,
        ];
    }

    private function patientStatsPayload(): array
    {
        return [
            'total_patients' => Patient::count(),
            'new_today' => Patient::whereDate('created_at', today())->count(),
            'new_this_week' => Patient::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_this_month' => Patient::where('created_at', '>=', now()->startOfMonth())->count(),
        ];
    }

    private function doctorStatsPayload(): array
    {
        return [
            'visits_today' => Visit::whereDate('visit_date', today())->count(),
            'completed_today' => Visit::whereDate('visit_date', today())->where('status', 'مكتمل')->count(),
            'lab_requests_today' => LabRequest::whereDate('request_date', today())->count(),
            'prescriptions_today' => Prescription::whereDate('prescription_date', today())->count(),
        ];
    }

    private function patientsPayload(): array
    {
        return [
            'by_gender' => Patient::select('gender', DB::raw('COUNT(*) as count'))
                ->groupBy('gender')
                ->orderBy('gender')
                ->get(),
            'by_blood_type' => Patient::select('blood_type', DB::raw('COUNT(*) as count'))
                ->whereNotNull('blood_type')
                ->groupBy('blood_type')
                ->orderBy('blood_type')
                ->get(),
        ];
    }

    private function visitsPayload(): array
    {
        return [
            'today' => Visit::whereDate('visit_date', today())->count(),
            'this_month' => Visit::where('visit_date', '>=', now()->startOfMonth())->count(),
            'by_status' => Visit::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->orderBy('status')
                ->get(),
        ];
    }

    private function exportPayload(string $report, Request $request): array
    {
        $sections = match ($report) {
            'financial' => $this->financialSections($request),
            'patient-stats' => $this->patientStatsSections(),
            'doctor-stats' => $this->doctorStatsSections(),
            'patients' => $this->patientsSections(),
            'visits' => $this->visitsSections(),
            'all' => array_merge(
                $this->financialSections($request),
                $this->patientStatsSections(),
                $this->patientsSections(),
                $this->doctorStatsSections(),
                $this->visitsSections(),
            ),
        };

        return [
            'title' => $this->reportTitle($report),
            'subtitle' => 'تاريخ الإصدار: ' . now()->format('Y-m-d H:i'),
            'file_name' => 'nawras-report-' . $report,
            'sections' => $sections,
        ];
    }

    private function financialSections(Request $request): array
    {
        $data = $this->financialPayload($request);

        return [
            [
                'title' => 'ملخص التقرير المالي',
                'headers' => ['البند', 'القيمة'],
                'rows' => [
                    ['الشهر', $data['month']],
                    ['السنة', $data['year']],
                    ['إجمالي الإيرادات', number_format($data['total_income'], 2)],
                    ['إجمالي المصروفات', number_format($data['total_expense'], 2)],
                    ['الصافي', number_format($data['net_profit'], 2)],
                ],
            ],
            [
                'title' => 'تفصيل الأيام',
                'headers' => ['التاريخ', 'الإيراد', 'المصروف', 'الصافي'],
                'rows' => collect($data['daily_breakdown'])->map(fn ($day) => [
                    $day['date'],
                    number_format((float) $day['income'], 2),
                    number_format((float) $day['expense'], 2),
                    number_format((float) $day['net'], 2),
                ])->values()->all(),
            ],
        ];
    }

    private function patientStatsSections(): array
    {
        $data = $this->patientStatsPayload();

        return [[
            'title' => 'إحصائيات المرضى',
            'headers' => ['البند', 'القيمة'],
            'rows' => [
                ['إجمالي المرضى', $data['total_patients']],
                ['جدد اليوم', $data['new_today']],
                ['جدد هذا الأسبوع', $data['new_this_week']],
                ['جدد هذا الشهر', $data['new_this_month']],
            ],
        ]];
    }

    private function doctorStatsSections(): array
    {
        $data = $this->doctorStatsPayload();

        return [[
            'title' => 'إحصائيات الطبيب اليومية',
            'headers' => ['البند', 'القيمة'],
            'rows' => [
                ['زيارات اليوم', $data['visits_today']],
                ['زيارات مكتملة اليوم', $data['completed_today']],
                ['طلبات فحوصات اليوم', $data['lab_requests_today']],
                ['وصفات اليوم', $data['prescriptions_today']],
            ],
        ]];
    }

    private function patientsSections(): array
    {
        $data = $this->patientsPayload();

        return [
            [
                'title' => 'المرضى حسب الجنس',
                'headers' => ['الجنس', 'العدد'],
                'rows' => collect($data['by_gender'])->map(fn ($row) => [
                    $row->gender ?: 'غير محدد',
                    $row->count,
                ])->values()->all(),
            ],
            [
                'title' => 'المرضى حسب فصيلة الدم',
                'headers' => ['فصيلة الدم', 'العدد'],
                'rows' => collect($data['by_blood_type'])->map(fn ($row) => [
                    $row->blood_type ?: 'غير محدد',
                    $row->count,
                ])->values()->all(),
            ],
        ];
    }

    private function visitsSections(): array
    {
        $data = $this->visitsPayload();

        return [
            [
                'title' => 'ملخص الزيارات',
                'headers' => ['البند', 'القيمة'],
                'rows' => [
                    ['زيارات اليوم', $data['today']],
                    ['زيارات هذا الشهر', $data['this_month']],
                ],
            ],
            [
                'title' => 'الزيارات حسب الحالة',
                'headers' => ['الحالة', 'العدد'],
                'rows' => collect($data['by_status'])->map(fn ($row) => [
                    $row->status ?: 'غير محدد',
                    $row->count,
                ])->values()->all(),
            ],
        ];
    }

    private function reportTitle(string $report): string
    {
        return match ($report) {
            'financial' => 'التقرير المالي',
            'patient-stats' => 'تقرير إحصائيات المرضى',
            'doctor-stats' => 'تقرير إحصائيات الطبيب',
            'patients' => 'تقرير تصنيف المرضى',
            'visits' => 'تقرير الزيارات',
            'all' => 'التقرير الشامل',
        };
    }
}
