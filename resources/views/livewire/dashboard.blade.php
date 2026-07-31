<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Machine Status</h3>
            <p class="text-sm text-gray-500">Real-time monitoring via WebSocket</p>
        </div>
        <div class="flex items-center gap-1.5 text-sm text-gray-500">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            LIVE
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($machines as $machine)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200
                @if($machine['status'] === 'Running') border-l-4 border-l-green-500
                @elseif($machine['status'] === 'Idle') border-l-4 border-l-yellow-500
                @elseif($machine['status'] === 'Maintenance') border-l-4 border-l-blue-500
                @else border-l-4 border-l-red-500
                @endif
            ">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $machine['name'] }}</h3>
                            <span class="text-xs text-gray-500">{{ $machine['type'] }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold
                            @if($machine['status'] === 'Running') bg-green-50 text-green-700
                            @elseif($machine['status'] === 'Idle') bg-yellow-50 text-yellow-700
                            @elseif($machine['status'] === 'Maintenance') bg-blue-50 text-blue-700
                            @else bg-red-50 text-red-700
                            @endif
                        ">
                            {{ $machine['status'] }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <div>
                            <span class="text-xs text-gray-500">Output/min</span>
                            <p class="text-base font-semibold text-gray-900">{{ number_format($machine['output_count']) }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Temperature</span>
                            <p class="text-base font-semibold
                                @if($machine['temperature'] > 60) text-red-600
                                @elseif($machine['temperature'] > 40) text-yellow-600
                                @else text-gray-900
                                @endif
                            ">{{ $machine['temperature'] }}°C</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Operator</span>
                            <p class="text-sm font-medium text-gray-700">{{ $machine['current_operator'] ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Shift</span>
                            <p class="text-sm font-medium text-gray-700">Shift {{ $machine['shift'] }}</p>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                        <span>{{ $machine['recorded_at'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
