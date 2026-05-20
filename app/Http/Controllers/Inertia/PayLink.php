<?php

namespace App\Http\Controllers\Inertia;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentLinkResource;
use App\Services\Merchant\PaymentLinkService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayLink extends Controller
{

    public function __construct(
        private PaymentLinkService $paymentLinkService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(string $ref)
    {
        $paymentLink = $this->paymentLinkService->getByUuid($ref);

        $validation = $this->paymentLinkService->validateForPayment($paymentLink);

        return Inertia::render('Checkout/Index', [
            'is_valid' => $validation->isValid,
            'validation_message' => $validation->message,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
