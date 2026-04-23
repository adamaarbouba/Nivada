@extends('layouts.app')

@section('content')
    <x-breadcrumbs :links="[
        ['label' => 'Owner Dashboard', 'url' => route('owner.dashboard')],
        ['label' => $hotel->name, 'url' => route('owner.hotels.show', $hotel)],
        ['label' => 'Staff', 'url' => '#']
    ]" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-[#EAD3CD]">Staff — {{ $hotel->name }}</h2>
            <p class="text-[#CFCBCA] mt-1">Manage staff members assigned to this hotel</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('owner.staff.applications') }}"
                class="flex items-center gap-2 bg-[#A0717F] hover:bg-[#8F6470] text-white font-semibold px-5 py-2 rounded-lg transition text-sm shadow-md">
                <x-icon name="users" class="w-4 h-4" /> View Applications
            </a>
            <a href="{{ route('owner.hotels.show', $hotel) }}"
                class="border border-[#4E3B46] hover:bg-[#2A2729] text-[#CFCBCA] font-semibold px-5 py-2 rounded transition text-sm">
                ← Back to Hotel
            </a>
        </div>
    </div>

    <!-- Freelance Staff (Cleaners & Inspectors) -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-[#4E3B46] bg-[#2A2729]">
            <h3 class="text-lg font-semibold text-[#EAD3CD]">Freelance Staff (Cleaners & Inspectors)</h3>
        </div>

        @if ($hotel->staff->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#2A2729] border-b border-[#4E3B46]">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Name</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Email</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Role</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Hourly Rate</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-[#CFCBCA]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hotel->staff as $staff)
                            <tr class="border-b border-[#4E3B46] hover:bg-[#2A2729]/50">
                                <td class="px-6 py-4 text-sm text-[#EAD3CD] font-semibold">{{ $staff->name }}</td>
                                <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ $staff->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border bg-[#2A2729]
                                        {{ $staff->pivot->role === 'cleaner' ? 'text-blue-400 border-blue-500' : 'text-purple-400 border-purple-500' }}">
                                        {{ ucfirst($staff->pivot->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#CFCBCA]">
                                    <!-- Editable Wage -->
                                    <form action="{{ route('owner.staff.updateWage', [$hotel, $staff]) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <span class="text-[#CFCBCA]">$</span>
                                        <input type="number" name="hourly_rate" value="{{ $staff->pivot->hourly_rate }}"
                                            step="0.01" min="0.01"
                                            class="w-20 px-2 py-1 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#A0717F]">
                                        <button type="submit" class="text-xs text-[#EAD3CD] bg-[#4E3B46] hover:bg-[#68525F] px-2 py-1 rounded transition">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border bg-[#2A2729]
                                        {{ $staff->pivot->is_available ? 'text-green-400 border-green-500' : 'text-red-400 border-red-500' }}">
                                        {{ $staff->pivot->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('owner.staff.remove', [$hotel, $staff]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="NocturnalUI.confirm('Remove {{ addslashes($staff->name) }} from this hotel?', 'Remove Staff').then(c => { if(c) this.closest('form').submit(); })" class="text-[#A0717F] hover:text-red-500 text-sm font-semibold transition">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-[#CFCBCA]">No freelance staff assigned yet. Staff can apply from their dashboards.</p>
            </div>
        @endif
    </div>

    <!-- Receptionists -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-[#4E3B46] bg-[#2A2729]">
            <h3 class="text-lg font-semibold text-[#EAD3CD]">Receptionists</h3>
        </div>

        @if ($hotel->receptionists->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#2A2729] border-b border-[#4E3B46]">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Name</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Email</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Phone</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-[#CFCBCA]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hotel->receptionists as $receptionist)
                            @if ($receptionist->user && !$receptionist->user->banned_at)
                                <tr class="border-b border-[#4E3B46] hover:bg-[#2A2729]/50">
                                    <td class="px-6 py-4 text-sm text-[#EAD3CD] font-semibold">{{ $receptionist->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ $receptionist->user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ $receptionist->user->phone ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border border-blue-500 text-blue-400 bg-[#2A2729]">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('owner.staff.remove', [$hotel, $receptionist->user]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="NivadaUI.confirm('Remove {{ addslashes($receptionist->user->name) }} from this hotel?', 'Remove Staff').then(c => { if(c) this.closest('form').submit(); })" class="text-[#A0717F] hover:text-red-500 text-sm font-semibold transition">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-[#CFCBCA]">No receptionists assigned yet. Receptionists can apply from their dashboards.</p>
            </div>
        @endif
    </div>
@endsection
