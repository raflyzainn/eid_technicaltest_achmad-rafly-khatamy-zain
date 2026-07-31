<?php

namespace App\Livewire;

use App\Models\Machine;
use App\Models\ProductionLog;
use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{
    public $machines;

    public function mount()
    {
        $this->loadMachines();
    }

    #[On('echo:machines,.ProductionDataUpdated')]
    public function handleProductionDataUpdated($payload): void
    {
        $this->loadMachines();
    }

    public function loadMachines()
    {
        $this->machines = Machine::with(['productionLogs' => function ($query) {
            $query->latest('recorded_at')->limit(1);
        }])->orderBy('name')->get()->map(function ($machine) {
            $latestLog = $machine->productionLogs->first();
            return [
                'id' => $machine->id,
                'name' => $machine->name,
                'type' => $machine->type,
                'status' => $machine->status,
                'current_operator' => $machine->current_operator,
                'output_count' => $latestLog?->output_count ?? 0,
                'temperature' => $latestLog?->temperature ?? 0,
                'shift' => $latestLog?->shift ?? '-',
                'recorded_at' => $latestLog?->recorded_at?->format('H:i:s') ?? '-',
            ];
        });
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
