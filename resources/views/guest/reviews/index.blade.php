@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-[#EAD3CD]">My Reviews</h2>
            <a href="{{ route('guest.dashboard') }}"
                class="bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD] border border-[#4E3B46] px-4 py-2 rounded transition">
                ← Back to Dashboard
            </a>
        </div>

        @if ($reviews->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach ($reviews as $review)
                    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                        <!-- Header with Hotel Name and Rating -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-[#EAD3CD]">{{ $review->hotel->name }}</h3>
                                @if ($review->room)
                                    <p class="text-sm text-[#CFCBCA]">Room {{ $review->room->room_number }}
                                        ({{ $review->room->room_type }})
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-1 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <span class="text-2xl">⭐</span>
                                        @else
                                            <span class="text-2xl text-[#4E3B46]">☆</span>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-sm text-[#CFCBCA]">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Review Comment -->
                        <p class="text-[#EAD3CD] mb-6 leading-relaxed">{{ $review->comment }}</p>

                        <!-- Category Ratings -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-[#4E3B46]">
                            <div class="text-center">
                                <p class="text-sm font-semibold text-[#CFCBCA] mb-2">Cleanliness</p>
                                <div class="flex justify-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->cleanliness_rating)
                                            <span class="text-lg">⭐</span>
                                        @else
                                            <span class="text-lg text-[#4E3B46]">☆</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-[#CFCBCA] mb-2">Service</p>
                                <div class="flex justify-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->service_rating)
                                            <span class="text-lg">⭐</span>
                                        @else
                                            <span class="text-lg text-[#4E3B46]">☆</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-[#CFCBCA] mb-2">Amenities</p>
                                <div class="flex justify-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->amenity_rating)
                                            <span class="text-lg">⭐</span>
                                        @else
                                            <span class="text-lg text-[#4E3B46]">☆</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-[#CFCBCA] mb-2">Booking</p>
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-[#2A2729] text-[#A0717F] border border-[#4E3B46]">
                                    #{{ $review->booking_id }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 pt-4 border-t border-[#4E3B46]">
                            <a href="{{ route('guest.reviews.show', $review) }}"
                                class="text-[#A0717F] hover:text-[#EAD3CD] text-sm font-semibold">
                                View Full Review →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($reviews->hasPages())
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg p-12 text-center">
                <p class="text-[#CFCBCA] text-lg mb-6">You haven't written any reviews yet.</p>
                <a href="{{ route('guest.bookings.index') }}"
                    class="inline-block bg-[#A0717F] hover:bg-[#8F6470] text-[#EAD3CD] px-6 py-2 rounded transition">
                    View Completed Bookings
                </a>
            </div>
        @endif

</div>@endsection
