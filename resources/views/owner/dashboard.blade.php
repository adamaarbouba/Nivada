@extends('layouts.app')

@php
    $pageTitle = 'Owner Dashboard';
@endphp

@section('content')
    <x-breadcrumbs :links="[
        ['label' => 'Owner Dashboard', 'url' => route('owner.dashboard')]
    ]" />

    <!-- Statistics Cards Section - Step 3 -->
    <div id="stats-section">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Hotels Card -->
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#A0717F] bg-[#383537]">
                <h3 class="text-sm font-semibold text-[#EAD3CD]">Total Hotels</h3>
                <p class="text-4xl font-bold mt-3 text-[#A0717F]">{{ $stats['totalHotels'] }}</p>
                <p class="text-xs mt-2 text-[#CFCBCA]">Hotels owned by you</p>
            </div>

            <!-- Total Rooms Card -->
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#4E3B46] bg-[#383537]">
                <h3 class="text-sm font-semibold text-[#EAD3CD]">Total Rooms</h3>
                <p class="text-4xl font-bold mt-3 text-[#EAD3CD]">{{ $stats['totalRooms'] }}</p>
                <p class="text-xs mt-2 text-[#CFCBCA]">Across all hotels</p>
            </div>

            <!-- Total Bookings Card -->
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#A0717F] bg-[#383537]">
                <h3 class="text-sm font-semibold text-[#EAD3CD]">Total Bookings</h3>
                <p class="text-4xl font-bold mt-3 text-[#A0717F]">{{ $stats['totalBookings'] }}</p>
                <p class="text-xs mt-2 text-[#CFCBCA]">Active and completed</p>
            </div>
        </div>
    </div>

    <!-- Room Status Breakdown Section -->
    <div id="room-status-section" class="mt-8">
        <h3 class="text-xl font-bold mb-6 text-[#EAD3CD]">Room Status Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#4E3B46] bg-[#383537]">
                <h4 class="text-sm font-semibold text-[#EAD3CD]">Available</h4>
                <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $stats['roomStatusCounts']['available'] }}</p>
            </div>
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#A0717F] bg-[#383537]">
                <h4 class="text-sm font-semibold text-[#EAD3CD]">Occupied</h4>
                <p class="text-3xl font-bold mt-2 text-[#A0717F]">{{ $stats['roomStatusCounts']['occupied'] }}</p>
            </div>
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#4E3B46] bg-[#383537]">
                <h4 class="text-sm font-semibold text-[#EAD3CD]">Cleaning</h4>
                <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $stats['roomStatusCounts']['cleaning'] }}</p>
            </div>
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#A0717F] bg-[#383537]">
                <h4 class="text-sm font-semibold text-[#EAD3CD]">Inspection</h4>
                <p class="text-3xl font-bold mt-2 text-[#A0717F]">{{ $stats['roomStatusCounts']['inspection'] }}
                </p>
            </div>
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#4E3B46] bg-[#383537]">
                <h4 class="text-sm font-semibold text-[#EAD3CD]">Maintenance</h4>
                <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $stats['roomStatusCounts']['maintenance'] }}
                </p>
            </div>
            <div class="rounded-lg shadow-lg p-6 border-t-4 border-[#4E3B46] bg-[#383537]">
                <h4 class="text-sm font-semibold text-[#EAD3CD]">Disabled</h4>
                <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $stats['roomStatusCounts']['disabled'] }}</p>
            </div>
        </div>
    </div>

    <!-- Hotel Requests Status Section - Step 6 -->
    <div id="requests-section" class="mt-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Pending Requests -->
            <div class="rounded-lg shadow-lg p-6 bg-[#383537] border-l-4 border-[#A0717F]">
                <h3 class="text-sm font-semibold text-[#EAD3CD]">Pending Requests</h3>
                <p class="text-3xl font-bold mt-2 text-[#A0717F]">{{ $hotelRequests['pending'] }}</p>
                <a href="{{ route('owner.hotel-requests.index') }}" class="text-xs font-semibold mt-3 inline-block text-[#A0717F] hover:underline">View Details →</a>
            </div>

            <!-- Approved Requests -->
            <div class="rounded-lg shadow-lg p-6 bg-[#383537] border-l-4 border-[#4E3B46]">
                <h3 class="text-sm font-semibold text-[#EAD3CD]">Approved Requests</h3>
                <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $hotelRequests['approved'] }}</p>
                <a href="{{ route('owner.hotel-requests.index') }}" class="text-xs font-semibold mt-3 inline-block text-[#CFCBCA] hover:text-[#EAD3CD]">View Details →</a>
            </div>

            <!-- Rejected Requests -->
            <div class="rounded-lg shadow-lg p-6 bg-[#383537] border-l-4 border-[#4E3B46]">
                <h3 class="text-sm font-semibold text-[#EAD3CD]">Rejected Requests</h3>
                <p class="text-3xl font-bold mt-2 text-[#EAD3CD]">{{ $hotelRequests['rejected'] }}</p>
                <a href="{{ route('owner.hotel-requests.index') }}" class="text-xs font-semibold mt-3 inline-block text-[#CFCBCA] hover:text-[#EAD3CD]">View Details →</a>
            </div>
        </div>

        <!-- Quick Action Button -->
        <div class="rounded-lg p-6 flex items-center justify-between border border-[#4E3B46] bg-[#2A2729]">
            <div>
                <h4 class="font-semibold text-[#EAD3CD]">Ready to add a new hotel?</h4>
                <p class="text-sm mt-1 text-[#CFCBCA]">Submit a hotel request to expand your portfolio</p>
            </div>
            <a href="{{ route('owner.hotel-requests.create') }}"
                class="text-white font-semibold px-6 py-2 rounded transition bg-[#A0717F] hover:bg-[#8F6470]">
                Request New Hotel
            </a>
        </div>

        <!-- Maintenance Quick Action -->
        @if ($stats['roomStatusCounts']['maintenance'] > 0 || $stats['roomStatusCounts']['cleaning'] > 0)
            <div class="rounded-lg p-6 flex items-center justify-between mt-4 border border-[#4E3B46] bg-[#2A2729]">
                <div>
                    <h4 class="font-semibold text-[#EAD3CD]">Room Maintenance Needed</h4>
                    <p class="text-sm mt-1 text-[#CFCBCA]">
                        {{ $stats['roomStatusCounts']['maintenance'] }} rooms in maintenance,
                        {{ $stats['roomStatusCounts']['cleaning'] }} in cleaning
                    </p>
                </div>
                <a href="{{ route('owner.maintenance.index') }}"
                    class="text-white font-semibold px-6 py-2 rounded transition bg-[#A0717F] hover:bg-[#8F6470]">
                    View Maintenance
                </a>
            </div>
        @endif
    </div>

    <!-- Occupancy Rate Chart Section -->
    @if (count($recentHotels) > 0)
        <div id="occupancy-chart-section" class="mt-12">
            <div class="rounded-lg shadow-lg p-8 bg-[#383537]">
                <h3 class="text-xl font-bold mb-6 text-[#EAD3CD]">Hotel Occupancy Rates</h3>
                <div class="relative h-80">
                    <canvas id="occupancyChart"></canvas>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Hotels Section - Step 4 -->
    <div id="hotels-section" class="mt-12">
        <div class="rounded-lg shadow-lg p-8 bg-[#383537]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-[#EAD3CD]">Your Hotels</h3>
                <a href="{{ route('owner.hotels.index') }}" class="text-sm font-semibold text-[#A0717F] hover:underline">View
                    All →</a>
            </div>

            @if (count($recentHotels) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($recentHotels as $hotel)
                        <div class="border border-[#4E3B46] bg-[#2A2729] rounded-lg p-6 hover:shadow-md transition">
                            <h4 class="font-semibold text-lg mb-2 text-[#EAD3CD]">{{ $hotel['name'] }}</h4>
                            <div class="space-y-2 text-sm mb-4 text-[#CFCBCA]">
                                <p><strong class="text-[#EAD3CD]">Location:</strong> {{ $hotel['location'] }}</p>
                                <p><strong class="text-[#EAD3CD]">Total Rooms:</strong> {{ $hotel['rooms'] }}</p>
                                <p><strong class="text-[#EAD3CD]">Active Bookings:</strong> {{ $hotel['bookings'] }}</p>
                                <p><strong class="text-[#A0717F]">Occupancy Rate:</strong> <span
                                        class="font-bold text-[#A0717F]">{{ $hotel['occupancy_rate'] }}%</span>
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('owner.hotels.show', $hotel['id']) }}"
                                    class="text-[#CFCBCA] font-semibold px-4 py-2 rounded text-sm transition border border-[#4E3B46] hover:bg-[#383537]">View</a>
                                <a href="{{ route('owner.hotels.manage', $hotel['id']) }}"
                                    class="text-[#CFCBCA] font-semibold px-4 py-2 rounded text-sm transition border border-[#4E3B46] hover:bg-[#383537]">Manage</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-[#CFCBCA]">No hotels found. Submit a hotel request to get started.</p>
            @endif
        </div>
    </div>

    <!-- Chart.js Script and Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('occupancyChart');
            if (canvas) {
                const hotelData = @json($recentHotels);

                const labels = hotelData.map(hotel => hotel.name);
                const rates = hotelData.map(hotel => hotel.occupancy_rate);

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Occupancy Rate (%)',
                            data: rates,
                            backgroundColor: [
                                '#A0717F',
                                '#4E3B46',
                                '#4E3B46',
                                '#4E3B46',
                                '#A0717F',
                                '#4E3B46',
                                '#4E3B46',
                                '#4E3B46',
                                '#A0717F',
                                '#4E3B46'
                            ],
                            borderColor: '#383537',
                            borderWidth: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#EAD3CD',
                                    font: {
                                        size: 14,
                                        weight: '600'
                                    }
                                }
                            },
                        },
                        scales: {
                            x: {
                                max: 100,
                                ticks: {
                                    color: '#CFCBCA',
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                },
                                grid: {
                                    color: 'rgba(207, 203, 202, 0.1)'
                                }
                            },
                            y: {
                                ticks: {
                                    color: '#CFCBCA',
                                    font: {
                                        weight: '500'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection




