@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 pt-8">
        <x-breadcrumbs :links="[
            ['label' => 'Admin', 'url' => route('admin.dashboard')],
            ['label' => 'Hotel Requests', 'url' => route('admin.hotel-requests.index')],
            ['label' => $request->name, 'url' => '#']
        ]" />
    </div>

    <!-- Page Header -->
    <div class="mb-8 border-b border-[#4E3B46] flex justify-between items-start"
        style="background: linear-gradient(180deg, rgba(42, 39, 41, 0.5) 0%, transparent 100%);">
        <div class="py-6 px-4">
            <h1 class="text-3xl font-semibold text-[#EAD3CD]">Request: {{ $request->name }}</h1>
            <p class="text-sm mt-2 text-[#CFCBCA]">Submitted by {{ $request->owner->name }}</p>
        </div>
        <div class="py-6 px-4">
            <a href="{{ route('admin.hotel-requests.index') }}"
                class="px-4 py-2 text-sm border border-[#4E3B46] text-[#CFCBCA] rounded-lg hover:bg-[#2A2729] transition">
                Back to Requests
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto mb-8">
        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <h3 class="text-sm font-semibold text-[#EAD3CD] uppercase tracking-wider mb-4">Request Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border-b border-[#4E3B46] pb-4">
                    <p class="text-xs text-[#CFCBCA] font-medium">Status</p>
                    <div class="mt-2 text-sm">
                        <span class="text-xs px-2.5 py-1 rounded font-medium
                            @if ($request->status === 'pending') bg-yellow-900/50 text-yellow-300 border border-yellow-800
                            @elseif ($request->status === 'approved') bg-green-900/50 text-green-300 border border-green-800
                            @elseif ($request->status === 'rejected') bg-red-900/50 text-red-300 border border-red-800
                            @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                </div>
                <div class="border-b border-[#4E3B46] pb-4">
                    <p class="text-xs text-[#CFCBCA] font-medium">Owner Email</p>
                    <p class="text-sm text-[#EAD3CD] mt-2">{{ $request->owner->email }}</p>
                </div>
                <div class="border-b border-[#4E3B46] pb-4">
                    <p class="text-xs text-[#CFCBCA] font-medium">Hotel Address</p>
                    <p class="text-sm text-[#EAD3CD] mt-2">{{ $request->address }}</p>
                </div>
                <div class="border-b border-[#4E3B46] pb-4">
                    <p class="text-xs text-[#CFCBCA] font-medium">Hotel Contact Email</p>
                    <p class="text-sm text-[#EAD3CD] mt-2">{{ $request->email }}</p>
                </div>
            </div>

            @if ($request->description)
                <div class="border-t border-[#4E3B46] mt-6 pt-6">
                    <p class="text-xs text-[#CFCBCA] font-medium">Description provided</p>
                    <p class="text-sm text-[#EAD3CD] mt-2">{{ $request->description }}</p>
                </div>
            @endif

            @if ($request->admin_notes)
                <div class="border-t border-[#4E3B46] mt-6 pt-6">
                    <p class="text-xs text-[#CFCBCA] font-medium">Admin Notes</p>
                    <p class="text-sm text-[#EAD3CD] mt-2">{{ $request->admin_notes }}</p>
                </div>
            @endif

            @if ($request->status === 'pending')
                <div class="mt-8 flex gap-4">
                    <form action="{{ route('admin.hotel-requests.approve', $request) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition-colors"
                            style="background-color: #A0717F;" onmouseover="this.style.backgroundColor='#8F6470'"
                            onmouseout="this.style.backgroundColor='#A0717F'">
                            Approve
                        </button>
                    </form>
                    <button type="button" onclick="showRejectModal()"
                        class="px-6 py-2 rounded-lg font-medium border border-[#4E3B46] text-[#CFCBCA] hover:bg-[#2A2729] transition">
                        Reject
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if ($request->status === 'pending')
        <!-- Hidden Reject Form -->
        <form id="reject-form" action="{{ route('admin.hotel-requests.reject', $request) }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="admin_notes" id="reject-reason">
        </form>

        <script>
            function showRejectModal() {
                NocturnalUI.prompt('Enter rejection reason (required):', `Reject {{ addslashes($request->name) }}?`).then(reason => {
                    if (reason !== null && reason.trim().length > 0) {
                        document.getElementById('reject-reason').value = reason;
                        document.getElementById('reject-form').submit();
                    } else if (reason !== null) {
                        NocturnalUI.alert('Rejection reason is explicitly required.', 'Input Missing');
                    }
                });
            }
        </script>
    @endif
@endsection
