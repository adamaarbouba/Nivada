@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Search and Filters Section -->
        <div class="bg-[#383537] rounded-lg shadow-lg p-8 mb-8 border border-[#4E3B46]">
            <h3 class="text-xl font-semibold text-[#EAD3CD] mb-6">Find Your Perfect Hotel</h3>

            <form method="GET" action="{{ route('guest.hotels.index') }}" class="space-y-6" id="filter-form">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-[#EAD3CD] mb-2">Search</label>
                    <input type="text" id="search" name="search" placeholder="Hotel name or city..."
                        value="{{ $search ?? '' }}"
                        class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:border-[#A0717F] transition-colors">
                </div>

                <!-- Filters Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- City Filter -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-[#EAD3CD] mb-2">City</label>
                        <select id="city" name="city"
                            class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:border-[#A0717F] transition-colors">
                            <option value="">All Cities</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" {{ ($selectedCity ?? '') === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Min Price -->
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-[#EAD3CD] mb-2">Min Price ($)</label>
                        <input type="number" id="min_price" name="min_price" placeholder="0" value="{{ $minPrice ?? '' }}"
                            min="0"
                            class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:border-[#A0717F] transition-colors">
                    </div>

                    <!-- Max Price -->
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-[#EAD3CD] mb-2">Max Price ($)</label>
                        <input type="number" id="max_price" name="max_price" placeholder="999"
                            value="{{ $maxPrice ?? '' }}" min="0"
                            class="w-full px-4 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded-lg focus:outline-none focus:border-[#A0717F] transition-colors">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit"
                        class="text-[#EAD3CD] bg-[#A0717F] hover:bg-[#8F6470] px-6 py-2 rounded-lg font-semibold transition-colors">
                        Search
                    </button>
                    <a href="{{ route('guest.hotels.index') }}"
                        class="px-6 py-2 rounded-lg font-semibold transition-colors border border-[#4E3B46] bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD]">
                        Clear Filters
                    </a>
                </div>
            </form>

            <script>
                document.getElementById('filter-form').addEventListener('submit', function(e) {
                    const formData = new FormData(this);
                    const params = new URLSearchParams();

                    // Only add non-empty parameters
                    for (let [key, value] of formData.entries()) {
                        if (value && value.trim() !== '') {
                            params.append(key, value);
                        }
                    }

                    // Redirect to the clean URL
                    if (params.toString()) {
                        window.location.href = '{{ route('guest.hotels.index') }}?' + params.toString();
                    } else {
                        window.location.href = '{{ route('guest.hotels.index') }}';
                    }
                    e.preventDefault();
                });
            </script>
        </div>

        <!-- Hotels Grid -->
        <div class="mb-8">
            @if ($hotels->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($hotels as $hotel)
                        <a href="{{ route('guest.hotels.show', $hotel) }}"
                            class="bg-[#383537] rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:scale-105 border border-[#4E3B46]">
                            <!-- Hotel Image Placeholder -->
                            <div
                                class="h-48 bg-gradient-to-r from-[#A0717F] to-[#2A2729] flex items-center justify-center group">
                                <x-icon name="home" size="2xl"
                                    class="text-[#EAD3CD]/50 group-hover:text-[#EAD3CD]/80 transition" />
                            </div>

                            <!-- Hotel Info -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-[#EAD3CD] mb-1">{{ $hotel->name }}</h3>
                                <p class="text-sm text-[#CFCBCA] mb-2 flex items-center gap-1">
                                    <x-icon name="map-pin" size="sm" class="text-[#A0717F]" />
                                    {{ $hotel->city }}
                                </p>
                                <p class="text-sm text-[#EAD3CD] mb-3 line-clamp-2">{{ $hotel->description }}</p>

                                <!-- Available Rooms -->
                                @php
                                    $availableRooms = $hotel->rooms()->where('status', 'Available')->count();
                                @endphp
                                <div class="text-xs mb-3">
                                    <span
                                        class="inline-block px-2 py-1 rounded bg-[#2A2729] text-[#A0717F] border border-[#4E3B46]">
                                        {{ $availableRooms }} rooms available
                                    </span>
                                </div>

                                <!-- Price Range -->
                                @php
                                    $priceRange = $hotel
                                        ->rooms()
                                        ->where('status', 'Available')
                                        ->selectRaw(
                                            'MIN(price_per_night) as min_price, MAX(price_per_night) as max_price',
                                        )
                                        ->first();
                                @endphp
                                @if ($priceRange && $priceRange->min_price)
                                    <div class="text-lg font-bold text-[#A0717F]">
                                        ${{ number_format($priceRange->min_price, 2) }}
                                        @if ($priceRange->min_price != $priceRange->max_price)
                                            - ${{ number_format($priceRange->max_price, 2) }}
                                        @endif
                                        <span class="text-sm font-normal text-[#CFCBCA]">/night</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($hotels->hasPages())
                    <div class="mt-8 flex justify-center">
                        <style>
                            .pagination {
                                display: flex;
                                gap: 0.25rem;
                                justify-content: center;
                                flex-wrap: wrap;
                            }

                            .pagination li {
                                list-style: none;
                            }

                            .pagination a,
                            .pagination span {
                                display: inline-block;
                                padding: 0.5rem 0.75rem;
                                font-size: 0.875rem;
                                border: 1px solid #4E3B46;
                                border-radius: 0.5rem;
                                transition: all 0.2s;
                                color: #EAD3CD;
                                text-decoration: none;
                                background-color: #383537;
                            }

                            .pagination a:hover {
                                background-color: #A0717F;
                                color: #EAD3CD;
                                border-color: #A0717F;
                            }

                            .pagination .active span {
                                background-color: #A0717F;
                                color: #EAD3CD;
                                border-color: #A0717F;
                            }

                            .pagination .disabled span {
                                opacity: 0.5;
                                cursor: not-allowed;
                                background-color: #2A2729;
                            }
                        </style>
                        {{ $hotels->links() }}
                    </div>
                @endif
            @else
                <div class="bg-[#383537] rounded-lg shadow-lg p-8 text-center border border-[#4E3B46]">
                    <div class="group">
                        <x-icon name="search" size="xl"
                            class="mx-auto mb-3 text-[#A0717F] opacity-70 group-hover:opacity-100 transition" />
                    </div>
                    <h3 class="text-lg font-semibold text-[#EAD3CD] mb-2">No hotels found</h3>
                    <p class="text-[#CFCBCA] mb-6">Try adjusting your filters or search criteria</p>
                    <a href="{{ route('guest.hotels.index') }}"
                        class="inline-block px-6 py-2 rounded-lg font-semibold transition-colors bg-[#4E3B46] hover:bg-[#68525F] text-[#EAD3CD]">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
</div>@endsection
