@extends('layouts.app')

@section('content')
    <x-breadcrumbs :links="[
        ['label' => 'Owner Dashboard', 'url' => route('owner.dashboard')],
        ['label' => 'Staff Applications', 'url' => '#']
    ]" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-[#EAD3CD]">Staff Applications</h2>
            <p class="text-[#CFCBCA] mt-1">Review and manage staff applications for your hotels</p>
        </div>
        <a href="{{ route('owner.dashboard') }}"
            class="border border-[#4E3B46] hover:bg-[#2A2729] text-[#CFCBCA] font-semibold px-5 py-2 rounded transition text-sm">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Pending Applications -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-[#4E3B46] bg-[#2A2729]">
            <h3 class="text-lg font-semibold text-[#EAD3CD]">
                Pending Applications
                @if ($pendingApplications->count() > 0)
                    <span class="inline-flex items-center justify-center w-6 h-6 ml-2 text-xs font-bold text-white rounded-full bg-[#A0717F]">
                        {{ $pendingApplications->count() }}
                    </span>
                @endif
            </h3>
        </div>

        @if ($pendingApplications->count() > 0)
            <div class="divide-y divide-[#4E3B46]">
                @foreach ($pendingApplications as $application)
                    <div class="px-6 py-5 hover:bg-[#2A2729]/50 transition border-t border-[#4E3B46]">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-lg font-semibold text-[#EAD3CD]">{{ $application->user->name }}</h4>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border bg-[#2A2729]
                                        {{ $application->role === 'cleaner' ? 'text-blue-400 border-blue-500' : ($application->role === 'inspector' ? 'text-purple-400 border-purple-500' : 'text-green-400 border-green-500') }}">
                                        {{ ucfirst($application->role) }}
                                    </span>
                                </div>
                                <p class="text-sm text-[#CFCBCA] mb-1">
                                    <strong class="text-[#EAD3CD]">Hotel:</strong> {{ $application->hotel->name }}
                                </p>
                                <p class="text-sm text-[#CFCBCA] mb-1">
                                    <strong class="text-[#EAD3CD]">Email:</strong> {{ $application->user->email }}
                                </p>
                                @if ($application->message)
                                    <p class="text-sm text-[#CFCBCA] mt-2 italic">
                                        "{{ $application->message }}"
                                    </p>
                                @endif
                                <p class="text-xs text-[#4E3B46] mt-2">Applied {{ $application->created_at->diffForHumans() }}</p>
                            </div>

                            <!-- Approve/Reject Actions -->
                            <div class="flex flex-col gap-2 min-w-[200px]">
                                <form action="{{ route('owner.staff.approve', $application) }}" method="POST" class="flex flex-col gap-2">
                                    @csrf
                                    <label class="text-xs text-[#CFCBCA] font-semibold">Hourly Rate ($)</label>
                                    <input type="number" name="hourly_rate"
                                        value="{{ $application->hotel->default_hourly_wage ?? '15.00' }}"
                                        step="0.01" min="0.01"
                                        class="px-3 py-2 border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#A0717F]">
                                    <button type="submit"
                                        class="text-white font-semibold px-4 py-2 rounded transition text-sm bg-[#A0717F] hover:bg-[#8F6470]">
                                        ✓ Approve
                                    </button>
                                </form>
                                <form action="{{ route('owner.staff.reject', $application) }}" method="POST">
                                    @csrf
                                    <button type="button" onclick="NocturnalUI.confirm('Reject this application?', 'Reject Application').then(c => { if(c) this.closest('form').submit(); })"
                                        class="w-full text-white font-semibold px-4 py-2 rounded transition text-sm bg-[#4E3B46] hover:bg-red-700">
                                        ✗ Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-[#CFCBCA]">No pending applications at this time.</p>
            </div>
        @endif
    </div>

    <!-- Recently Reviewed Applications -->
    <div class="bg-[#383537] border border-[#4E3B46] rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-[#4E3B46] bg-[#2A2729]">
            <h3 class="text-lg font-semibold text-[#EAD3CD]">Recently Reviewed</h3>
        </div>

        @if ($reviewedApplications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#2A2729] border-b border-[#4E3B46]">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Applicant</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Hotel</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Role</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Rate</th>
                            <th class="px-6 py-3 text-xs font-semibold text-[#CFCBCA]">Reviewed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviewedApplications as $app)
                            <tr class="border-b border-[#4E3B46] hover:bg-[#2A2729]/50">
                                <td class="px-6 py-4 text-sm text-[#EAD3CD] font-semibold">{{ $app->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ $app->hotel->name }}</td>
                                <td class="px-6 py-4 text-sm text-[#CFCBCA]">{{ ucfirst($app->role) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold border bg-[#2A2729]
                                        {{ $app->status === 'approved' ? 'text-green-400 border-green-500' : 'text-red-400 border-red-500' }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#CFCBCA]">
                                    {{ $app->hourly_rate ? '$' . number_format($app->hourly_rate, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-[#4E3B46]">
                                    {{ $app->reviewed_at?->diffForHumans() ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-[#CFCBCA]">No reviewed applications yet.</p>
            </div>
        @endif
    </div>
@endsection
