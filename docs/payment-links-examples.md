# Exemples d'utilisation - Liens de paiement sécurisés

## 1. Générer un lien de paiement (API)

### Request
```bash
curl -X POST http://localhost:8000/api/merchant/payments/initiate \
  -H "Authorization: Bearer {api_key}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 50000,
    "currency": "XOF",
    "customer_email": "client@example.com",
    "customer_name": "John Doe",
    "customer_phone": "+221701234567",
    "metadata": {
      "order_id": "ORDER-123",
      "description": "Payment for services"
    }
  }'
```

### Response
```json
{
  "data": {
    "uuid": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
    "amount": 50000,
    "currency": "XOF",
    "status": "pending",
    "customer_email": "client@example.com",
    "customer_name": "John Doe",
    "payment_link_id": 123,
    "created_at": "2026-05-05T10:30:00Z",
    "checkout_link": "https://checkout.zilopay.com/merchant/pay/link-uuid-abc?expires=1735689600&signature=abcd1234..."
  },
  "message": "Payment initiated successfully"
}
```

## 2. Récupérer un lien de paiement par UUID

### Request
```bash
curl -X GET http://localhost:8000/api/merchant/payments/a1b2c3d4-e5f6-7890-abcd-ef1234567890 \
  -H "Authorization: Bearer {api_key}"
```

### Response
```json
{
  "data": {
    "uuid": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
    "amount": 50000,
    "currency": "XOF",
    "status": "pending",
    "customer_email": "client@example.com",
    "checkout_link": "https://checkout.zilopay.com/merchant/pay/link-uuid-abc?expires=1735689600&signature=..."
  }
}
```

## 3. Accéder au lien de paiement (Frontend)

### Lien complet
```
https://checkout.zilopay.com/merchant/pay/link-uuid-abc?expires=1735689600&signature=abc123def456
```

### Flux:
1. Client reçoit le lien via email ou l'accède directement
2. Navigateur accède à l'URL
3. Middleware valide la signature
4. Page de checkout s'affiche
5. Client saisit les informations de paiement
6. Paiement est traité

## 4. Utilisation en PHP/Laravel

### Générer un lien dans le code
```php
<?php

use App\Services\Merchant\MerchantPaymentService;
use App\Models\MerchantTransaction;

$paymentService = app(MerchantPaymentService::class);
$transaction = MerchantTransaction::find(1);

// Récupérer le lien sécurisé
$checkoutLink = $paymentService->getCheckoutLink($transaction);

echo "Lien de paiement: " . $checkoutLink;
```

### Envoyer le lien par email
```php
<?php

use App\Models\MerchantTransaction;
use Illuminate\Support\Facades\Mail;

$transaction = MerchantTransaction::with('paymentLink', 'merchant')->find(1);
$paymentService = app(\App\Services\Merchant\MerchantPaymentService::class);
$checkoutLink = $paymentService->getCheckoutLink($transaction);

// Utiliser le lien dans un mailable
Mail::send('emails.payment-link', [
    'checkoutLink' => $checkoutLink,
    'amount' => $transaction->amount,
    'customer_name' => $transaction->customer_name
]);
```

## 5. Validation côté Frontend (JavaScript)

### Vérifier la signature avant d'accéder au lien
```javascript
// Extraire les paramètres de l'URL
const url = new URL(window.location.href);
const signature = url.searchParams.get('signature');
const expires = url.searchParams.get('expires');

// Vérifier l'expiration
const currentTime = Math.floor(Date.now() / 1000);
if (currentTime > parseInt(expires)) {
  console.error('Lien expiré');
  // Afficher un message d'erreur
}

// La signature est validée côté serveur
// Si elle est invalide, on reçoit une 403
```

## 6. Gestion des erreurs

### Lien expiré
```bash
curl https://checkout.zilopay.com/merchant/pay/uuid?expires=1234567890&signature=old
# Response: 403 Forbidden
```

### Lien modifié
```bash
curl https://checkout.zilopay.com/merchant/pay/uuid?amount=999&signature=abc123
# Response: 403 Forbidden (signature invalide)
```

### Configuration stricte activée sans signature
```bash
curl https://checkout.zilopay.com/merchant/pay/uuid
# Response: 403 Forbidden (signature manquante)
```

## 7. Configuration avancée

### Mode développement (signatures optionnelles)
```env
# .env
CHECKOUT_REQUIRE_SIGNED_URL=false
CHECKOUT_DOMAIN=localhost:3000
CHECKOUT_PROTOCOL=http
```

### Mode production (signatures obligatoires)
```env
# .env
CHECKOUT_REQUIRE_SIGNED_URL=true
CHECKOUT_DOMAIN=checkout.zilopay.com
CHECKOUT_PROTOCOL=https
```

## 8. Webhook pour les mises à jour de paiement

Après le paiement, un webhook est déclenché:

```bash
POST /webhooks/payment-completed

{
  "event": "payment.completed",
  "transaction_uuid": "a1b2c3d4-e5f6-7890-abcd-ef1234567890",
  "amount": 50000,
  "currency": "XOF",
  "status": "completed",
  "merchant_id": 123,
  "timestamp": 1715007000
}
```

## 9. Tests avec Postman

### Collection d'exemples
```json
{
  "info": {
    "name": "ZiloPay Secure Payment Links",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Initiate Payment",
      "request": {
        "method": "POST",
        "header": [
          {"key": "Authorization", "value": "Bearer {{api_key}}"},
          {"key": "Content-Type", "value": "application/json"}
        ],
        "body": {
          "mode": "raw",
          "raw": "{\"amount\": 50000, \"currency\": \"XOF\"}"
        },
        "url": {"raw": "{{base_url}}/api/merchant/payments/initiate"}
      }
    }
  ]
}
```

## 10. Cas d'usage courants

### Paiement d'une facture
```php
$invoice = Invoice::find(1);
$merchant = $invoice->merchant;

$transaction = $paymentService->initiate($merchant, [
    'amount' => $invoice->amount,
    'currency' => 'XOF',
    'customer_email' => $invoice->customer_email,
    'metadata' => ['invoice_id' => $invoice->id]
]);

$checkoutLink = $paymentService->getCheckoutLink($transaction);

// Envoyer le lien au client
$invoice->customer->sendPaymentLink($checkoutLink);
```

### Paiement d'abonnement
```php
$subscription = Subscription::find(1);

$transaction = $paymentService->initiate($subscription->merchant, [
    'amount' => $subscription->amount,
    'currency' => $subscription->currency,
    'customer_email' => $subscription->customer_email,
    'metadata' => ['subscription_id' => $subscription->id]
]);

$checkoutLink = $paymentService->getCheckoutLink($transaction);
```

### Panier de boutique
```php
$cart = Cart::find(1);

$transaction = $paymentService->initiate($cart->shop->merchant, [
    'amount' => $cart->total,
    'currency' => 'XOF',
    'customer_email' => $cart->customer_email,
    'customer_name' => $cart->customer_name,
    'metadata' => [
        'cart_id' => $cart->id,
        'items_count' => $cart->items()->count()
    ]
]);

$checkoutLink = $paymentService->getCheckoutLink($transaction);
```

## Notes de sécurité

✅ Toujours utiliser HTTPS en production
✅ Ne pas partager la clé APP_KEY
✅ Valider les montants côté serveur
✅ Vérifier les permissions des merchants
✅ Implémenter le rate limiting
✅ Monitorer les tentatives d'accès non autorisé
