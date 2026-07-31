<?php

namespace App\Livewire;

use App\Models\ProductionLog;
use Livewire\Component;

class Report extends Component
{
    public $date;
    public $shift = '';
    public $reports;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->loadReport();
    }

    public function loadReport()
    {
        $query = ProductionLog::query()
            ->whereDate('recorded_at', $this->date)
            ->with('machine');

        if ($this->shift !== '') {
            $query->where('shift', $this->shift);
        }

        $this->reports = $query->get()
            ->groupBy('machine_id')
            ->map(function ($logs) {
                $machine = $logs->first()->machine;
                $totalOutput = $logs->sum('output_count');
                $avgTemperature = $logs->avg('temperature');
                $downtimeCount = $logs->where('status', '!=', 'Running')->count();

                return [
                    'machine_name' => $machine->name,
                    'machine_type' => $machine->type,
                    'total_output' => $totalOutput,
                    'avg_temperature' => round($avgTemperature, 2),
                    'downtime_count' => $downtimeCount,
                    'total_logs' => $logs->count(),
                ];
            })
            ->values();
    }

    public function updated($property)
    {
        if (in_array($property, ['date', 'shift'])) {
            $this->loadReport();
        }
    }

    public function exportExcel()
    {
        return redirect()->route('reports.export', [
            'date' => $this->date,
            'shift' => $this->shift,
        ]);
    }

    public function render()
    {
        return view('livewire.report');
    }
}
