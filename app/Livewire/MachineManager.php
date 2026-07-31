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
    public $deletingMachine = null;

    public $showForm = false;
    public $showEditForm = false;
    public $showDeleteModal = false;

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

        $machine = Machine::create([
            'name' => $this->name,
            'type' => $this->type,
            'status' => 'Idle',
        ]);

        $this->reset(['name', 'type']);
        $this->showForm = false;
        $this->loadMachines();

        $this->dispatch('snackbar', message: "Machine {$machine->name} created successfully.", type: 'success');
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

        $this->dispatch('snackbar', message: "Machine {$machine->name} updated.", type: 'success');
    }

    public function cancelEdit()
    {
        $this->editingMachine = null;
        $this->showEditForm = false;
        $this->reset(['editName', 'editType', 'editStatus']);
    }

    public function confirmDelete($id)
    {
        $this->deletingMachine = Machine::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->deletingMachine) {
            $name = $this->deletingMachine->name;
            $this->deletingMachine->delete();
            $this->deletingMachine = null;
            $this->showDeleteModal = false;
            $this->loadMachines();

            $this->dispatch('snackbar', message: "Machine {$name} deleted.", type: 'success');
        }
    }

    public function cancelDelete()
    {
        $this->deletingMachine = null;
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.machine-manager');
    }
}
