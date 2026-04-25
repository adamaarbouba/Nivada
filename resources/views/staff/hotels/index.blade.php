@extends('layouts.app')

@section('content')
    {{-- Ambient Gradients --}}
    <div class="fixed top-0 left-0 w-[500px] h-[500px] rounded-full blur-3xl pointer-events-none"
        style="background: rgba(160, 113, 127, 0.05); z-index: 0;"></div>

    <div class="container mx-auto px-4 py-8 relative z-10">
        <x-breadcrumbs :links="[
            ['label' => 'Staff Dashboard', 'url' => route('staff.dashboard')],
            ['label' => 'Browse Jobs', 'url' => '#']
        ]" />

        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-[rgba(234,211,205,0.05)] pb-6">
            <div>
                <h2 class="text-3xl lg:text-5xl font-bold font-serif text-[#EAD3CD]">Available Positions</h2>
                <p class="text-xs font-medium uppercase mt-3" style="color: rgba(207, 203, 202, 0.6); letter-spacing: 0.15em;">
                    Explore hotels hiring <strong class="text-[#A0717F]">{{ $role }}s</strong> across our network
                </p>
            </div>
            <a href="{{ route('staff.my-applications') }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all duration-300 shadow-md shrink-0"
                style="background-color: #383537; color: #EAD3CD; border: 1px solid rgba(234, 211, 205, 0.1);"
                onmouseover="this.style.backgroundColor='rgba(160, 113, 127, 0.1)'; this.style.borderColor='rgba(160, 113, 127, 0.3)';"
                onmouseout="this.style.backgroundColor='#383537'; this.style.borderColor='rgba(234, 211, 205, 0.1)';">
                <x-icon name="file" class="w-4 h-4 text-[#A0717F]" />
                Track Applications
            </a>
        </div>

        @if (session('success'))
            <div class="mb-8 p-4 rounded-xl" style="background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3);">
                <p class="text-green-400 font-medium tracking-wide">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if ($isReceptionistBlocked)
            <div class="mb-8 p-5 rounded-xl border-l-[4px]" style="background-color: rgba(234, 179, 8, 0.1); border-color: rgba(234, 179, 8, 0.5);">
                <div class="flex items-start gap-3">
                    <x-icon name="alert" class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" />
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest text-[#EAD3CD] mb-1">Restricted Action</h4>
                        <p class="text-sm text-[#CFCBCA]">You are presently assigned to a hotel. Receptionist roles enforce a strict single-establishment workflow.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Hotels Grid -->
        @if ($hotels->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($hotels as $hotel)
                    @php
                        $applicationStatus = $myApplications[$hotel->id] ?? null;
                        $isWorking = in_array($hotel->id, $workingAtHotels);
                    @endphp
                    
                    <div class="rounded-2xl shadow-xl overflow-hidden bg-[#383537] border-t-[3px] transition-all duration-500 relative flex flex-col group"
                        style="border-color: rgba(160, 113, 127, 0.4);"
                        onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 25px 50px rgba(160,113,127,0.15)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px rgba(0,0,0,0.1)';">
                        
                        <div class="p-6 md:p-8 flex-grow">
                            <h3 class="text-2xl font-bold text-[#EAD3CD] font-serif mb-2 line-clamp-1 group-hover:text-[#A0717F] transition-colors">{{ $hotel->name }}</h3>
                            <p class="text-xs uppercase tracking-widest text-[#CFCBCA] mb-6 flex items-center gap-1.5 opacity-70">
                                <x-icon name="location" class="w-3.5 h-3.5" />
                                {{ $hotel->city }}, {{ $hotel->country }}
                            </p>

                            <div class="space-y-4 mb-6">
                                <div class="flex items-center justify-between pb-3 border-b border-[rgba(234,211,205,0.05)]">
                                    <span class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F]">Room Count</span>
                                    <span class="font-semibold text-[#EAD3CD]">{{ $hotel->rooms->count() }} Suites</span>
                                </div>
                                <div class="flex items-center justify-between pb-3 border-b border-[rgba(234,211,205,0.05)]">
                                    <span class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F]">Remuneration</span>
                                    <span class="font-bold font-serif tracking-widest text-[#EAD3CD]">
                                        {{ $hotel->default_hourly_wage ? '$' . number_format($hotel->default_hourly_wage, 2) . '/hr' : 'Discussed upon hire' }}
                                    </span>
                                </div>
                                @if ($hotel->rating)
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F]">Reputation</span>
                                        <span class="font-semibold text-[#EAD3CD] flex items-center gap-1">
                                            <x-icon name="star" class="w-3.5 h-3.5 text-yellow-500" />
                                            {{ $hotel->rating }} / 5.0
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if ($hotel->description)
                                <p class="text-xs leading-relaxed text-[#CFCBCA] opacity-80 line-clamp-3 mb-6">{{ $hotel->description }}</p>
                            @endif
                        </div>

                        <!-- Action Area -->
                        <div class="p-6 md:p-8 pt-0 mt-auto">
                            @if ($isWorking)
                                <div class="w-full text-center px-4 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                    style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3); color: #4ADE80;">
                                    Currently Employed
                                </div>
                            @elseif ($applicationStatus === 'pending')
                                <div class="w-full text-center px-4 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                    style="background: rgba(234, 179, 8, 0.1); border-color: rgba(234, 179, 8, 0.3); color: #FACC15;">
                                    Application Pending
                                </div>
                            @elseif ($applicationStatus === 'rejected')
                                <div class="w-full text-center px-4 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                    style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #F87171;">
                                    Application Rejected
                                </div>
                            @elseif ($isReceptionistBlocked)
                                <div class="w-full text-center px-4 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest border cursor-not-allowed"
                                    style="background: rgba(207, 203, 202, 0.05); border-color: rgba(207, 203, 202, 0.2); color: rgba(207, 203, 202, 0.5);">
                                    Blocked
                                </div>
                            @else
                                <a href="{{ route('staff.hotels.apply', $hotel) }}"
                                    class="block w-full text-center px-4 py-3 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all duration-300 shadow-md"
                                    style="background-color: #A0717F; color: #FFFFFF;"
                                    onmouseover="this.style.backgroundColor='#b58290'; this.style.transform='scale(1.02)';"
                                    onmouseout="this.style.backgroundColor='#A0717F'; this.style.transform='scale(1)';">
                                    Submit Application
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl shadow-xl p-16 text-center border-t border-[rgba(234,211,205,0.1)]" style="background-color: #383537;">
                <x-icon name="building" class="w-16 h-16 mx-auto mb-6" style="color: rgba(160, 113, 127, 0.3);" />
                <h3 class="text-xl font-bold font-serif text-[#EAD3CD] mb-2">No Openings</h3>
                <p class="text-sm uppercase tracking-widest" style="color: rgba(207, 203, 202, 0.5);">
                    There are currently no hotels accepting applications on our network.
                </p>
            </div>
        @endif
    </div>
@endsection
