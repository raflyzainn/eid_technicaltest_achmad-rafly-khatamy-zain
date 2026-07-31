<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        $machines = [
            ['name' => 'CNC-01', 'type' => 'CNC'],
            ['name' => 'CNC-02', 'type' => 'CNC'],
            ['name' => 'Milling-01', 'type' => 'Milling'],
            ['name' => 'Milling-02', 'type' => 'Milling'],
            ['name' => 'Press-01', 'type' => 'Press'],
            ['name' => 'Press-02', 'type' => 'Press'],
            ['name' => 'Assembly-01', 'type' => 'Assembly'],
            ['name' => 'Assembly-02', 'type' => 'Assembly'],
        ];

        foreach ($machines as $machine) {
            Machine::create([
                'name' => $machine['name'],
                'type' => $machine['type'],
                'status' => 'Idle',
            ]);
        }
    }
}
