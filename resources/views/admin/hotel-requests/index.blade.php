@extends('layouts.app')

@section('content')
    <!-- Background Container -->
    <div style="background-color: transparent; min-height: 100vh;">
        <!-- Page Header -->
        <div class="max-w-7xl mx-auto px-4 pt-8 pb-8">
            <h1 class="text-3xl font-semibold text-[#EAD3CD]">Hotel Requests</h1>
            <p class="text-sm mt-2 text-[#CFCBCA]">Review new hotel submissions</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 pb-12">

            @if (session('success'))
                <div class="mb-6 px-4 py-3 border rounded-lg text-sm"
                    style="background-color: rgba(160, 113, 127, 0.1); border-color: #4E3B46; color: #A0717F;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-4 py-3 border rounded-lg text-sm"
                    style="background-color: rgba(255, 142, 139, 0.1); border-color: #4E3B46; color: #ff8e8b;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="mb-6 flex gap-4 border-b border-[#4E3B46]">
                <button onclick="switchTab('pending')"
                    class="tab-btn active px-4 py-2 font-medium text-[#EAD3CD] border-b-2 border-[#A0717F] -mb-1 text-sm">
                    Pending <span class="text-xs">({{ $pendingRequests->total() }})</span>
                </button>
                <button onclick="switchTab('approved')"
                    class="tab-btn px-4 py-2 font-medium text-[#CFCBCA] hover:text-[#EAD3CD] border-b-2 border-transparent -mb-1 text-sm">
                    Approved <span class="text-xs">({{ $approvedRequests->total() }})</span>
                </button>
                <button onclick="switchTab('rejected')"
                    class="tab-btn px-4 py-2 font-medium text-[#CFCBCA] hover:text-[#EAD3CD] border-b-2 border-transparent -mb-1 text-sm">
                    Rejected <span class="text-xs">({{ $rejectedRequests->total() }})</span>
                </button>
            </div>

            <!-- Pending Tab -->
            <div id="pending-tab" class="tab-content">
                @if ($pendingRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach ($pendingRequests as $request)
                            <div
                                class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                                <div class="grid md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2">
                                        <h3 class="font-semibold text-[#EAD3CD] text-lg">{{ $request->name }}</h3>
                                        <p class="text-xs text-[#CFCBCA] mt-1">From <span
                                                class="font-medium text-[#EAD3CD]">{{ $request->owner->name }}</span></p>

                                        <div class="grid grid-cols-2 gap-4 mt-4">
                                            <div class="border-t border-[#4E3B46] pt-3">
                                                <p class="text-xs text-[#CFCBCA] font-medium">Location</p>
                                                <p class="text-sm text-[#EAD3CD] mt-1">{{ $request->address }}</p>
                                            </div>
                                            <div class="border-t border-[#4E3B46] pt-3">
                                                <p class="text-xs text-[#CFCBCA] font-medium">Contact</p>
                                                <p class="text-sm text-[#EAD3CD] mt-1">{{ $request->email }}</p>
                                            </div>
                                        </div>

                                        @if ($request->description)
                                            <div class="border-t border-[#4E3B46] pt-3 mt-4">
                                                <p class="text-xs text-[#CFCBCA] font-medium">Description</p>
                                                <p class="text-sm text-[#EAD3CD] mt-1">{{ $request->description }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <button
                                            onclick="showApproveModal({{ $request->id }}, '{{ addslashes($request->name) }}')"
                                            class="px-4 py-2 rounded-lg font-medium text-white text-sm transition-colors"
                                            style="background-color: #A0717F;"
                                            onmouseover="this.style.backgroundColor='#8F6470'"
                                            onmouseout="this.style.backgroundColor='#A0717F'">
                                            Approve
                                        </button>
                                        <button
                                            onclick="showRejectModal({{ $request->id }}, '{{ addslashes($request->name) }}')"
                                            class="px-4 py-2 rounded-lg font-medium text-[#CFCBCA] border border-[#4E3B46] text-sm hover:bg-[#2A2729] transition">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $pendingRequests->links() }}</div>
                @else
                    <div class="bg-[#383537] rounded-2xl p-12 text-center border border-transparent shadow-sm">
                        <p class="text-[#EAD3CD] font-semibold">No pending requests</p>
                        <p class="text-xs text-[#CFCBCA] mt-1">All caught up!</p>
                    </div>
                @endif
            </div>

            <!-- Approved Tab -->
            <div id="approved-tab" class="tab-content hidden">
                @if ($approvedRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach ($approvedRequests as $request)
                            <div
                                class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-[#EAD3CD]">{{ $request->name }}</h3>
                                        <p class="text-xs mt-1" style="color: #A0717F;">Approved by
                                            <span class="text-[#EAD3CD]">{{ $request->reviewer->name }}</span>
                                            on
                                            {{ $request->reviewed_at->format('M d, Y') }}
                                        </p>
                                        @if ($request->admin_notes)
                                            <div class="border-t mt-3 pt-3" style="border-color: #4E3B46;">
                                                <p class="text-xs text-[#CFCBCA] font-medium">Notes</p>
                                                <p class="text-sm text-[#EAD3CD] mt-1">{{ $request->admin_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-xs px-2.5 py-1 rounded font-medium"
                                        style="background-color: #2A2729; color: #A0717F;">Approved</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $approvedRequests->links() }}</div>
                @else
                    <div class="bg-[#383537] rounded-2xl p-12 text-center border border-transparent shadow-sm">
                        <p class="text-[#CFCBCA] font-semibold">No approved requests</p>
                    </div>
                @endif
            </div>

            <!-- Rejected Tab -->
            <div id="rejected-tab" class="tab-content hidden">
                @if ($rejectedRequests->count() > 0)
                    <div class="space-y-4">
                        @foreach ($rejectedRequests as $request)
                            <div
                                class="bg-[#383537] rounded-2xl p-8 border border-transparent shadow-sm hover:shadow-lg hover:border-[#4E3B46] transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-[#EAD3CD]">{{ $request->name }}</h3>
                                        <p class="text-xs mt-1" style="color: #ff8e8b;">Rejected by
                                            <span class="text-[#EAD3CD]">{{ $request->reviewer->name }}</span> on
                                            {{ $request->reviewed_at->format('M d, Y') }}
                                        </p>
                                        @if ($request->admin_notes)
                                            <div class="border-t mt-3 pt-3" style="border-color: #4E3B46;">
                                                <p class="text-xs text-[#CFCBCA] font-medium">Reason</p>
                                                <p class="text-sm text-[#EAD3CD] mt-1">{{ $request->admin_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-xs px-2.5 py-1 rounded font-medium"
                                        style="background-color: rgba(255, 142, 139, 0.1); color: #ff8e8b;">Rejected</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $rejectedRequests->links() }}</div>
                @else
                    <div class="bg-[#383537] rounded-2xl p-12 text-center border border-transparent shadow-sm">
                        <p class="text-[#CFCBCA] font-semibold">No rejected requests</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
                el.classList.remove('!border-[#A0717F]', '!text-[#EAD3CD]');
                el.classList.add('border-transparent', 'text-[#CFCBCA]');
            });

            document.getElementById(tab + '-tab').classList.remove('hidden');
            event.target.classList.add('active');
            event.target.classList.add('!border-[#A0717F]', '!text-[#EAD3CD]');
            event.target.classList.remove('border-transparent', 'text-[#CFCBCA]');
        }

        function showApproveModal(id, name) {
            const modal = document.getElementById('approve-modal');
            const input = document.getElementById('approve-notes');
            const titleEl = document.getElementById('approve-modal-title');
            const currentId = document.getElementById('approve-modal-id');

            titleEl.textContent = `Approve "${name}"?`;
            currentId.value = id;
            input.value = '';
            modal.classList.remove('hidden');
            input.focus();
        }

        function closeApproveModal() {
            document.getElementById('approve-modal').classList.add('hidden');
        }

        function submitApprove() {
            const id = document.getElementById('approve-modal-id').value;
            const notes = document.getElementById('approve-notes').value;
            const form = document.getElementById('approve-form-' + id);
            document.getElementById('approve-notes-input-' + id).value = notes;
            form.submit();
        }

        function showRejectModal(id, name) {
            const modal = document.getElementById('reject-modal');
            const input = document.getElementById('reject-notes');
            const titleEl = document.getElementById('reject-modal-title');
            const currentId = document.getElementById('reject-modal-id');

            titleEl.textContent = `Reject "${name}"?`;
            currentId.value = id;
            input.value = '';
            modal.classList.remove('hidden');
            input.focus();
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        function submitReject() {
            const id = document.getElementById('reject-modal-id').value;
            const notes = document.getElementById('reject-notes').value.trim();
            const errorEl = document.getElementById('reject-error');

            if (!notes) {
                errorEl.textContent = 'Rejection reason is required.';
                errorEl.classList.remove('hidden');
                return;
            }

            if (notes.length < 10) {
                errorEl.textContent = 'Rejection reason must be at least 10 characters.';
                errorEl.classList.remove('hidden');
                return;
            }

            errorEl.classList.add('hidden');
            const form = document.getElementById('reject-form-' + id);
            document.getElementById('reject-reason-' + id).value = notes;
            form.submit();
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const approveModal = document.getElementById('approve-modal');
            const rejectModal = document.getElementById('reject-modal');

            if (event.target === approveModal) {
                closeApproveModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        });

        // Allow Enter key to submit
        document.addEventListener('keydown', function(event) {
            const approveModal = document.getElementById('approve-modal');
            const rejectModal = document.getElementById('reject-modal');

            if (event.key === 'Enter') {
                if (!approveModal.classList.contains('hidden')) {
                    submitApprove();
                } else if (!rejectModal.classList.contains('hidden')) {
                    submitReject();
                }
            }

            if (event.key === 'Escape') {
                closeApproveModal();
                closeRejectModal();
            }
        });
    </script>

    <!-- Approve Modal -->
    <div id="approve-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-[#383537] rounded-lg p-6 max-w-md w-full mx-4 border border-[#4E3B46]">
            <h3 id="approve-modal-title" class="text-lg font-semibold text-[#EAD3CD] mb-4">Approve Hotel Request?</h3>
            <div class="mb-6">
                <label class="block text-sm text-[#CFCBCA] font-medium mb-2">Notes (Optional)</label>
                <textarea id="approve-notes"
                    class="w-full px-3 py-2 rounded border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] placeholder-[#8B8782] focus:outline-none focus:border-[#A0717F]"
                    rows="3" placeholder="Add any approval notes..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeApproveModal()"
                    class="flex-1 px-4 py-2 rounded-lg border border-[#4E3B46] text-[#CFCBCA] hover:bg-[#2A2729] transition">Cancel</button>
                <button type="button" onclick="submitApprove()"
                    class="flex-1 px-4 py-2 rounded-lg text-white transition-colors" style="background-color: #A0717F;"
                    onmouseover="this.style.backgroundColor='#8F6470'"
                    onmouseout="this.style.backgroundColor='#A0717F'">Approve</button>
            </div>
            <input type="hidden" id="approve-modal-id">
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-[#383537] rounded-lg p-6 max-w-md w-full mx-4 border border-[#4E3B46]">
            <h3 id="reject-modal-title" class="text-lg font-semibold text-[#EAD3CD] mb-4">Reject Hotel Request?</h3>
            <div class="mb-6">
                <label class="block text-sm text-[#CFCBCA] font-medium mb-2">Reason (Required - min 10 characters)</label>
                <textarea id="reject-notes"
                    class="w-full px-3 py-2 rounded border border-[#4E3B46] bg-[#2A2729] text-[#EAD3CD] placeholder-[#8B8782] focus:outline-none focus:border-[#A0717F]"
                    rows="3" placeholder="Enter rejection reason..."
                    oninput="document.getElementById('reject-error').classList.add('hidden')"></textarea>
                <div id="reject-error" class="hidden text-sm text-red-400 mt-2"></div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2 rounded-lg border border-[#4E3B46] text-[#CFCBCA] hover:bg-[#2A2729] transition">Cancel</button>
                <button type="button" onclick="submitReject()"
                    class="flex-1 px-4 py-2 rounded-lg text-white transition-colors"
                    style="background-color: #ff8e8b; border: 1px solid #ff8e8b;"
                    onmouseover="this.style.backgroundColor='#ff7a76'"
                    onmouseout="this.style.backgroundColor='#ff8e8b'">Reject</button>
            </div>
            <input type="hidden" id="reject-modal-id">
        </div>
    </div>

    <!-- Hidden Forms for Actions -->
    @foreach ($pendingRequests as $request)
        <form id="approve-form-{{ $request->id }}" action="{{ route('admin.hotel-requests.approve', $request) }}"
            method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="admin_notes" id="approve-notes-input-{{ $request->id }}">
        </form>
        <form id="reject-form-{{ $request->id }}" action="{{ route('admin.hotel-requests.reject', $request) }}"
            method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="admin_notes" id="reject-reason-{{ $request->id }}">
        </form>
    @endforeach
@endsection
