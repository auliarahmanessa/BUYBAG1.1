<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function showCheckout()
    {
        return view('checkout');
    }

    public function processCheckout(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'payment_method' => 'required',
        ]);

        Payment::create($validated);

        // Proses pembayaran (contoh logika sederhana)
        // Simpan data ke database atau panggil API payment gateway
        // Contoh: PaymentGateway::charge($validated);

        return redirect()->route('checkout')->with('success');
    }
}
