@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <a href="{{ route('owner.maintenance.index') }}" class="text-[#CFCBCA] hover:text-[#EAD3CD] mb-6 inline-block">
            ← Back to Maintenance
        </a>

        <div class="grid grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="col-span-2">
                <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8">
                    <!-- Room Info Header -->
                    <div class="border-b border-[#4E3B46] pb-6 mb-6">
                        <h2 class="text-3xl font-bold text-[#EAD3CD]">
                            🔧 Room {{ $maintenanceRequest->room->room_number }} Maintenance
                        </h2>
                        <p class="text-[#CFCBCA] mt-2">
                            {{ $maintenanceRequest->hotel->name }}
                        </p>
                        <p class="text-sm text-[#4E3B46] mt-1">
                            Room Type: {{ $maintenanceRequest->room->room_type }} |
                            Capacity: {{ $maintenanceRequest->room->capacity }} guests
                        </p>
                    </div>

                    <!-- Maintenance Request Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-[#EAD3CD] mb-4">Request Details</h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-[#CFCBCA]"><strong>Inspector:</strong></p>
                                <p class="text-[#EAD3CD]">{{ $maintenanceRequest->inspector->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#CFCBCA]"><strong>Priority:</strong></p>
                                <p class="text-[#EAD3CD]">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-sm font-semibold border bg-[#2A2729]
                                            @if ($maintenanceRequest->priority === 'urgent') text-red-400 border-red-500
                                            @elseif ($maintenanceRequest->priority === 'normal')
                                                text-yellow-400 border-yellow-500
                                            @else
                                                text-green-400 border-green-500 @endif
                                        ">
                                        {{ ucfirst($maintenanceRequest->priority) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-[#CFCBCA]"><strong>Requested:</strong></p>
                                <p class="text-[#EAD3CD]">{{ $maintenanceRequest->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-[#CFCBCA]"><strong>Status:</strong></p>
                                <p class="text-[#EAD3CD]">
                                    <span
                                        class="inline-block px-3 py-1 rounded text-sm font-semibold border bg-[#2A2729]
                                            @if ($maintenanceRequest->status === 'pending') text-orange-400 border-orange-500
                                            @elseif ($maintenanceRequest->status === 'in-progress')
                                                text-blue-400 border-blue-500
                                            @else
                                                text-green-400 border-green-500 @endif
                                        ">
                                        {{ ucfirst(str_replace('-', ' ', $maintenanceRequest->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Issue Description -->
                    <div class="mb-8 bg-[#2A2729] border border-[#4E3B46] rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-[#EAD3CD] mb-2">Issue Description</h3>
                        <p class="text-[#CFCBCA]">
                            {{ $maintenanceRequest->issue_description ?: 'No description provided' }}
                        </p>
                    </div>

                    <!-- Transition Form -->
                    @if ($maintenanceRequest->status !== 'completed')
                        <div class="bg-[#2A2729] border border-[#4E3B46] rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-[#EAD3CD] mb-4">🎯 Choose Next Action</h3>
                            <p class="text-[#CFCBCA] text-sm mb-6">
                                What should happen with this room after maintenance?
                            </p>

                            <form method="POST" action="{{ route('owner.maintenance.transition', $maintenanceRequest) }}"
                                class="space-y-4">
                                @csrf

                                <!-- Action Selection -->
                                <div>
                                    <label class="block text-sm font-semibold text-[#EAD3CD] mb-3">Next Status</label>
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center p-3 border border-[#4E3B46] rounded-lg cursor-pointer hover:border-[#A0717F] hover:bg-[#383537] transition"
                                            onclick="document.getElementById('status-cleaning').classList.remove('hidden')">
                                            <input type="radio" name="next_status" value="Cleaning"
                                                class="w-4 h-4 text-[#A0717F]" required>
                                            <span class="ml-3 text-[#EAD3CD]">
                                                <strong>Send to Cleaning</strong>
                                                <span class="text-xs text-[#CFCBCA] block">Room needs professional
                                                    cleaning before guests</span>
                                            </span>
                                        </label>
                                        <label
                                            class="flex items-center p-3 border border-[#4E3B46] rounded-lg cursor-pointer hover:border-[#A0717F] hover:bg-[#383537] transition"
                                            onclick="document.getElementById('status-available').classList.remove('hidden')">
                                            <input type="radio" name="next_status" value="Available"
                                                class="w-4 h-4 text-[#A0717F]" required>
                                            <span class="ml-3 text-[#EAD3CD]">
                                                <strong>Send to Available</strong>
                                                <span class="text-xs text-[#CFCBCA] block">Maintenance complete, room
                                                    ready for guests</span>
                                            </span>
                                        </label>
                                        <label
                                            class="flex items-center p-3 border border-[#4E3B46] rounded-lg cursor-pointer hover:border-[#A0717F] hover:bg-[#383537] transition"
                                            onclick="document.getElementById('status-maintenance').classList.remove('hidden')">
                                            <input type="radio" name="next_status" value="Maintenance"
                                                class="w-4 h-4 text-[#A0717F]" required>
                                            <span class="ml-3 text-[#EAD3CD]">
                                                <strong>Keep in Maintenance</strong>
                                                <span class="text-xs text-[#CFCBCA] block">More work needed, will update
                                                    status later</span>
                                            </span>
                                        </label>
                                    </div>
                                    @error('next_status')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Completion Notes -->
                                <div>
                                    <label for="completion_notes" class="block text-sm font-semibold text-[#EAD3CD] mb-2">
                                        Completion Notes (Optional)
                                    </label>
                                    <p class="text-xs text-[#CFCBCA] mb-2">
                                        Document what was done or next steps
                                    </p>
                                    <textarea id="completion_notes" name="completion_notes" rows="3"
                                        placeholder="e.g., Maintenance completed. AC unit serviced and working. Ready for guests."
                                        class="w-full px-4 py-2 bg-[#383537] border border-[#4E3B46] rounded-lg text-[#EAD3CD] focus:outline-none focus:border-[#A0717F]"></textarea>
                                    @error('completion_notes')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4 pt-4">
                                    <button type="submit"
                                        class="flex-1 bg-[#A0717F] hover:bg-[#8F6470] text-white font-semibold px-6 py-3 rounded-lg transition">
                                        ✓ Update Room Status
                                    </button>
                                    <a href="{{ route('owner.maintenance.index') }}"
                                        class="flex-1 border border-[#4E3B46] hover:bg-[#2A2729] text-[#CFCBCA] font-semibold px-6 py-3 rounded-lg transition text-center">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Completion Info -->
                        <div class="bg-[#1A1515] border border-green-500 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-400 mb-3">✓ Maintenance Completed</h3>
                            <div class="space-y-2 text-[#CFCBCA]">
                                <p><strong class="text-[#EAD3CD]">Completed At:</strong>
                                    {{ $maintenanceRequest->completed_at->format('M d, Y H:i') }}</p>
                                <p><strong class="text-[#EAD3CD]">Room Status:</strong> {{ $maintenanceRequest->room->status }}</p>
                                @if ($maintenanceRequest->completion_notes)
                                    <p><strong class="text-[#EAD3CD]">Notes:</strong> {{ $maintenanceRequest->completion_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-span-1">
                <!-- Quick Info Card -->
                <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-6 sticky top-4">
                    <h3 class="font-semibold text-[#EAD3CD] mb-4">Room Status Summary</h3>

                    <div class="space-y-3 text-sm">
                        <div class="pb-3 border-b border-[#4E3B46]">
                            <p class="text-[#CFCBCA] font-semibold">Current Status</p>
                            <p class="text-[#EAD3CD] mt-1">
                                <span
                                    class="inline-block px-2 py-1 rounded text-xs font-semibold
                                        @if ($maintenanceRequest->room->status === 'Maintenance') bg-[#1A1515] text-orange-400 border border-orange-500 @endif
                                    ">
                                    {{ $maintenanceRequest->room->status }}
                                </span>
                            </p>
                        </div>

                        <div class="pb-3 border-b border-[#4E3B46]">
                            <p class="text-[#CFCBCA] font-semibold">Priority Level</p>
                            <p class="text-[#EAD3CD] mt-1 capitalize">{{ $maintenanceRequest->priority }}</p>
                        </div>

                        <div class="pb-3 border-b border-[#4E3B46]">
                            <p class="text-[#CFCBCA] font-semibold">Request Age</p>
                            <p class="text-[#EAD3CD] mt-1">{{ $maintenanceRequest->created_at->diffForHumans() }}</p>
                        </div>

                        <div>
                            <p class="text-[#CFCBCA] font-semibold mb-2">Quick Actions</p>
                            <a href="{{ route('owner.maintenance.index') }}"
                                class="block bg-[#A0717F] hover:bg-[#8F6470] text-white px-3 py-2 rounded text-center transition">
                                View All Maintenance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>@endsection




