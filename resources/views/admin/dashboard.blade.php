@extends('layouts.app')

@section('content')
    <!-- Background -->
    <div style="background-color: transparent; min-height: 100vh;">

        <!-- Breadcrumb Navigation -->
        <div class="max-w-7xl mx-auto px-4 pt-8 pb-2">
            <x-breadcrumbs :links="[
                ['label' => 'Admin', 'url' => route('admin.dashboard')],
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')]
            ]" />
        </div>

        <!-- Page Header with Title and Actions -->
        <div class="max-w-7xl mx-auto px-4 pb-8">
            <div>
                <h1 class="text-4xl font-bold text-[#EAD3CD]">Dashboard</h1>
                <p class="text-sm text-[#CFCBCA] mt-1">System overview and management</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 pb-12">
            <!-- KPI Row - 4 Cards with Trends -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Hotels -->
                <div
                    class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Total Hotels</p>
                            <p class="text-4xl font-bold text-[#EAD3CD] mt-4">{{ $stats['totalHotels'] }}</p>
                            <p class="text-xs text-[#CFCBCA] mt-2">Active properties</p>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div
                    class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Total Users</p>
                            <p class="text-4xl font-bold text-[#EAD3CD] mt-4">{{ $stats['totalUsers'] }}</p>
                            <p class="text-xs text-[#CFCBCA] mt-2">Registered members</p>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div
                    class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Total Bookings</p>
                            <p class="text-4xl font-bold text-[#EAD3CD] mt-4">{{ $stats['totalBookings'] }}</p>
                            <p class="text-xs text-[#CFCBCA] mt-2">All-time total</p>
                        </div>
                    </div>
                </div>

                <!-- Approval Rate -->
                <div
                    class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Approval Rate</p>
                            <p class="text-4xl font-bold text-[#EAD3CD] mt-4">
                                @php
                                    $total =
                                        $stats['pendingRequests'] +
                                        $stats['approvedRequests'] +
                                        $stats['rejectedRequests'];
                                    $rate = $total > 0 ? round(($stats['approvedRequests'] / $total) * 100) : 0;
                                @endphp
                                {{ $rate }}%
                            </p>
                            <p class="text-xs text-[#CFCBCA] mt-2">Hotel requests</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - 3 Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (2 columns) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Request Status Overview -->
                    <div
                        class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:border-[#4E3B46] transition">
                        <h3 class="text-lg font-bold text-[#EAD3CD] mb-6">Request Status Breakdown</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 rounded-lg" style="background-color: #2A2729;">
                                <p class="text-3xl font-bold text-[#A0717F]">{{ $stats['pendingRequests'] }}</p>
                                <p class="text-xs text-[#CFCBCA] mt-2">Pending</p>
                            </div>
                            <div class="text-center p-4 rounded-lg" style="background-color: #2A2729;">
                                <p class="text-3xl font-bold text-[#A0717F]">{{ $stats['approvedRequests'] }}</p>
                                <p class="text-xs text-[#CFCBCA] mt-2">Approved</p>
                            </div>
                            <div class="text-center p-4 rounded-lg" style="background-color: #2A2729;">
                                <p class="text-3xl font-bold text-[#A0717F]">{{ $stats['rejectedRequests'] }}</p>
                                <p class="text-xs text-[#CFCBCA] mt-2">Rejected</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Hotel Requests Table -->
                    <div
                        class="bg-[#383537] rounded-2xl border border-transparent shadow-sm overflow-hidden hover:border-[#4E3B46] transition">
                        <div class="px-8 py-6 border-b border-[#4E3B46]"
                            style="background-color: #2A2729;">
                            <h3 class="text-lg font-bold text-[#EAD3CD]">Recent Hotel Requests</h3>
                        </div>
                        @if ($recentRequests->count() > 0)
                            <div class="divide-y divide-[#4E3B46]">
                                @foreach ($recentRequests as $request)
                                    <div class="px-8 py-4 hover:bg-[#2A2729] transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-[#EAD3CD]">{{ $request->name }}</h4>
                                                <p class="text-xs text-[#CFCBCA] mt-1">by
                                                    <strong>{{ $request->owner->name }}</strong> •
                                                    {{ $request->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                            <span class="text-xs px-3 py-1.5 rounded-full font-medium"
                                                style="background-color: {{ $request->status === 'approved' ? '#4E3B46' : ($request->status === 'rejected' ? '#2A2729' : '#2A2729') }}; color: #A0717F;">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-8 py-12 text-center text-[#CFCBCA]">
                                <p>No recent requests</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Recent Users Card -->
                    <div
                        class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:border-[#4E3B46] transition">
                        <h3 class="text-lg font-bold text-[#EAD3CD] mb-6">Recent Users</h3>
                        @if ($systemUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach ($systemUsers->take(5) as $user)
                                    <div
                                        class="flex items-center justify-between pb-4 border-b border-[#4E3B46] last:border-b-0">
                                        <div>
                                            <p class="text-sm font-medium text-[#EAD3CD]">{{ $user->name }}</p>
                                            <p class="text-xs text-[#CFCBCA]">{{ ucfirst($user->role->slug) }}</p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded"
                                            style="background-color: #2A2729; color: #CFCBCA;">
                                            {{ $user->created_at->format('M d') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-[#CFCBCA]">No users found</p>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div
                        class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:border-[#4E3B46] transition">
                        <h3 class="text-lg font-bold text-[#EAD3CD] mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.hotel-requests.index') }}"
                                class="w-full block px-4 py-3 rounded-lg font-medium text-white transition-colors text-center"
                                style="background-color: #A0717F;" onmouseover="this.style.backgroundColor='#8F6470'"
                                onmouseout="this.style.backgroundColor='#A0717F'">
                                Review Requests
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                                class="w-full block px-4 py-3 rounded-lg font-medium border border-[#4E3B46] text-[#CFCBCA] text-center hover:bg-[#2A2729] transition">
                                Manage Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




