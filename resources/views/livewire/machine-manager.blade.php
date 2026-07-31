<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">{{ count($machines) }} machines registered</p>
        <button wire:click="toggleForm" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium
            {{ $showForm ? 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
            {{ $showForm ? 'Cancel' : '+ Add Machine' }}
        </button>
    </div>

    @if ($showForm)
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <form wire:submit="create">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Machine Name</label>
                        <input type="text" wire:model="name" placeholder="e.g. CNC-03"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select wire:model="type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="CNC">CNC</option>
                            <option value="Milling">Milling</option>
                            <option value="Press">Press</option>
                            <option value="Assembly">Assembly</option>
                        </select>
                        @error('type') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                        Create Machine
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Operator</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($machines as $machine)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-900">{{ $machine->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $machine->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold
                                @if($machine->status === 'Running') bg-green-50 text-green-700
                                @elseif($machine->status === 'Idle') bg-yellow-50 text-yellow-700
                                @elseif($machine->status === 'Maintenance') bg-blue-50 text-blue-700
                                @else bg-red-50 text-red-700
                                @endif
                            ">
                                {{ $machine->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $machine->current_operator ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-3">
                            <button wire:click="edit({{ $machine->id }})" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                            <button wire:click="confirmDelete({{ $machine->id }})"
                                class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                            No machines found. Add your first machine above.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showEditForm)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="cancelEdit">
            <div class="bg-white rounded-lg border border-gray-200 shadow-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-5">Edit Machine</h3>
                <form wire:submit="update" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" wire:model="editName"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        @error('editName') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select wire:model="editType" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="CNC">CNC</option>
                            <option value="Milling">Milling</option>
                            <option value="Press">Press</option>
                            <option value="Assembly">Assembly</option>
                        </select>
                        @error('editType') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="editStatus" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="Running">Running</option>
                            <option value="Idle">Idle</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Error">Error</option>
                        </select>
                        @error('editStatus') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-3 justify-end pt-2">
                        <button type="button" wire:click="cancelEdit"
                            class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showDeleteModal && $deletingMachine)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="cancelDelete">
            <div class="bg-white rounded-lg border border-gray-200 shadow-xl p-6 w-full max-w-sm mx-4">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Machine</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Are you sure you want to delete <strong>{{ $deletingMachine->name }}</strong>? This action cannot be undone.
                    </p>
                    <div class="flex gap-3 justify-center">
                        <button type="button" wire:click="cancelDelete"
                            class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="button" wire:click="delete"
                            class="px-4 py-2 rounded-lg text-sm font-medium bg-red-600 text-white hover:bg-red-700">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
