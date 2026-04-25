@extends('layouts.app')

@section('content')
    {{-- Ambient Gradients --}}
    <div class="fixed top-0 left-0 w-[600px] h-[600px] rounded-full blur-3xl pointer-events-none"
        style="background: rgba(160, 113, 127, 0.05); z-index: 0;"></div>

    <div class="container mx-auto px-4 py-8 relative z-10">
        <x-breadcrumbs :links="[
            ['label' => 'Staff Dashboard', 'url' => route('staff.dashboard')],
            ['label' => 'My Applications', 'url' => '#']
        ]" />

        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-[rgba(234,211,205,0.05)] pb-6">
            <div>
                <h2 class="text-3xl lg:text-5xl font-bold font-serif text-[#EAD3CD]">My Applications</h2>
                <p class="text-xs font-medium uppercase mt-3" style="color: rgba(207, 203, 202, 0.6); letter-spacing: 0.15em;">
                    Track the lifecycle of your hotel work applications
                </p>
            </div>
            <a href="{{ route('staff.hotels.index') }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all duration-300 shadow-md shrink-0"
                style="background-color: #A0717F; color: #FFFFFF;"
                onmouseover="this.style.backgroundColor='#b58290'; this.style.transform='translateY(-2px)';"
                onmouseout="this.style.backgroundColor='#A0717F'; this.style.transform='translateY(0)';">
                <x-icon name="building" class="w-4 h-4" />
                Browse Hotels
            </a>
        </div>

        @if ($applications->count() > 0)
            
            <!-- Mobile/Tablet Card Layout (Visible up to ~1280px) -->
            <div class="block xl:hidden space-y-5">
                @foreach ($applications as $application)
                    <div class="bg-[#383537] rounded-2xl shadow-xl p-6 border-t-[3px]"
                        style="border-color: @if($application->status === 'pending') rgba(234, 179, 8, 0.6)
                                              @elseif($application->status === 'approved') rgba(34, 197, 94, 0.6)
                                              @else rgba(239, 68, 68, 0.6) @endif;">
                        
                        <div class="flex justify-between items-start mb-5 border-b border-[rgba(234,211,205,0.05)] pb-5">
                            <div>
                                <h3 class="text-xl font-bold font-serif text-[#EAD3CD] mb-1">{{ $application->hotel->name }}</h3>
                                <p class="text-sm text-[#CFCBCA] flex items-center gap-1.5 opacity-80">
                                    <x-icon name="location" class="w-3.5 h-3.5 text-[#A0717F]" />
                                    {{ $application->hotel->city }}, {{ $application->hotel->country }}
                                </p>
                            </div>
                            <div class="shrink-0 ml-4">
                                @if ($application->status === 'pending')
                                    <span class="inline-block px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                        style="background: rgba(234, 179, 8, 0.1); border-color: rgba(234, 179, 8, 0.3); color: #FACC15;">
                                        Pending
                                    </span>
                                @elseif ($application->status === 'approved')
                                    <span class="inline-block px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                        style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3); color: #4ADE80;">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                        style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #F87171;">
                                        Rejected
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <p class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-1">Target Role</p>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] uppercase tracking-wider text-[#EAD3CD]"
                                    style="background-color: rgba(26, 21, 21, 0.5); border: 1px solid rgba(234, 211, 205, 0.1);">
                                    {{ $application->role }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-1">Proposed Package</p>
                                <p class="text-sm font-semibold font-serif tracking-widest text-[#EAD3CD]">
                                    {{ $application->hourly_rate ? '$' . number_format($application->hourly_rate, 2) . '/hr' : 'TBD' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-5 border-t border-[rgba(234,211,205,0.05)]">
                            <div>
                                <p class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-0.5">Dispatched</p>
                                <p class="text-xs text-[#CFCBCA]">{{ $application->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] uppercase font-bold tracking-widest text-[#A0717F] mb-0.5">Reviewed</p>
                                <p class="text-xs text-[#CFCBCA]">
                                    {{ $application->reviewed_at ? $application->reviewed_at->format('M d, Y') : 'Pending Review' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop View Uncompressed Table (Visible 1280px and above) -->
            <div class="hidden xl:block rounded-2xl shadow-2xl overflow-hidden bg-[#383537] border-t border-[rgba(234,211,205,0.1)]">
                <div class="w-full overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead style="background-color: #2E2530; border-bottom: 1px solid rgba(160, 113, 127, 0.2);">
                            <tr>
                                <th class="px-6 py-5 text-[11px] font-bold uppercase tracking-widest" style="color: #A0717F;">Establishment</th>
                                <th class="px-6 py-5 text-[11px] font-bold uppercase tracking-widest" style="color: #A0717F;">Classification</th>
                                <th class="px-6 py-5 text-[11px] font-bold uppercase tracking-widest" style="color: #A0717F;">Current Status</th>
                                <th class="px-6 py-5 text-[11px] font-bold uppercase tracking-widest" style="color: #A0717F;">Remuneration</th>
                                <th class="px-6 py-5 text-[11px] font-bold uppercase tracking-widest" style="color: #A0717F;">Dispatched On</th>
                                <th class="px-6 py-5 text-[11px] font-bold uppercase tracking-widest text-right" style="color: #A0717F;">Adjudicated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[rgba(234,211,205,0.05)]">
                            @foreach ($applications as $application)
                                <tr class="transition-colors duration-300"
                                    onmouseover="this.style.backgroundColor='rgba(160, 113, 127, 0.05)';"
                                    onmouseout="this.style.backgroundColor='transparent';">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-[#EAD3CD] font-serif tracking-wide text-lg">{{ $application->hotel->name }}</p>
                                        <p class="text-sm text-[#CFCBCA]">{{ $application->hotel->city }}, {{ $application->hotel->country }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded text-[10px] uppercase font-bold tracking-widest text-[#EAD3CD]"
                                            style="background-color: rgba(26, 21, 21, 0.5); border: 1px solid rgba(234, 211, 205, 0.1);">
                                            {{ $application->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($application->status === 'pending')
                                            <span class="inline-block px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                                style="background: rgba(234, 179, 8, 0.1); border-color: rgba(234, 179, 8, 0.3); color: #FACC15;">
                                                Pending Review
                                            </span>
                                        @elseif ($application->status === 'approved')
                                            <span class="inline-block px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                                style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3); color: #4ADE80;">
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest border"
                                                style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #F87171;">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-bold font-serif" style="color: #A0717F;">
                                            {{ $application->hourly_rate ? '$' . number_format($application->hourly_rate, 2) . '/hr' : 'TBD' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-[#CFCBCA]">
                                        {{ $application->created_at->format('M d, Y') }}
                                        <div class="text-[10px] opacity-60 font-normal uppercase mt-0.5 tracking-wider">{{ $application->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-semibold text-[#CFCBCA]">
                                        @if($application->reviewed_at)
                                            {{ $application->reviewed_at->format('M d, Y') }}
                                            <div class="text-[10px] opacity-60 font-normal uppercase mt-0.5 tracking-wider">{{ $application->reviewed_at->diffForHumans() }}</div>
                                        @else
                                            <span class="opacity-50 italic">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <div class="rounded-2xl shadow-xl p-16 text-center border-t border-[rgba(234,211,205,0.1)] mt-12" style="background-color: #383537;">
                <x-icon name="file" class="w-16 h-16 mx-auto mb-6" style="color: rgba(160, 113, 127, 0.3);" />
                <h3 class="text-xl font-bold font-serif text-[#EAD3CD] mb-2">No Applications Record</h3>
                <p class="text-sm uppercase tracking-widest mb-8 leading-loose" style="color: rgba(207, 203, 202, 0.5);">
                    Your dossier is currently barren.<br>Explore available properties below to establish your career.
                </p>
                <a href="{{ route('staff.hotels.index') }}"
                    class="inline-flex text-[#FFFFFF] font-bold px-8 py-4 rounded-xl transition-all duration-300 text-xs uppercase tracking-widest shadow-xl"
                    style="background-color: #A0717F;"
                    onmouseover="this.style.backgroundColor='#b58290'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.backgroundColor='#A0717F'; this.style.transform='translateY(0)';">
                    Access Network Openings
                </a>
            </div>
        @endif
        
    </div>
@endsection
