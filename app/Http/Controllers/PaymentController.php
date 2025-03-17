<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

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
            'payment_proof' => 'required|image|max:2048', // maksimal 2MB
        ]);

        $payment = Payment::where('order_id', $orderId)->firstOrFail();

        // Simpan file ke storage (misalnya pada disk 'public')
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update record payment: simpan path gambar dan ubah status menjadi waiting confirmation
        $payment->update([
            'payment_proof'  => $path,
            'payment_status' => 'waiting confirmation'
        ]);

        return redirect()->route('orders.show', $orderId)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }
}
