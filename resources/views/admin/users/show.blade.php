@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-8 border-b border-[#4E3B46] flex justify-between items-start"
        style="background: linear-gradient(180deg, rgba(42, 39, 41, 0.5) 0%, transparent 100%);">
        <div class="py-6">
            <h1 class="text-3xl font-semibold text-[#EAD3CD]">{{ $user->name }}</h1>
            <p class="text-sm mt-2 text-[#CFCBCA]">
                @if ($user->banned_at)
                    <span class="text-red-500 font-semibold">Banned Account</span>
                @else
                    Member since {{ $user->created_at->format('M d, Y') }}
                @endif
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="px-4 py-2 text-sm border border-[#4E3B46] text-[#CFCBCA] rounded-lg hover:bg-[#2A2729] transition">
            Back to List
        </a>
    </div>

    <!-- User Stats - 4 Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Role</p>
            <p class="text-2xl font-semibold text-[#EAD3CD] mt-3">{{ ucfirst($user->role->slug) }}</p>
            <p class="text-xs text-[#CFCBCA] mt-1">User classification</p>
        </div>

        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Status</p>
            <div class="mt-3">
                @if ($user->banned_at)
                    <span class="inline-block px-2.5 py-1 text-xs bg-red-900/50 text-red-300 border border-red-800 rounded font-medium">Banned</span>
                @else
                    <span
                        class="inline-block px-2.5 py-1 text-xs bg-green-900/50 text-green-300 border border-green-800 rounded font-medium">Active</span>
                @endif
            </div>
            <p class="text-xs text-[#CFCBCA] mt-2">Account state</p>
        </div>

        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Email</p>
            <p class="text-sm font-medium text-[#EAD3CD] mt-3 break-words">{{ $user->email }}</p>
            <p class="text-xs text-[#CFCBCA] mt-1">Contact address</p>
        </div>

        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <p class="text-xs uppercase tracking-wider text-[#CFCBCA] font-medium">Phone</p>
            <p class="text-sm font-medium text-[#EAD3CD] mt-3">{{ $user->phone ?? 'N/A' }}</p>
            <p class="text-xs text-[#CFCBCA] mt-1">Telephone number</p>
        </div>
    </div>

    <!-- User Details -->
    <div class="border border-[#4E3B46] rounded-lg p-6 mb-8 bg-[#383537]">
        <h3 class="text-sm font-semibold text-[#EAD3CD] uppercase tracking-wider mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-b border-[#4E3B46] pb-4">
                <p class="text-xs text-[#CFCBCA] font-medium">Email</p>
                <p class="text-sm text-[#EAD3CD] mt-2">{{ $user->email }}</p>
            </div>
            <div class="border-b border-[#4E3B46] pb-4">
                <p class="text-xs text-[#CFCBCA] font-medium">Phone</p>
                <p class="text-sm text-[#EAD3CD] mt-2">{{ $user->phone ?? 'Not provided' }}</p>
            </div>
            <div class="border-b border-[#4E3B46] pb-4">
                <p class="text-xs text-[#CFCBCA] font-medium">Address</p>
                <p class="text-sm text-[#EAD3CD] mt-2">{{ $user->address ?? 'Not provided' }}</p>
            </div>
            <div class="border-b border-[#4E3B46] pb-4">
                <p class="text-xs text-[#CFCBCA] font-medium">Joined</p>
                <p class="text-sm text-[#EAD3CD] mt-2">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-8 flex gap-3">
        @if ($user->banned_at)
            <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-2.5 rounded-lg font-medium text-white transition-colors"
                    style="background-color: #A0717F;" onmouseover="this.style.backgroundColor='#8F6470'"
                    onmouseout="this.style.backgroundColor='#A0717F'">
                    Unban User
                </button>
            </form>
        @else
            <form id="banForm" action="{{ route('admin.users.ban', $user) }}" method="POST" class="inline">
                @csrf
                <button type="button" onclick="openBanModal()"
                    class="px-6 py-2.5 rounded-lg font-medium text-white transition-colors"
                    style="background-color: #A0717F;" onmouseover="this.style.backgroundColor='#8F6470'"
                    onmouseout="this.style.backgroundColor='#A0717F'">
                    Ban User
                </button>
            </form>
        @endif
    </div>

    <!-- Ban Confirmation Modal -->
    <div id="banModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
        onclick="closeBanModal(event)">
        <div class=" rounded-lg shadow-xl p-6 max-w-md w-full mx-4 bg-[#2A2729] border border-[#4E3B46]" onclick="event.stopPropagation()">
            <h2 class="text-xl font-bold text-[#EAD3CD] mb-3">Ban User</h2>
            <p class="text-sm text-[#CFCBCA] mb-6">
                Are you sure you want to ban <strong class="text-[#EAD3CD]">{{ $user->name }}</strong>? This user will no longer be able to
                access their account.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeBanModal()"
                    class="flex-1 px-4 py-2 border border-[#4E3B46] text-[#CFCBCA] rounded-lg hover:bg-[#383537] transition font-medium">
                    Cancel
                </button>
                <button type="button" onclick="submitBanForm()"
                    class="flex-1 px-4 py-2 text-white rounded-lg transition font-medium" style="background-color: #A0717F;"
                    onmouseover="this.style.backgroundColor='#8F6470'" onmouseout="this.style.backgroundColor='#A0717F'">
                    Ban User
                </button>
            </div>
        </div>
    </div>

    <script>
        function openBanModal() {
            document.getElementById('banModal').classList.remove('hidden');
        }

        function closeBanModal(event) {
            // Allow closing by clicking outside the modal
            if (!event || event.target.id === 'banModal') {
                document.getElementById('banModal').classList.add('hidden');
            }
        }

        function submitBanForm() {
            document.getElementById('banForm').submit();
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeBanModal();
            }
        });
    </script>

    <!-- Role-Based Sections -->
    @if ($user->role->slug === 'owner')
        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <h3 class="text-sm font-semibold text-[#EAD3CD] uppercase tracking-wider mb-4">Owned Hotels</h3>
            @if ($hotelInfo && $hotelInfo->count() > 0)
                <div class="space-y-3">
                    @foreach ($hotelInfo as $hotel)
                        <div
                            class="border border-[#4E3B46] rounded p-4 hover:bg-[#2A2729] transition flex justify-between items-start">
                            <div>
                                <p class="font-medium text-[#EAD3CD]">{{ $hotel->name }}</p>
                                <p class="text-xs text-[#CFCBCA] mt-2">{{ $hotel->rooms->count() }} rooms @if ($hotel->verified)
                                        • Verified
                                    @else
                                        • Pending
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('admin.hotels.show', $hotel) }}"
                                class="text-xs font-medium text-[#A0717F] hover:underline">
                                View
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-[#CFCBCA]">No hotels owned yet</p>
            @endif
        </div>
    @elseif ($user->role->slug === 'receptionist' || $user->role->slug === 'cleaner' || $user->role->slug === 'inspector')
        <div class="border border-[#4E3B46] rounded-lg p-6 bg-[#383537]">
            <h3 class="text-sm font-semibold text-[#EAD3CD] uppercase tracking-wider mb-4">Hotel Assignment</h3>
            @if ($hotelInfo)
                <div class="border border-[#4E3B46] rounded p-4 bg-[#2A2729]">
                    <p class="font-medium text-[#EAD3CD]">{{ $hotelInfo->name }}</p>
                    <p class="text-xs text-[#CFCBCA] mt-2">{{ $hotelInfo->location }}</p>
                    <a href="{{ route('admin.hotels.show', $hotelInfo) }}"
                        class="text-xs font-medium text-[#A0717F] hover:underline mt-3 inline-block">
                        View Hotel
                    </a>
                </div>
            @else
                <p class="text-sm text-[#CFCBCA]">Not assigned to any hotel</p>
            @endif
        </div>
    @endif
@endsection




