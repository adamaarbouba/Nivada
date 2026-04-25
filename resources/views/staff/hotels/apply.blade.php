@extends('layouts.app')

@section('content')
    {{-- Ambient Gradients --}}
    <div class="fixed top-0 right-0 w-[500px] h-[500px] rounded-full blur-3xl pointer-events-none"
        style="background: rgba(160, 113, 127, 0.05); z-index: 0;"></div>

    <div class="container mx-auto px-4 py-12 max-w-4xl relative z-10">
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-[rgba(234,211,205,0.05)] pb-6">
            <div>
                <p class="text-xs font-medium uppercase mb-2" style="color: #A0717F; letter-spacing: 0.4em;">
                    Career Application
                </p>
                <h2 class="text-3xl lg:text-5xl font-bold font-serif text-[#EAD3CD] mb-2">{{ $hotel->name }}</h2>
                <p class="text-xs font-medium uppercase mt-2" style="color: rgba(207, 203, 202, 0.6); letter-spacing: 0.15em;">
                    Applying for position: <strong class="text-[#EAD3CD]">{{ ucfirst($role) }}</strong>
                </p>
            </div>
            <a href="{{ route('staff.hotels.index') }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all duration-300 shrink-0"
                style="background-color: transparent; color: #CFCBCA; border: 1px solid rgba(234, 211, 205, 0.2);"
                onmouseover="this.style.backgroundColor='rgba(234, 211, 205, 0.05)'; this.style.color='#EAD3CD';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#CFCBCA';">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Cancel Process
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Hotel Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-2xl shadow-xl overflow-hidden bg-[#383537] border-t border-[rgba(234,211,205,0.05)] p-6 md:p-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-[#A0717F] mb-6">Property Profile</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-widest text-[#CFCBCA] mb-1 opacity-70">Region</p>
                            <p class="text-sm text-[#EAD3CD] flex items-center gap-2">
                                <x-icon name="location" class="w-4 h-4 text-[#A0717F]" />
                                {{ $hotel->city }}, {{ $hotel->country }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-widest text-[#CFCBCA] mb-1 opacity-70">Base Remuneration</p>
                            <p class="text-lg font-bold font-serif tracking-wide text-[#EAD3CD]">
                                {{ $hotel->default_hourly_wage ? '$' . number_format($hotel->default_hourly_wage, 2) . '/hr' : 'Discussed upon hire' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-widest text-[#CFCBCA] mb-1 opacity-70">Volume</p>
                            <p class="text-sm text-[#EAD3CD]">{{ $hotel->rooms()->count() }} Total Suites</p>
                        </div>
                    </div>
                </div>

                @if ($hotel->description)
                    <div class="rounded-2xl shadow-xl overflow-hidden bg-[#2A2729] border border-[rgba(234,211,205,0.03)] p-6">
                        <p class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-3">About {{ $hotel->name }}</p>
                        <p class="text-xs leading-relaxed text-[#CFCBCA] opacity-80">{{ $hotel->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Form -->
            <div class="lg:col-span-2">
                <div class="rounded-2xl shadow-xl overflow-hidden bg-[#383537] border border-[rgba(234,211,205,0.05)] p-6 md:p-10">
                    <h3 class="text-xl font-bold font-serif text-[#EAD3CD] mb-8">Letter of Intent</h3>
                    
                    <form action="{{ route('staff.hotels.storeApplication', $hotel) }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-3">Target Role</label>
                            <div class="w-full px-5 py-4 border rounded-xl text-sm font-semibold tracking-wide"
                                style="background-color: rgba(26, 21, 21, 0.3); border-color: rgba(234, 211, 205, 0.1); color: #CFCBCA;">
                                {{ ucfirst($role) }} Professional
                            </div>
                            <p class="text-xs text-[#CFCBCA] opacity-60 mt-2">Your application will be locked to this specific staff classification.</p>
                        </div>

                        <div class="mb-10">
                            <label for="message" class="block text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-3">
                                Introduction / Cover Message
                            </label>
                            <textarea name="message" id="message" rows="5"
                                class="w-full px-5 py-4 rounded-xl text-sm outline-none transition-all duration-300 resize-none font-serif"
                                style="background-color: #2A2729; border: 1px solid rgba(234, 211, 205, 0.1); color: #EAD3CD;"
                                onfocus="this.style.borderColor='#A0717F'; this.style.boxShadow='0 0 0 2px rgba(160,113,127,0.2)';"
                                onblur="this.style.borderColor='rgba(234, 211, 205, 0.1)'; this.style.boxShadow='none';"
                                placeholder="Express your interest, detail relevant past experience, or outline your availability...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-t border-[rgba(234,211,205,0.05)] pt-8 flex items-center justify-between">
                            <p class="text-[10px] uppercase font-bold tracking-widest text-[#CFCBCA] opacity-60 hidden sm:block">
                                Awaiting Submission
                            </p>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-300 shadow-xl"
                                style="background-color: #A0717F; color: #FFFFFF;"
                                onmouseover="this.style.backgroundColor='#b58290'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.backgroundColor='#A0717F'; this.style.transform='translateY(0)';">
                                Submit Cover Letter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
