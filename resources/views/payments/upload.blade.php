{{-- payment/upload.blade.php --}}
@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    <div class="max-w-2xl mx-auto px-6 py-8">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h1 class="font-playfair text-2xl text-deep-navy mb-6">Upload Bukti Pembayaran</h1>

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-terracotta p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-terracotta" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-terracotta">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6 bg-soft-beige/30 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-dusty-rose" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-slate-gray">Informasi Pembayaran</h3>
                        <div class="mt-2 text-sm text-slate-gray">
                            <p>Order ID: <span class="font-medium">{{ $payment->order_id }}</span></p>
                            <p>Total: <span class="font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('payment.upload.process', $payment->order_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="payment_proof" class="block text-slate-gray text-sm mb-2">Bukti Pembayaran</label>
                    <div class="relative border-2 border-soft-beige rounded-xl p-4 hover:border-dusty-rose transition-colors">
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)">
                        <div class="text-center" id="upload-prompt">
                            <div class="mb-2 text-dusty-rose">
                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <p class="text-slate-gray text-sm">Klik untuk upload bukti transfer</p>
                            <p class="text-xs text-slate-gray/60 mt-1">Format: JPG, PNG (Maks. 5MB)</p>
                        </div>
                        <div id="image-preview" class="hidden flex-col items-center mt-2">
                            <img src="" alt="Preview" class="max-h-64 max-w-full rounded-lg object-contain" id="preview-img">
                            <p class="text-sm text-slate-gray mt-2" id="file-name"></p>
                            <button type="button" onclick="resetImage()" class="mt-2 text-terracotta text-xs py-1 px-3 border border-terracotta rounded-full hover:bg-terracotta/10 transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @error('payment_proof')
                    <p class="text-terracotta text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('orders.show', $payment->order_id) }}"
                       class="flex-1 py-3 bg-white border border-slate-gray/20 text-slate-gray text-center rounded hover:bg-slate-gray/5 transition-colors font-medium">
                        Kembali
                    </a>
                    <button type="submit"
                            class="flex-1 py-3 bg-[#001f3f] text-white rounded hover:bg-[#001f3f]/90 transition-colors font-medium">
                        Kirim Bukti
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const uploadPrompt = document.getElementById('upload-prompt');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const fileName = document.getElementById('file-name');

            if (input.files && input.files[0]) {
                // Validasi ukuran file (max 5MB)
                const fileSize = input.files[0].size / 1024 / 1024; // dalam MB
                if (fileSize > 5) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    resetImage();
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    fileName.textContent = input.files[0].name + ' (' + fileSize.toFixed(2) + ' MB)';

                    uploadPrompt.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                    imagePreview.classList.add('flex');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetImage() {
            const uploadPrompt = document.getElementById('upload-prompt');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const fileInput = document.getElementById('payment_proof');

            fileInput.value = '';
            previewImg.src = '';

            uploadPrompt.classList.remove('hidden');
            imagePreview.classList.add('hidden');
            imagePreview.classList.remove('flex');
        }
    </script>
@endsection
