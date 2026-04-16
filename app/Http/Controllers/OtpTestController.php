<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;

class OtpTestController extends Controller
{
    /**
     * Affiche les 10 derniers OTP
     */
    public function index()
    {
        $otps = OtpVerification::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('otp.index', compact('otps'));
    }
}
