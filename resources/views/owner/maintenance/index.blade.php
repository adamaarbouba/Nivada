@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <a href="{{ route('owner.dashboard') }}" class="text-[#CFCBCA] hover:text-[#EAD3CD] mb-6 inline-block">
            ← Back to Dashboard
        </a>

        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-[#2A2729] border-b border-[#4E3B46] px-8 py-6 text-white">
                <h2 class="text-3xl font-bold text-[#EAD3CD]">🔧 Maintenance Management</h2>
                <p class="mt-2 text-[#CFCBCA]">Review and manage maintenance requests from inspectors</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                @if ($maintenanceRequests->isEmpty())
                    <div class="bg-[#2A2729] border border-[#4E3B46] rounded-lg p-6 text-center">
                        <p class="text-[#EAD3CD] text-lg">No maintenance requests at this time</p>
                        <p class="text-[#CFCBCA] text-sm mt-1">All your rooms are in good condition</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($maintenanceRequests as $request)
                            <div class="bg-[#2A2729] border border-[#4E3B46] rounded-lg p-4 hover:bg-[#383537] transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-[#EAD3CD]">
                                            Room {{ $request->room->room_number }}
                                            <span class="text-sm font-normal text-[#CFCBCA]">
                                                - {{ $request->hotel->name }}
                                            </span>
                                        </h3>
                                        <p class="text-sm text-[#CFCBCA] mt-1">
                                            <strong class="text-[#EAD3CD]">Inspector:</strong> {{ $request->inspector->name }}
                                        </p>
                                        <p class="text-xs text-[#4E3B46] mt-1">
                                            Requested: {{ $request->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-sm font-semibold border bg-[#1A1515]
                                                @if ($request->priority === 'urgent') text-red-400 border-red-500
                                                @elseif ($request->priority === 'normal')
                                                    text-yellow-400 border-yellow-500
                                                @else
                                                    text-green-400 border-green-500 @endif
                                            ">
                                            {{ ucfirst($request->priority) }} Priority
                                        </span>
                                        <span
                                            class="block mt-2 px-3 py-1 rounded text-xs font-semibold border bg-[#1A1515]
                                                @if ($request->status === 'pending') text-orange-400 border-orange-500
                                                @elseif ($request->status === 'in-progress')
                                                    text-blue-400 border-blue-500
                                                @else
                                                    text-green-400 border-green-500 @endif
                                            ">
                                            {{ ucfirst(str_replace('-', ' ', $request->status)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Issue Details -->
                                <div class="bg-[#1A1515] rounded p-3 my-3">
                                    <p class="text-sm text-[#CFCBCA]">
                                        <strong class="text-[#EAD3CD]">Issue Description:</strong>
                                    </p>
                                    <p class="text-sm text-[#CFCBCA] mt-1">
                                        {{ $request->issue_description ?: 'No description provided' }}
                                    </p>
                                </div>

                                <!-- Room Info -->
                                <div class="grid grid-cols-2 gap-2 text-xs text-[#CFCBCA] mb-3">
                                    <div>
                                        <span class="font-semibold text-[#EAD3CD]">Room Type:</span>
                                        {{ $request->room->room_type }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-[#EAD3CD]">Capacity:</span>
                                        {{ $request->room->capacity }} guests
                                    </div>
                                </div>

                                @if ($request->status === 'completed' && $request->completion_notes)
                                    <div class="bg-[#1A1515] border border-green-500 rounded p-3 mb-3">
                                        <p class="text-xs text-green-400">
                                            <strong>Completion Notes:</strong>
                                        </p>
                                        <p class="text-sm text-green-300 mt-1">
                                            {{ $request->completion_notes }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Button -->
                                @if ($request->status !== 'completed')
                                    <div class="flex gap-2">
                                        <a href="{{ route('owner.maintenance.show', $request) }}"
                                            class="flex-1 bg-[#A0717F] hover:bg-[#8F6470] text-white px-4 py-2 rounded font-semibold transition text-center">
                                            Manage Room
                                        </a>
                                    </div>
                                @else
                                    <div class="bg-[#1A1515] border border-green-500 rounded p-3 text-center">
                                        <p class="text-sm text-green-400 font-semibold">
                                            ✓ Completed on {{ $request->completed_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Summary Stats -->
                <div class="grid grid-cols-3 gap-4 mt-8 pt-8 border-t border-[#4E3B46]">
                    <div class="bg-[#2A2729] border border-[#4E3B46] rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-[#EAD3CD]">
                            {{ $maintenanceRequests->where('status', 'pending')->count() }}
                        </div>
                        <p class="text-sm text-[#CFCBCA] mt-1">Pending</p>
                    </div>
                    <div class="bg-[#2A2729] border border-[#4E3B46] rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-[#EAD3CD]">
                            {{ $maintenanceRequests->where('status', 'in-progress')->count() }}
                        </div>
                        <p class="text-sm text-[#CFCBCA] mt-1">In Progress</p>
                    </div>
                    <div class="bg-[#2A2729] border border-[#4E3B46] rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-[#EAD3CD]">
                            {{ $maintenanceRequests->where('status', 'completed')->count() }}
                        </div>
                        <p class="text-sm text-[#CFCBCA] mt-1">Completed</p>
                    </div>
                </div>
            </div>
        </div>
</div>@endsection




