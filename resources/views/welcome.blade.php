@extends('layouts.guest')

@section('content')


    <section class="relative overflow-hidden" style="background-color: #4E3B46;">

        <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl pointer-events-none"
            style="background: rgba(160, 113, 127, 0.06);"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full blur-3xl pointer-events-none"
            style="background: rgba(234, 211, 205, 0.04);"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full blur-3xl pointer-events-none"
            style="background: rgba(160, 113, 127, 0.03);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 relative z-10">


            <div class="rounded-xl shadow-2xl p-4 lg:p-5 mt-8 lg:mt-12"
                style="background-color: #383537; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);">
                <form action="{{ route('guest.hotels.index') }}" method="GET" id="welcome-search-form">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                        <div class="space-y-2">
                            <label class="text-xs font-medium uppercase"
                                style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">Destination</label>
                            <div class="flex items-center gap-2 rounded-lg px-4 py-3"
                                style="background-color: #4E3B46; border: 1px solid rgba(160, 113, 127, 0.2);">
                                <svg class="w-4 h-4 shrink-0" style="color: #A0717F;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="text" name="city" placeholder="Explore All Ateliers"
                                    class="bg-transparent text-sm w-full outline-none" style="color: #EAD3CD;">
                            </div>
                        </div>


                        <div class="space-y-2">
                            <label class="text-xs font-medium uppercase"
                                style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">Min Price</label>
                            <div class="flex items-center gap-2 rounded-lg px-4 py-3"
                                style="background-color: #4E3B46; border: 1px solid rgba(160, 113, 127, 0.2);">
                                <svg class="w-4 h-4 shrink-0" style="color: #A0717F;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <input type="number" name="min_price" placeholder="$0" min="0"
                                    class="bg-transparent text-sm w-full outline-none" style="color: #EAD3CD;">
                            </div>
                        </div>


                        <div class="space-y-2">
                            <label class="text-xs font-medium uppercase"
                                style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">Max Price</label>
                            <div class="flex items-center gap-2 rounded-lg px-4 py-3"
                                style="background-color: #4E3B46; border: 1px solid rgba(160, 113, 127, 0.2);">
                                <svg class="w-4 h-4 shrink-0" style="color: #A0717F;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <input type="number" name="max_price" placeholder="$999" min="0"
                                    class="bg-transparent text-sm w-full outline-none" style="color: #EAD3CD;">
                            </div>
                        </div>


                        <button type="submit"
                            class="font-semibold uppercase rounded-lg px-6 py-3.5 text-sm text-white flex items-center justify-center gap-2 transition-all duration-300"
                            style="background-color: #A0717F; letter-spacing: 0.1em;"
                            onmouseover="this.style.backgroundColor='#b58290'; this.style.boxShadow='0 8px 25px rgba(160,113,127,0.3)';"
                            onmouseout="this.style.backgroundColor='#A0717F'; this.style.boxShadow='none';">
                            Search Atelier
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </div>

                    <script>
                        document.getElementById('welcome-search-form').addEventListener('submit', function (e) {
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
                </form>
            </div>


            <div class="text-center py-24 lg:py-36 max-w-4xl mx-auto">
                <p class="text-xs font-medium uppercase mb-6" style="color: #A0717F; letter-spacing: 0.4em;">
                    An Atelier of Nocturnal Luxury
                </p>

                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold leading-tight mb-8"
                    style="color: #EAD3CD; font-family: 'Georgia', serif;">
                    Experience Elevated<br>
                    Hospitality At<br>
                    <em style="color: #A0717F;">NIVADA</em>
                </h1>

                <p class="text-base lg:text-lg leading-relaxed max-w-2xl mx-auto mb-10" style="color: #CFCBCA;">
                    Indulge in sophisticated spaces, world-class service, and unforgettable stays crafted for modern luxury
                    travelers. Where the architecture of silence meets the poetry of comfort.
                </p>

                <a href="{{ route('guest.hotels.index') }}"
                    class="inline-block text-white font-semibold uppercase rounded-full px-10 py-4 text-sm transition-all duration-300"
                    style="background-color: #A0717F; letter-spacing: 0.12em;"
                    onmouseover="this.style.backgroundColor='#b58290'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 35px rgba(160,113,127,0.25)';"
                    onmouseout="this.style.backgroundColor='#A0717F'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    Reserve Your Escape
                </a>
            </div>
        </div>
    </section>


    @if ($hotels->count() > 0)
        <section style="background-color: #383537; border-top: 1px solid rgba(234, 211, 205, 0.1);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 py-20 lg:py-28">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
                    <div>
                        <p class="text-xs font-medium uppercase mb-3" style="color: #A0717F; letter-spacing: 0.4em;">
                            Curated Destinations</p>
                        <h2 class="text-3xl lg:text-5xl font-bold leading-tight"
                            style="color: #EAD3CD; font-family: 'Georgia', serif;">
                            Portfolios of Rare<br>Splendor
                        </h2>
                    </div>
                    <a href="{{ route('guest.hotels.index') }}"
                        class="text-sm font-medium uppercase flex items-center gap-2 shrink-0 transition-colors duration-300 group"
                        style="color: rgba(207, 203, 202, 0.7); letter-spacing: 0.12em;"
                        onmouseover="this.style.color='#EAD3CD';" onmouseout="this.style.color='rgba(207, 203, 202, 0.7)';">
                        View Entire Collection
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($hotels as $hotel)
                        @php
                            $avgRating = $hotel->reviews->avg('rating') ?? 0;
                            $reviewCount = $hotel->reviews->count();
                            $roomCount = $hotel->rooms->count();
                            $minPrice = $hotel->rooms->min('price_per_night');
                            $maxPrice = $hotel->rooms->max('price_per_night');
                        @endphp

                        <div class="rounded-2xl overflow-hidden shadow-lg transition-all duration-500 cursor-pointer group"
                            style="background-color: #4E3B46;"
                            onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 25px 50px rgba(160,113,127,0.15)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.1)';">


                            <div class="relative overflow-hidden" style="height: 240px; background-color: #2E2530;">
                                <div class="absolute inset-0"
                                    style="background: linear-gradient(to top, #4E3B46, transparent 60%); opacity: 0.7;">
                                </div>
                                <div class="absolute inset-0"
                                    style="background: linear-gradient(135deg, rgba(160,113,127,0.08), transparent, rgba(234,211,205,0.05));">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-16 h-16" style="color: rgba(160, 113, 127, 0.2);" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>


                                <div class="absolute top-4 left-4 rounded-full px-3 py-1.5"
                                    style="background: rgba(56, 53, 55, 0.9); backdrop-filter: blur(8px);">
                                    <span class="text-xs font-medium" style="color: #EAD3CD;">
                                        {{ $hotel->city }}{{ $hotel->country ? ', ' . $hotel->country : '' }}
                                    </span>
                                </div>


                                @if ($hotel->occupation_rate > 0)
                                    <div class="absolute top-4 right-4 rounded-full px-3 py-1.5"
                                        style="background: rgba(160, 113, 127, 0.9); backdrop-filter: blur(8px);">
                                        <span class="text-xs font-semibold text-white">{{ round($hotel->occupation_rate) }}%
                                            Booked</span>
                                    </div>
                                @endif
                            </div>


                            <div class="p-6 space-y-3">
                                <h3 class="text-xl font-semibold" style="color: #EAD3CD; font-family: 'Georgia', serif;">
                                    {{ $hotel->name }}
                                </h3>

                                @if ($hotel->description)
                                    <p class="text-sm leading-relaxed" style="color: rgba(207, 203, 202, 0.7);">
                                        {{ Str::limit($hotel->description, 100) }}
                                    </p>
                                @endif


                                <div class="flex items-center gap-3">
                                    @if ($reviewCount > 0)
                                        <div class="flex items-center gap-1" style="color: #A0717F;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="text-sm" style="{{ $i <= round($avgRating) ? '' : 'opacity: 0.3;' }}">★</span>
                                            @endfor
                                        </div>
                                        <span class="text-xs" style="color: rgba(207, 203, 202, 0.5);">
                                            {{ number_format($avgRating, 1) }} ({{ $reviewCount }}
                                            {{ Str::plural('review', $reviewCount) }})
                                        </span>
                                    @else
                                        <span class="text-xs" style="color: rgba(207, 203, 202, 0.5);">No reviews
                                            yet</span>
                                    @endif
                                </div>


                                <div class="flex items-center justify-between pt-3"
                                    style="border-top: 1px solid rgba(234, 211, 205, 0.1);">
                                    <div>
                                        <span class="text-xs" style="color: rgba(207, 203, 202, 0.5);">{{ $roomCount }}
                                            {{ Str::plural('room', $roomCount) }}</span>
                                        @if ($minPrice)
                                            <p class="font-bold text-xl" style="color: #A0717F;">
                                                ${{ number_format($minPrice, 0) }}
                                                @if ($maxPrice && $maxPrice != $minPrice)
                                                    <span class="text-sm font-normal" style="color: rgba(207, 203, 202, 0.5);">–
                                                        ${{ number_format($maxPrice, 0) }}</span>
                                                @endif
                                                <span class="text-sm font-normal" style="color: rgba(207, 203, 202, 0.5);"> /
                                                    night</span>
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('guest.hotels.show', $hotel) }}"
                                        class="text-xs font-semibold uppercase rounded-lg px-4 py-2 transition-all duration-300"
                                        style="color: #A0717F; border: 1px solid #A0717F; letter-spacing: 0.08em;"
                                        onmouseover="this.style.backgroundColor='#A0717F'; this.style.color='#FFFFFF';"
                                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#A0717F';">
                                        View Hotel
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    @php
        $allReviews = $hotels
            ->flatMap(function ($hotel) {
                return $hotel->reviews->map(function ($review) use ($hotel) {
                    $review->hotel_name = $hotel->name;
                    return $review;
                });
            })
            ->sortByDesc('created_at')
            ->take(3);
    @endphp

    <section style="background-color: #4E3B46; border-top: 1px solid rgba(234, 211, 205, 0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 py-20 lg:py-28">

            <div class="mb-14">
                <p class="text-xs font-medium uppercase mb-3" style="color: #A0717F; letter-spacing: 0.4em;">The Guest
                    Experience</p>
                <h2 class="text-3xl lg:text-5xl font-bold" style="color: #EAD3CD; font-family: 'Georgia', serif;">
                    Voices of the<br>Nocturnal Atelier
                </h2>
            </div>


            @if ($allReviews->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach ($allReviews as $index => $review)
                        @if ($index === 0)

                            <div class="lg:row-span-2 rounded-xl overflow-hidden transition-all duration-500"
                                style="background-color: #383537;"
                                onmouseover="this.style.boxShadow='0 20px 40px rgba(160,113,127,0.1)';"
                                onmouseout="this.style.boxShadow='none';">

                                <div class="relative" style="height: 320px; background-color: #2E2530;">
                                    <div class="absolute inset-0"
                                        style="background: linear-gradient(to top, #383537, rgba(56,53,55,0.2) 50%, transparent);">
                                    </div>
                                    <div class="absolute bottom-6 left-6 right-6">
                                        <div class="rounded-xl p-6"
                                            style="background: rgba(78, 59, 70, 0.9); backdrop-filter: blur(10px);">
                                            <span class="text-3xl leading-none"
                                                style="color: #A0717F; font-family: 'Georgia', serif;">❝</span>
                                            <p class="text-sm italic leading-relaxed mt-2" style="color: #CFCBCA;">
                                                {{ $review->comment ? Str::limit($review->comment, 120) : 'The finest experience I have ever had. Pure poetry.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center gap-1 text-sm mb-2" style="color: #A0717F;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span style="{{ $i <= $review->rating ? '' : 'opacity: 0.3;' }}">★</span>
                                        @endfor
                                    </div>
                                    <p class="font-semibold" style="color: #EAD3CD;">{{ $review->user->name ?? 'Guest' }}
                                    </p>
                                    <p class="text-xs uppercase mt-1" style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">
                                        {{ $review->hotel_name }}
                                    </p>
                                </div>
                            </div>
                        @else

                            <div class="rounded-xl p-6 flex flex-col justify-between transition-all duration-500"
                                style="background-color: #383537;"
                                onmouseover="this.style.boxShadow='0 20px 40px rgba(160,113,127,0.1)';"
                                onmouseout="this.style.boxShadow='none';">
                                <div>
                                    <p class="text-sm italic leading-relaxed" style="color: #CFCBCA;">
                                        "{{ $review->comment ? Str::limit($review->comment, 150) : 'Every detail feels intentional and deeply personal.' }}"
                                    </p>
                                </div>
                                <div class="mt-6">
                                    <div class="flex items-center gap-1 text-sm mb-2" style="color: #A0717F;">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span style="{{ $i <= $review->rating ? '' : 'opacity: 0.3;' }}">★</span>
                                        @endfor
                                    </div>
                                    <p class="font-semibold" style="color: #EAD3CD;">{{ $review->user->name ?? 'Guest' }}
                                    </p>
                                    <p class="text-xs uppercase mt-1" style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">
                                        {{ $review->hotel_name }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endforeach


                    @for ($fill = $allReviews->count(); $fill < 3; $fill++)
                        <div class="rounded-xl p-6 flex flex-col justify-between" style="background-color: #383537;">
                            <p class="text-sm italic leading-relaxed" style="color: rgba(207, 203, 202, 0.4);">
                                "Be the first to share your experience at one of our curated destinations."
                            </p>
                            <div class="mt-6">
                                <div class="flex items-center gap-1 text-sm mb-2" style="color: rgba(160, 113, 127, 0.3);">
                                    <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                </div>
                                <p class="font-semibold" style="color: rgba(234, 211, 205, 0.4);">Your Name Here</p>
                            </div>
                        </div>
                    @endfor
                </div>
            @else

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:row-span-2 rounded-xl overflow-hidden" style="background-color: #383537;">
                        <div class="relative" style="height: 320px; background-color: #2E2530;">
                            <div class="absolute inset-0"
                                style="background: linear-gradient(to top, #383537, rgba(56,53,55,0.2) 50%, transparent);">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="rounded-xl p-6"
                                    style="background: rgba(78, 59, 70, 0.9); backdrop-filter: blur(10px);">
                                    <span class="text-3xl leading-none"
                                        style="color: #A0717F; font-family: 'Georgia', serif;">❝</span>
                                    <p class="text-sm italic leading-relaxed mt-2" style="color: #CFCBCA;">
                                        The finest sleep I have ever had outside of my own estate. Pure poetry.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-1 text-sm mb-2" style="color: #A0717F;">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            </div>
                            <p class="font-semibold" style="color: #EAD3CD;">Julian V.</p>
                            <p class="text-xs uppercase mt-1" style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">
                                London</p>
                        </div>
                    </div>


                    <div class="rounded-xl p-6 flex flex-col justify-between" style="background-color: #383537;">
                        <p class="text-sm italic leading-relaxed" style="color: #CFCBCA;">
                            "Every detail, from the thread count to the weight of the silver, feels intentional and deeply
                            personal."
                        </p>
                        <div class="mt-6">
                            <div class="flex items-center gap-1 text-sm mb-2" style="color: #A0717F;">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            </div>
                            <p class="font-semibold" style="color: #EAD3CD;">Elena R.</p>
                            <p class="text-xs uppercase mt-1" style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">
                                Milan</p>
                        </div>
                    </div>


                    <div class="rounded-xl p-6 flex flex-col justify-between" style="background-color: #383537;">
                        <p class="text-sm italic leading-relaxed" style="color: #CFCBCA;">
                            "Redefined my standards for luxury. It is simply incomparable."
                        </p>
                        <div class="mt-6">
                            <div class="flex items-center gap-1 text-sm mb-2" style="color: #A0717F;">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                            </div>
                            <p class="font-semibold" style="color: #EAD3CD;">Arthur D.</p>
                            <p class="text-xs uppercase mt-1" style="color: rgba(207, 203, 202, 0.5); letter-spacing: 0.15em;">
                                New York</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>


    <section style="background-color: #383537; border-top: 1px solid rgba(234, 211, 205, 0.1);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-16 py-24 lg:py-32">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl lg:text-5xl font-bold leading-tight italic"
                    style="color: #EAD3CD; font-family: 'Georgia', serif;">
                    Crafted for those<br>who seek <span style="color: #A0717F;">The Unspoken.</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-16">
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold uppercase"
                        style="color: #EAD3CD; font-family: 'Georgia', serif; letter-spacing: 0.12em;">
                        Tactile Sensation
                    </h3>
                    <p class="text-sm leading-relaxed" style="color: rgba(207, 203, 202, 0.7);">
                        We prioritize materials that beg to be touched — Egyptian cotton, brushed brass, and cold Italian
                        marble beneath bare feet.
                    </p>
                </div>
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold uppercase"
                        style="color: #EAD3CD; font-family: 'Georgia', serif; letter-spacing: 0.12em;">
                        Aural Comfort
                    </h3>
                    <p class="text-sm leading-relaxed" style="color: rgba(207, 203, 202, 0.7);">
                        Every NIVADA property is acoustically engineered to ensure absolute silence, protecting your most
                        precious commodity — rest.
                    </p>
                </div>
            </div>
        </div>
    </section>

@endsection