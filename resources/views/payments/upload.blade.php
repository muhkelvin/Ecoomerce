{{-- payment/upload.blade.php --}}
@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    <div class="max-w-2xl mx-auto px-6">
        <div class="bg-white rounded-xl shadow-sm p-8">
            <h1 class="font-playfair text-2xl text-deep-navy mb-6">Upload Bukti Pembayaran</h1>

            <form action="{{ route('payment.upload.process', $payment->order_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-slate-gray text-sm mb-2">Pilih File</label>
                    <div class="relative border-2 border-soft-beige rounded-xl p-4 hover:border-dusty-rose transition-colors">
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-center">
                            <div class="mb-2 text-dusty-rose">
                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-slate-gray text-sm">Klik untuk upload bukti transfer</p>
                            <p class="text-xs text-slate-gray/60 mt-1">Format: JPG, PNG (Maks. 5MB)</p>
                        </div>
                    </div>
                    @error('payment_proof')
                    <p class="text-terracotta text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-3 bg-[#001f3f] text-white rounded hover:bg-[#FFD700] transition-colors font-medium">
                    Kirim Bukti
                </button>
            </form>
        </div>
    </div>
@endsection
