<?php

namespace App\Console\Commands;

use App\Events\ProductionDataUpdated;
use App\Models\Machine;
use App\Models\ProductionLog;
use Illuminate\Console\Command;

class SimulateMachines extends Command
{
    protected $signature = 'machines:simulate {--interval=5 : Interval in seconds between data generation}';
    protected $description = 'Simulate machine production data by inserting random production logs';

    protected array $operators = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar'];

    public function handle(): void
    {
        $this->info('Starting machine simulation...');

        while (true) {
            $machines = Machine::all();

            if ($machines->isEmpty()) {
                $this->warn('No machines found. Please seed machines first.');
                break;
            }

            foreach ($machines as $machine) {
                $statuses = ['Running', 'Running', 'Running', 'Running', 'Idle', 'Maintenance', 'Error'];
                $status = $statuses[array_rand($statuses)];

                $operator = $this->operators[array_rand($this->operators)];
                $shift = (string) rand(1, 3);

                $outputCount = $status === 'Running' ? rand(10, 100) : 0;
                $temperature = match ($status) {
                    'Running' => round(rand(350, 750) / 10, 2),
                    'Idle' => round(rand(200, 350) / 10, 2),
                    'Maintenance' => round(rand(200, 300) / 10, 2),
                    'Error' => round(rand(300, 800) / 10, 2),
                };

                $log = ProductionLog::create([
                    'machine_id' => $machine->id,
                    'output_count' => $outputCount,
                    'status' => $status,
                    'temperature' => $temperature,
                    'operator' => $operator,
                    'shift' => $shift,
                    'recorded_at' => now(),
                ]);

                $machine->update([
                    'status' => $status,
                    'current_operator' => $operator,
                ]);

                ProductionDataUpdated::dispatch([
                    'machine_id' => $machine->id,
                    'machine_name' => $machine->name,
                    'status' => $status,
                    'output_count' => $outputCount,
                    'temperature' => $temperature,
                    'operator' => $operator,
                    'shift' => $shift,
                    'recorded_at' => now()->format('H:i:s'),
                ]);
            }

            $this->info('Generated data for ' . $machines->count() . ' machines. Sleeping ' . $this->option('interval') . 's...');
            sleep((int) $this->option('interval'));
        }
    }
}
