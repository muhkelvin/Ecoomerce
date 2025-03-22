<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Menampilkan form upload bukti pembayaran.
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function showUploadForm($orderId)
    {
        // Cari payment berdasarkan order_id
        $payment = Payment::where('order_id', $orderId)->firstOrFail();

        return view('payments.upload', compact('payment'));
    }

    /**
     * Proses upload bukti pembayaran.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadProof(Request $request, $orderId)
    {
        // Validasi file input
        $request->validate([
            'payment_proof' => 'required|image|max:5120', // maksimal 5MB sesuai dengan yang tertulis di form
        ]);

        $payment = Payment::where('order_id', $orderId)->firstOrFail();

        // Hapus bukti pembayaran lama jika ada
        if ($payment->payment_proof && Storage::disk('public')->exists($payment->payment_proof)) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        // Simpan file ke storage (disk 'public')
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update record payment: simpan path gambar dan ubah status menjadi processing (menunggu konfirmasi)
        $payment->update([
            'payment_proof'  => $path,
            'payment_status' => 'processing' // Menggunakan 'processing' sebagai pengganti 'waiting confirmation'
        ]);

        return redirect()->route('orders.show', $orderId)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }
}
