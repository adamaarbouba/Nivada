@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <x-breadcrumbs :links="[
            ['label' => 'My Bookings', 'url' => route('guest.bookings.index')],
            ['label' => 'Process Payment', 'url' => '#'],
        ]" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Payment Form --}}
            <div class="lg:col-span-2 space-y-8">
                @if($booking->remainingBalance() > 0)
                <div class="relative overflow-hidden rounded-2xl border border-[#4E3B46] bg-[#383537] shadow-xl">
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-[#A0717F] to-transparent opacity-50"></div>
                    
                    <div class="p-8">
                        <header class="mb-8">
                            <h2 class="text-2xl font-bold font-serif text-[#EAD3CD] mb-2 text-center">Complete Payment</h2>
                            <p class="text-[#CFCBCA] text-sm text-center">Enter your secure payment details below.</p>
                        </header>

                        @if (session('error'))
                            <div class="mb-6 p-4 bg-red-950/30 border border-red-900/50 rounded-xl text-red-400 text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('guest.payments.process', $booking) }}" class="space-y-6">
                            @csrf
                            
                            <div class="bg-[#2A2729] rounded-xl p-6 border border-[#4E3B46] space-y-6">
                                {{-- Amount Field --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-[#A0717F]">Payment Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#A0717F] font-bold text-xl">$</span>
                                        <input type="number" name="amount" step="0.01" value="{{ $booking->remainingBalance() }}" max="{{ $booking->remainingBalance() }}" required
                                            class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl pl-10 pr-5 py-4 text-2xl font-bold focus:outline-none focus:border-[#A0717F] transition-all">
                                    </div>
                                    <p class="text-[10px] text-[#CFCBCA] italic text-right">Remaining Balance: ${{ number_format($booking->remainingBalance(), 2) }}</p>
                                    @error('amount') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Card Details --}}
                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold uppercase tracking-wider text-[#CFCBCA]">Cardholder Name</label>
                                        <input type="text" name="cardholder_name" required value="{{ old('cardholder_name') }}"
                                            class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl px-5 py-3 focus:outline-none focus:border-[#A0717F] transition-all placeholder-[#4E3B46]"
                                            placeholder="James Sterling">
                                        @error('cardholder_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold uppercase tracking-wider text-[#CFCBCA]">Card Number</label>
                                        <div class="relative">
                                            <input type="text" name="card_number" maxlength="16" required
                                                class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl px-5 py-3 focus:outline-none focus:border-[#A0717F] transition-all placeholder-[#4E3B46]"
                                                placeholder="0000 0000 0000 0000">
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex gap-2">
                                                <x-icon name="credit-card" size="sm" class="text-[#4E3B46]" />
                                            </div>
                                        </div>
                                        @error('card_number') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-xs font-bold uppercase tracking-wider text-[#CFCBCA]">Expiry (MM/YY)</label>
                                            <input type="text" name="expiry_date" required placeholder="12/26" maxlength="5" value="{{ old('expiry_date') }}"
                                                class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl px-5 py-3 focus:outline-none focus:border-[#A0717F] transition-all placeholder-[#4E3B46]">
                                            @error('expiry_date') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-bold uppercase tracking-wider text-[#CFCBCA]">CVV</label>
                                            <input type="password" name="cvv" required placeholder="***" maxlength="3"
                                                class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl px-5 py-3 focus:outline-none focus:border-[#A0717F] transition-all placeholder-[#4E3B46]">
                                            @error('cvv') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#A0717F] hover:bg-[#8F6470] text-white font-bold uppercase tracking-widest text-xs py-5 rounded-xl shadow-xl transition-all flex items-center justify-center gap-3">
                                <x-icon name="checkmark" size="sm" />
                                Authorize Transaction
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="rounded-2xl border border-[#4E3B46] bg-[#383537] p-12 text-center space-y-4 shadow-xl">
                    <div class="mx-auto w-16 h-16 rounded-full bg-green-950/30 flex items-center justify-center text-green-500 mb-6">
                        <x-icon name="checkmark" size="lg" />
                    </div>
                    <h2 class="text-3xl font-bold font-serif text-[#EAD3CD]">Booking Fully Paid</h2>
                    <p class="text-[#CFCBCA] max-w-sm mx-auto">This reservation is secured. We look forward to your arrival at {{ $booking->hotel->name }}.</p>
                    <a href="{{ route('guest.bookings.index') }}" class="inline-block pt-6 text-[#A0717F] font-bold uppercase tracking-widest text-[10px] hover:text-[#EAD3CD] transition-colors">Return to Bookings</a>
                </div>
                @endif

                {{-- Refund Request Section --}}
                @if($booking->amountPaid() > 0)
                <div class="rounded-2xl border border-[#4E3B46] bg-[#2A2729] p-8 space-y-6 shadow-xl opacity-80 hover:opacity-100 transition-opacity">
                    <header class="flex items-center justify-between border-b border-[#4E3B46] pb-4">
                        <h3 class="text-lg font-bold font-serif text-[#EAD3CD]">Request a Refund</h3>
                        <x-icon name="sparkles" size="sm" class="text-[#A0717F]" />
                    </header>
                    
                    <p class="text-xs text-[#CFCBCA] leading-relaxed italic">
                        Refunds must be reviewed and approved by the hotel's curators. Please allow up to 48 hours for verification.
                    </p>

                    <form method="POST" action="{{ route('guest.refund-requests.store', $booking) }}" class="space-y-4">
                        @csrf
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-[#CFCBCA]">Amount to Refund</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#A0717F] font-bold">$</span>
                                    <input type="number" name="amount" step="0.01" value="{{ old('amount', $booking->amountPaid()) }}" max="{{ $booking->amountPaid() }}" required
                                        class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl pl-9 pr-5 py-3 focus:outline-none focus:border-[#A0717F] transition-all">
                                </div>
                                @error('amount') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-[#CFCBCA]">Reason for Request</label>
                                <textarea name="reason" rows="3" required minlength="10"
                                    class="w-full bg-[#383537] border border-[#4E3B46] text-[#EAD3CD] rounded-xl px-5 py-3 text-xs focus:outline-none focus:border-[#A0717F] transition-all placeholder-[#4E3B46] resize-none"
                                    placeholder="Please explain why you are requesting a refund...">{{ old('reason') }}</textarea>
                                @error('reason') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full border border-[#A0717F] text-[#A0717F] hover:bg-[#A0717F] hover:text-white font-bold uppercase tracking-widest text-[10px] py-4 rounded-xl transition-all">
                            Submit Refund Request
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- Right Column: Booking Summary --}}
            <div class="space-y-6">
                <div class="rounded-2xl border border-[#4E3B46] bg-[#2A2729] p-6 shadow-xl sticky top-8">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-[#A0717F] mb-6">Reservation Summary</h3>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-16 h-16 rounded-xl bg-[#383537] border border-[#4E3B46] flex items-center justify-center text-[#A0717F] shrink-0">
                                <x-icon name="building" size="md" />
                            </div>
                            <div>
                                <h4 class="text-[#EAD3CD] font-serif italic">{{ $booking->hotel->name }}</h4>
                                <p class="text-[10px] text-[#CFCBCA]">{{ $booking->hotel->city }}, {{ $booking->hotel->country }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-4 border-t border-[#4E3B46]">
                            <div>
                                <p class="text-[10px] uppercase text-[#4E3B46] font-bold tracking-tighter">Check-in</p>
                                <p class="text-sm text-[#EAD3CD]">{{ $booking->check_in_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-[#4E3B46] font-bold tracking-tighter">Check-out</p>
                                <p class="text-sm text-[#EAD3CD]">{{ $booking->check_out_date->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-[#4E3B46]">
                            <div class="flex justify-between text-xs">
                                <span class="text-[#CFCBCA]">Total Cost</span>
                                <span class="text-[#EAD3CD]">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-[#CFCBCA]">Deposits Paid</span>
                                <span class="text-green-500">-${{ number_format($booking->amountPaid(), 2) }}</span>
                            </div>
                            <div class="flex justify-between items-end pt-2">
                                <span class="text-xs font-bold uppercase tracking-widest text-[#A0717F]">Due Now</span>
                                <span class="text-2xl font-bold text-[#EAD3CD]">${{ number_format($booking->remainingBalance(), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
@endsection
