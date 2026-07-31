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
                    'machine_name' => $machine->name,
                    'machine_type' => $machine->type,
                    'total_output' => $logs->sum('output_count'),
                    'avg_temperature' => round($logs->avg('temperature'), 2),
                    'downtime_count' => $logs->where('status', '!=', 'Running')->count(),
                    'total_logs' => $logs->count(),
                ];
            })
            ->values();

        $filename = sprintf('production_report_%s', $date);
        if ($shift !== '') {
            $filename .= sprintf('_shift_%s', $shift);
        }
        $filename .= '.xls';

        return response()->streamDownload(function () use ($reports, $date, $shift) {
            $title = 'Production Report - ' . $date;
            if ($shift !== '') {
                $title .= ' (Shift ' . $shift . ')';
            }

            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<?mso-application progid="Excel.Sheet"?>';
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
            echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
            echo '<Worksheet ss:Name="Report">';
            echo '<Table>';

            echo '<Row ss:StyleID="header">';
            foreach (['Machine Name', 'Type', 'Total Output', 'Avg Temp (°C)', 'Downtime Events', 'Total Logs'] as $header) {
                echo '<Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>';
            }
            echo '</Row>';

            foreach ($reports as $row) {
                echo '<Row>';
                echo '<Cell><Data ss:Type="String">' . htmlspecialchars($row['machine_name']) . '</Data></Cell>';
                echo '<Cell><Data ss:Type="String">' . htmlspecialchars($row['machine_type']) . '</Data></Cell>';
                echo '<Cell><Data ss:Type="Number">' . $row['total_output'] . '</Data></Cell>';
                echo '<Cell><Data ss:Type="Number">' . $row['avg_temperature'] . '</Data></Cell>';
                echo '<Cell><Data ss:Type="Number">' . $row['downtime_count'] . '</Data></Cell>';
                echo '<Cell><Data ss:Type="Number">' . $row['total_logs'] . '</Data></Cell>';
                echo '</Row>';
            }

            echo '</Table>';
            echo '</Worksheet>';
            echo '</Workbook>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }
}
