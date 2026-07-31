<div class="space-y-6">
    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <div class="flex flex-col sm:flex-row gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" wire:model.live="date"
                    class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                <select wire:model.live="shift" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Shifts</option>
                    <option value="1">Shift 1</option>
                    <option value="2">Shift 2</option>
                    <option value="3">Shift 3</option>
                </select>
            </div>
            <button wire:click="exportExcel" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-green-600 text-white hover:bg-green-700">
                Export Excel
            </button>
        </div>
    </div>

    @if(count($reports) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <span class="text-xs font-semibold text-gray-500 uppercase">Total Machines</span>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ count($reports) }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <span class="text-xs font-semibold text-gray-500 uppercase">Total Output</span>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format(collect($reports)->sum('total_output')) }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <span class="text-xs font-semibold text-gray-500 uppercase">Avg Temperature</span>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ round(collect($reports)->avg('avg_temperature'), 1) }}°C</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <span class="text-xs font-semibold text-gray-500 uppercase">Downtime Events</span>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ collect($reports)->sum('downtime_count') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Machine</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Output</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Avg Temp</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Downtime</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Logs</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><span class="font-semibold text-gray-900">{{ $report['machine_name'] }}</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report['machine_type'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-green-600">{{ number_format($report['total_output']) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm
                            @if($report['avg_temperature'] > 60) text-red-600 font-semibold
                            @elseif($report['avg_temperature'] > 40) text-yellow-600 font-semibold
                            @else text-gray-700
                            @endif
                        ">{{ $report['avg_temperature'] }}°C</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @if($report['downtime_count'] > 0)
                                <span class="text-red-600 font-semibold">{{ $report['downtime_count'] }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">{{ $report['total_logs'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No production data for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
