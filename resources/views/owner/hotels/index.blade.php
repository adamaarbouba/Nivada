@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-[#EAD3CD]">My Hotels</h2>
            <div class="flex gap-4">
                <a href="{{ route('owner.hotel-requests.create') }}"
                    class="bg-[#A0717F] hover:bg-[#8F6470] text-white font-semibold px-6 py-2 rounded transition">
                    Add New Hotel
                </a>
                <a href="{{ route('owner.dashboard') }}"
                    class="border border-[#4E3B46] hover:bg-[#2A2729] text-[#CFCBCA] font-semibold px-6 py-2 rounded transition">
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if ($hotels->count() > 0)
            <!-- Hotels Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($hotels as $hotel)
                    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <!-- Hotel Header -->
                        <div class="bg-[#2A2729] border-b border-[#4E3B46] p-6">
                            <h3 class="text-xl font-bold text-[#EAD3CD]">{{ $hotel->name }}</h3>
                            <p class="text-[#A0717F] text-sm mt-1">{{ $hotel->location ?? 'Location not specified' }}
                            </p>
                        </div>

                        <!-- Hotel Details -->
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-[#2A2729] border border-[#4E3B46] p-4 rounded-lg">
                                    <p class="text-[#CFCBCA] text-xs font-semibold">Total Rooms</p>
                                    <p class="text-2xl font-bold text-[#A0717F] mt-2">{{ $hotel->rooms->count() }}</p>
                                </div>
                                <div class="bg-[#2A2729] border border-[#4E3B46] p-4 rounded-lg">
                                    <p class="text-[#CFCBCA] text-xs font-semibold">Bookings</p>
                                    <p class="text-2xl font-bold text-[#EAD3CD] mt-2">{{ $hotel->bookings->count() }}
                                    </p>
                                </div>
                            </div>

                            <!-- Hotel Info -->
                            <div class="space-y-3 mb-6 text-sm border-t border-[#4E3B46] pt-4">
                                <div>
                                    <p class="text-[#CFCBCA] font-semibold">Email</p>
                                    <p class="text-[#EAD3CD]">{{ $hotel->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[#CFCBCA] font-semibold">Phone</p>
                                    <p class="text-[#EAD3CD]">{{ $hotel->phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[#CFCBCA] font-semibold">Address</p>
                                    <p class="text-[#EAD3CD]">{{ $hotel->address ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-4 border-t border-[#4E3B46] flex gap-2">
                                <a href="{{ route('owner.hotels.show', $hotel) }}"
                                    class="flex-1 text-center bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD] font-semibold py-2 rounded transition text-sm">
                                    View
                                </a>
                                <a href="{{ route('owner.hotels.manage', $hotel) }}"
                                    class="flex-1 text-center bg-[#A0717F] hover:bg-[#8F6470] text-white font-semibold py-2 rounded transition text-sm">
                                    Manage
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $hotels->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-12 text-center">
                <div class="mb-6">
                    <div class="text-6xl text-[#4E3B46] mb-4">H</div>
                </div>
                <h3 class="text-2xl font-bold text-[#EAD3CD] mb-2">No Hotels Yet</h3>
                <p class="text-[#CFCBCA] mb-8">You haven't added any hotels to your portfolio yet. Start by submitting a
                    hotel request.</p>
                <a href="{{ route('owner.hotel-requests.create') }}"
                    class="inline-block bg-[#A0717F] hover:bg-[#8F6470] text-white font-semibold px-8 py-3 rounded transition">
                    Request New Hotel
                </a>
            </div>
        @endif
</div>@endsection




