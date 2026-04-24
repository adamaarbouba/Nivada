@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#EAD3CD] mb-6">Booking Details</h2>

                    <form method="POST" action="{{ route('guest.bookings.store', $room) }}" class="space-y-6">
                        @csrf

                        <!-- Hotel and Room Info -->
                        <div class="bg-[#2A2729] border border-[#4E3B46] p-4 rounded-lg mb-6">
                            <p class="text-sm text-[#CFCBCA]">Hotel</p>
                            <h3 class="text-lg font-semibold text-[#EAD3CD]">{{ $room->hotel->name }}</h3>
                            <p class="text-sm text-[#CFCBCA] mt-1">Room {{ $room->room_number }} - {{ $room->room_type }}
                            </p>
                        </div>

                        <!-- Check-in Date -->
                        <div>
                            <label for="check_in_date" class="block text-sm font-medium text-[#EAD3CD] mb-2">
                                Check-in Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" id="check_in_date" name="check_in_date" value="{{ old('check_in_date') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#A0717F] @error('check_in_date') border-red-500 @enderror">
                            @error('check_in_date')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check-out Date -->
                        <div>
                            <label for="check_out_date" class="block text-sm font-medium text-[#EAD3CD] mb-2">
                                Check-out Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" id="check_out_date" name="check_out_date"
                                value="{{ old('check_out_date') }}" min="{{ date('Y-m-d', strtotime('+2 day')) }}"
                                class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#A0717F] @error('check_out_date') border-red-500 @enderror">
                            @error('check_out_date')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label for="special_requests" class="block text-sm font-medium text-[#EAD3CD] mb-2">
                                Special Requests
                            </label>
                            <textarea id="special_requests" name="special_requests" rows="4"
                                placeholder="Any special requests or preferences? (e.g., high floor, near elevator, etc.)"
                                class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#A0717F]">{{ old('special_requests') }}</textarea>
                            <p class="text-xs text-[#CFCBCA] mt-1">Optional - max 500 characters</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-[#A0717F] hover:bg-[#8F6470] text-[#EAD3CD] px-6 py-3 rounded-lg font-semibold transition">
                                Confirm Booking
                            </button>
                            <a href="{{ route('guest.hotels.show', $room->hotel) }}"
                                class="flex-1 bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD] border border-[#4E3B46] px-6 py-3 rounded-lg font-semibold text-center transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Booking Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-8 sticky top-4">
                    <h3 class="text-xl font-bold text-[#EAD3CD] mb-6">Booking Summary</h3>

                    <div class="space-y-4 pb-6 border-b border-[#4E3B46]">
                        <div class="flex justify-between">
                            <span class="text-[#CFCBCA]">Room Type</span>
                            <span class="font-semibold text-[#EAD3CD]">{{ $room->room_type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#CFCBCA]">Room No.</span>
                            <span class="font-semibold text-[#EAD3CD]">{{ $room->room_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#CFCBCA]">Capacity</span>
                            <span class="font-semibold text-[#EAD3CD]">{{ $room->capacity }} Guests</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#CFCBCA]">Price/Night</span>
                            <span
                                class="font-semibold text-[#A0717F]">${{ number_format($room->price_per_night, 2) }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 py-6 border-b border-[#4E3B46]">
                        <div>
                            <p class="text-sm text-[#CFCBCA] mb-1">Check-in</p>
                            <p class="font-semibold text-[#EAD3CD]" id="display-checkin">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-[#CFCBCA] mb-1">Check-out</p>
                            <p class="font-semibold text-[#EAD3CD]" id="display-checkout">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-[#CFCBCA] mb-1">Number of Nights</p>
                            <p class="font-semibold text-[#EAD3CD]"><span id="nights-count">0</span> nights</p>
                        </div>
                    </div>

                    <div class="pt-6 text-center">
                        <p class="text-sm text-[#CFCBCA] mb-1">Total Cost</p>
                        <p class="text-3xl font-bold text-[#A0717F]">${{ number_format($room->price_per_night, 2) }}</p>
                        <p class="text-xs text-[#CFCBCA] mt-2" id="total-breakdown">0 nights ×
                            ${{ number_format($room->price_per_night, 2) }}/night</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        const pricePerNight = {{ $room->price_per_night }};
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');

        function updateSummary() {
            if (checkInInput.value && checkOutInput.value) {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

                if (nights > 0) {
                    const total = nights * pricePerNight;

                    document.getElementById('display-checkin').textContent = checkIn.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    document.getElementById('display-checkout').textContent = checkOut.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    document.getElementById('nights-count').textContent = nights;
                    document.querySelector('.text-3xl.font-bold').textContent = '$' + total.toFixed(2);
                    document.getElementById('total-breakdown').textContent = nights + ' nights × $' + pricePerNight.toFixed(
                        2) + '/night';
                }
            }
        }

        checkInInput.addEventListener('change', updateSummary);
        checkOutInput.addEventListener('change', updateSummary);

        // Initial update if dates are already filled
        updateSummary();
    </script>
@endsection
