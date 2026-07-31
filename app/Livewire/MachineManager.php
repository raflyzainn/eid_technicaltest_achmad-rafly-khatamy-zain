<?php

namespace App\Livewire;

use App\Models\Machine;
use Livewire\Component;

class MachineManager extends Component
{
    public $machines;
    public $name = '';
    public $type = 'CNC';
    public $editingMachine = null;
    public $editName = '';
    public $editType = '';
    public $editStatus = '';

    public $showForm = false;
    public $showEditForm = false;

    public function mount()
    {
        $this->loadMachines();
    }

    public function loadMachines()
    {
        $this->machines = Machine::orderBy('name')->get();
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->reset(['name', 'type']);
        }
    }

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:machines,name',
            'type' => 'required|in:CNC,Milling,Press,Assembly',
        ]);

        Machine::create([
            'name' => $this->name,
            'type' => $this->type,
            'status' => 'Idle',
        ]);

        $this->reset(['name', 'type']);
        $this->showForm = false;
        $this->loadMachines();
    }

    public function edit($id)
    {
        $machine = Machine::findOrFail($id);
        $this->editingMachine = $machine->id;
        $this->editName = $machine->name;
        $this->editType = $machine->type;
        $this->editStatus = $machine->status;
        $this->showEditForm = true;
    }

    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:machines,name,' . $this->editingMachine,
            'editType' => 'required|in:CNC,Milling,Press,Assembly',
            'editStatus' => 'required|in:Running,Idle,Maintenance,Error',
        ]);

        $machine = Machine::findOrFail($this->editingMachine);
        $machine->update([
            'name' => $this->editName,
            'type' => $this->editType,
            'status' => $this->editStatus,
        ]);

        $this->cancelEdit();
        $this->loadMachines();
    }

    public function cancelEdit()
    {
        $this->editingMachine = null;
        $this->showEditForm = false;
        $this->reset(['editName', 'editType', 'editStatus']);
    }

    public function delete($id)
    {
        Machine::findOrFail($id)->delete();
        $this->loadMachines();
    }

    public function render()
    {
        return view('livewire.machine-manager');
    }
}
