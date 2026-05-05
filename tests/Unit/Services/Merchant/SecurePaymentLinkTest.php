<?php

namespace Tests\Unit\Services\Merchant;

use App\Models\Merchant;
use App\Models\MerchantTransaction;
use App\Models\PaymentLinks;
use App\Services\Merchant\MerchantPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurePaymentLinkTest extends TestCase
{
    use RefreshDatabase;

    private MerchantPaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentService = app(MerchantPaymentService::class);
    }

    public function test_generates_secure_checkout_link(): void
    {
        $merchant = Merchant::factory()->create();
        $paymentLink = PaymentLinks::factory()->create([
            'merchant_id' => $merchant->id,
        ]);

        $transaction = MerchantTransaction::factory()->create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink->id,
        ]);

        $checkoutLink = $this->paymentService->getCheckoutLink($transaction);

        $this->assertNotEmpty($checkoutLink);
        $this->assertStringContainsString('checkout.zilopay.com', $checkoutLink);
        $this->assertStringContainsString('/merchant/pay/', $checkoutLink);
        $this->assertStringContainsString($paymentLink->uuid, $checkoutLink);
    }

    public function test_secure_checkout_link_contains_signature(): void
    {
        $merchant = Merchant::factory()->create();
        $paymentLink = PaymentLinks::factory()->create([
            'merchant_id' => $merchant->id,
        ]);

        $transaction = MerchantTransaction::factory()->create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink->id,
        ]);

        $checkoutLink = $this->paymentService->getCheckoutLink($transaction);

        // Check for signed URL parameters
        $this->assertStringContainsString('signature=', $checkoutLink);
        $this->assertStringContainsString('expires=', $checkoutLink);
    }

    public function test_secure_checkout_link_respects_config(): void
    {
        config(['services.checkout.domain' => 'custom-checkout.example.com']);
        config(['services.checkout.protocol' => 'http']);

        $merchant = Merchant::factory()->create();
        $paymentLink = PaymentLinks::factory()->create([
            'merchant_id' => $merchant->id,
        ]);

        $transaction = MerchantTransaction::factory()->create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink->id,
        ]);

        $checkoutLink = $this->paymentService->getCheckoutLink($transaction);

        $this->assertStringContainsString('http://custom-checkout.example.com', $checkoutLink);
    }

    public function test_secure_checkout_link_expires_in_thirty_days(): void
    {
        $merchant = Merchant::factory()->create();
        $paymentLink = PaymentLinks::factory()->create([
            'merchant_id' => $merchant->id,
        ]);

        $transaction = MerchantTransaction::factory()->create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink->id,
        ]);

        $checkoutLink = $this->paymentService->getCheckoutLink($transaction);

        // Extract the expires parameter
        parse_str(parse_url($checkoutLink, PHP_URL_QUERY), $params);

        $this->assertArrayHasKey('expires', $params);

        $expiresTimestamp = (int) $params['expires'];
        $expectedTimestamp = now()->addDays(30)->timestamp;

        // Allow 5 seconds difference due to execution time
        $this->assertLessThanOrEqual(5, abs($expiresTimestamp - $expectedTimestamp));
    }

    public function test_different_transactions_have_different_links(): void
    {
        $merchant = Merchant::factory()->create();
        $paymentLink1 = PaymentLinks::factory()->create(['merchant_id' => $merchant->id]);
        $paymentLink2 = PaymentLinks::factory()->create(['merchant_id' => $merchant->id]);

        $transaction1 = MerchantTransaction::factory()->create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink1->id,
        ]);

        $transaction2 = MerchantTransaction::factory()->create([
            'merchant_id' => $merchant->id,
            'payment_link_id' => $paymentLink2->id,
        ]);

        $link1 = $this->paymentService->getCheckoutLink($transaction1);
        $link2 = $this->paymentService->getCheckoutLink($transaction2);

        $this->assertNotEquals($link1, $link2);
        $this->assertStringContainsString($paymentLink1->uuid, $link1);
        $this->assertStringContainsString($paymentLink2->uuid, $link2);
    }
}
