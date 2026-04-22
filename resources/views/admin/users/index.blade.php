@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <!-- Page Header -->
        <div class="mb-8 border-b border-[#4E3B46] rounded-t-lg"
            style="background: linear-gradient(180deg, rgba(42, 39, 41, 0.5) 0%, transparent 100%);">
            <div class="py-8 text-center">
                <h1 class="text-3xl font-semibold text-[#EAD3CD]">Users</h1>
                <p class="text-sm mt-2 text-[#CFCBCA]">Manage system users and permissions</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-green-900/50 border border-green-800 rounded-lg text-green-300 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search -->
        <div class="mb-6 border border-[#4E3B46] rounded-lg p-4 bg-[#2A2729]">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3 flex-wrap items-center"
                id="search-form">
                <input type="text" name="search" placeholder="Search by name, email, or role..."
                    value="{{ request('search') }}"
                    class="px-3 py-2 text-sm border bg-[#383537] text-[#EAD3CD] border-[#4E3B46] rounded-lg focus:outline-none flex-1 min-w-[300px]" />

                <select name="role"
                    class="px-3 py-2 text-sm border bg-[#383537] text-[#EAD3CD] border-[#4E3B46] rounded-lg focus:outline-none">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->slug }}" @if (request('role') === $role->slug) selected @endif>
                            {{ ucfirst($role->slug) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                    style="background-color: #A0717F;" onmouseover="this.style.backgroundColor='#8F6470'"
                    onmouseout="this.style.backgroundColor='#A0717F'">
                    Search
                </button>
                @if (request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 text-sm border border-[#4E3B46] rounded-lg text-[#CFCBCA] hover:bg-[#383537] transition">
                        Clear
                    </a>
                @endif
            </form>

            <script>
                document.getElementById('search-form').addEventListener('submit', function(e) {
                    const roleField = this.querySelector('select[name="role"]');
                    // If role is empty, remove it from the form data
                    if (!roleField.value || roleField.value === '') {
                        roleField.removeAttribute('name');
                    }
                });
            </script>
        </div>

        <!-- Users Table -->
        <div class="border border-[#4E3B46] rounded-lg overflow-hidden bg-[#383537]">
            @if ($users->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#2A2729] border-b border-[#4E3B46]">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#CFCBCA] uppercase tracking-wide">
                                Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#CFCBCA] uppercase tracking-wide">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#CFCBCA] uppercase tracking-wide">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#CFCBCA] uppercase tracking-wide">
                                Phone
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#CFCBCA] uppercase tracking-wide">
                                Joined
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#CFCBCA] uppercase tracking-wide">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#4E3B46]">
                        @foreach ($users as $user)
                            <tr class="hover:bg-[#2A2729] transition">
                                <td class="px-6 py-3 font-medium text-[#EAD3CD]">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="hover:text-[#A0717F] transition">
                                        {{ $user->name }}
                                        @if ($user->banned_at)
                                            <span class="ml-2 text-xs text-red-500 font-semibold">(Banned)</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-[#CFCBCA]">{{ $user->email }}</td>
                                <td class="px-6 py-3">
                                    <span class="text-xs px-2.5 py-1 rounded-full font-medium text-[#CFCBCA] bg-[#2A2729]">
                                        {{ ucfirst($user->role->slug) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-[#CFCBCA]">{{ $user->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-[#CFCBCA] text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-sm font-medium text-[#A0717F] hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-[#4E3B46] bg-[#2A2729]">
                    <style>
                        .pagination {
                            display: flex;
                            gap: 0.25rem;
                            justify-content: center;
                            flex-wrap: wrap;
                        }

                        .pagination li {
                            list-style: none;
                        }

                        .pagination a,
                        .pagination span {
                            display: inline-block;
                            padding: 0.5rem 0.75rem;
                            font-size: 0.875rem;
                            border: 1px solid #4E3B46;
                            border-radius: 0.5rem;
                            transition: all 0.2s;
                            color: #CFCBCA;
                            text-decoration: none;
                        }

                        .pagination a:hover {
                            background-color: #383537;
                            color: #EAD3CD;
                            border-color: #4E3B46;
                        }

                        .pagination .active span {
                            background-color: #4E3B46;
                            color: #EAD3CD;
                            border-color: #4E3B46;
                        }

                        .pagination .disabled span {
                            opacity: 0.5;
                            cursor: not-allowed;
                        }
                    </style>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center text-[#CFCBCA]">
                    <p>No users found</p>
                </div>
            @endif
        </div>
    </div>
@endsection
