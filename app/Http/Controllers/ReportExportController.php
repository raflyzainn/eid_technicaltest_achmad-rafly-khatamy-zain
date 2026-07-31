<?php

namespace App\Http\Controllers;

use App\Models\ProductionLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController
{
    public function __invoke(Request $request): StreamedResponse
    {
        $date = $request->query('date', now()->format('Y-m-d'));
        $shift = $request->query('shift', '');

        $query = ProductionLog::query()
            ->whereDate('recorded_at', $date)
            ->with('machine');

        if ($shift !== '') {
            $query->where('shift', $shift);
        }

        $reports = $query->get()
            ->groupBy('machine_id')
            ->map(function ($logs) {
                $machine = $logs->first()->machine;

                return [
                    $machine->name,
                    $machine->type,
                    $logs->sum('output_count'),
                    round($logs->avg('temperature'), 2),
                    $logs->where('status', '!=', 'Running')->count(),
                    $logs->count(),
                ];
            })
            ->values();

        $filename = sprintf('production_report_%s', $date);
        if ($shift !== '') {
            $filename .= sprintf('_shift_%s', $shift);
        }
        $filename .= '.csv';

        return response()->streamDownload(function () use ($reports) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel UTF-8

            fputcsv($handle, ['Machine Name', 'Type', 'Total Output', 'Avg Temp (°C)', 'Downtime Events', 'Total Logs']);

            foreach ($reports as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
