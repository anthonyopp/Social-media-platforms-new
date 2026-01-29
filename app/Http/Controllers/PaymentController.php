<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function showFees()
    {
        return view('payment.index');
    }

    public function makePayment()
    {
        return back()->with('success', '支付成功！');
    }
}
