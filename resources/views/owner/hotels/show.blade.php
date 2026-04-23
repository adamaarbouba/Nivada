@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header Card -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-bold text-[#EAD3CD] mb-1">{{ $hotel->name }}</h2>
                    <p class="text-[#CFCBCA] text-sm">Manage your hotel information, staff, and rooms</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('owner.staff.index', $hotel) }}"
                        class="bg-[#A0717F] hover:bg-[#8F6470] text-white font-semibold px-6 py-2 rounded transition text-sm whitespace-nowrap flex items-center gap-2">
                        <x-icon name="users" size="sm" class="text-white" />
                        Manage Staff
                    </a>
                    <a href="{{ route('owner.hotels.manage', $hotel) }}"
                        class="border border-[#A0717F] text-[#A0717F] hover:bg-[#A0717F] hover:text-white font-semibold px-6 py-2 rounded transition text-sm whitespace-nowrap">
                        Manage Rooms
                    </a>
                    <a href="{{ route('owner.hotels.index') }}"
                        class="border border-[#4E3B46] hover:bg-[#2A2729] text-[#CFCBCA] font-semibold px-6 py-2 rounded transition text-sm whitespace-nowrap">
                        Back to Hotels
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Hotel Form (Name + Hourly Wage only) -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-[#EAD3CD] mb-6">Edit Hotel</h3>
            <form action="{{ route('owner.hotels.update', $hotel) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="text-[#CFCBCA] font-semibold text-sm">Hotel Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $hotel->name) }}"
                            class="w-full mt-1 px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#A0717F]">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="default_hourly_wage" class="text-[#CFCBCA] font-semibold text-sm">Default Hourly Wage
                            ($)</label>
                        <input type="number" name="default_hourly_wage" id="default_hourly_wage"
                            value="{{ old('default_hourly_wage', $hotel->default_hourly_wage) }}" step="0.01"
                            min="0"
                            class="w-full mt-1 px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#A0717F]"
                            placeholder="e.g. 15.00">
                        <p class="text-[#4E3B46] text-xs mt-1">Staff will see this when browsing hotels to apply</p>
                        @error('default_hourly_wage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <button type="submit"
                    class="bg-[#2A2729] hover:bg-[#4E3B46] border border-[#4E3B46] text-[#EAD3CD] font-semibold px-6 py-2 rounded transition text-sm">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Hotel Information Card -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-[#EAD3CD] mb-6">Hotel Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Location</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->city }}, {{ $hotel->country }}</p>
                </div>
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Email</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Phone</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Status</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-[#A0717F] border border-[#A0717F] bg-[#2A2729]">
                            {{ $hotel->is_verified ? 'Verified' : 'Pending' }}
                        </span>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-[#CFCBCA] font-semibold text-sm">Address</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->address ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-[#CFCBCA] font-semibold text-sm">Description</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->description ?? 'No description provided' }}</p>
                </div>
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Total Rooms</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->rooms->count() }}</p>
                </div>
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Rating</p>
                    <p class="text-[#EAD3CD] text-lg mt-1">{{ $hotel->rating ?? 'N/A' }}/5.0</p>
                </div>
                <div>
                    <p class="text-[#CFCBCA] font-semibold text-sm">Default Hourly Wage</p>
                    <p class="text-lg mt-1 font-semibold text-[#A0717F]">
                        {{ $hotel->default_hourly_wage ? '$' . number_format($hotel->default_hourly_wage, 2) . '/hr' : 'Not set' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Staff Section -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-[#EAD3CD]">Hotel Staff</h3>
                <a href="{{ route('owner.staff.index', $hotel) }}"
                    class="text-sm font-semibold hover:underline text-[#A0717F]">
                    Full Staff Management →
                </a>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hotel->staff as $staff)
                                @if (!$staff->banned_at)
                                    <tr class="border-b border-[#4E3B46] hover:bg-[#2A2729]/50">
                                        <td class="px-6 py-4 text-sm text-[#EAD3CD] font-semibold">{{ $staff->name }}</td>
                                        <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ $staff->email }}</td>
                                        <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ ucfirst($staff->pivot->role) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-[#A0717F] font-semibold">
                                            ${{ number_format($staff->pivot->hourly_rate, 2) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold border bg-[#2A2729]
                                                @if ($staff->pivot->is_available) text-green-400 border-green-500 @else text-red-400 border-red-500 @endif">
                                                {{ $staff->pivot->is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-[#CFCBCA] text-center py-8">No staff assigned to this hotel yet.</p>
            @endif
        </div>

        <!-- Receptionists Section -->
        <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8">
            <h3 class="text-2xl font-bold text-[#EAD3CD] mb-6">Receptionists</h3>

            @if ($hotel->receptionists->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-[#2A2729] border-b border-[#4E3B46]">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Name</th>
                                <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Email</th>
                                <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Phone</th>
                                <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hotel->receptionists as $receptionist)
                                @if ($receptionist->user && !$receptionist->user->banned_at)
                                    <tr class="border-b border-[#4E3B46] hover:bg-[#2A2729]/50">
                                        <td class="px-6 py-4 text-sm text-[#EAD3CD] font-semibold">
                                            {{ $receptionist->user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ $receptionist->user->email }}</td>
                                        <td class="px-6 py-4 text-sm text-[#CFCBCA]">
                                            {{ $receptionist->user->phone ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-blue-400 border border-blue-500 bg-[#2A2729]">
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-[#CFCBCA] text-center py-8">No receptionists assigned to this hotel yet.</p>
            @endif
        </div>
    </div>
@endsection
