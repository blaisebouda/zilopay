<?php

declare(strict_types=1);

namespace App\Services\Merchant\Utils;

use App\Models\PaymentLink;

class PaymentLinkValidator
{
    private PaymentLink $paymentLink;

    public ?string $message = null;

    public bool $isValid = false;

    public function __construct(PaymentLink $paymentLink)
    {
        $this->paymentLink = $paymentLink;
        $this->validate();
    }

    public static function make(PaymentLink $paymentLink): self
    {
        return new self($paymentLink);
    }

    private function validate(): void
    {
        if (! $this->paymentLink->isActive()) {
            $this->message = 'Le lien de paiement n\'est pas actif.';
            $this->isValid = false;

            return;
        }

        if ($this->paymentLink->isExpired()) {
            $this->message = 'Le lien de paiement a expiré.';
            $this->isValid = false;

            return;
        }

        if ($this->paymentLink->hasReachedMaxUses()) {
            $this->message = 'Le lien de paiement a atteint le nombre d\'utilisations maximum.';
            $this->isValid = false;

            return;
        }

        $this->isValid = true;
    }
}
