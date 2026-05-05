# Lien de Paiement Sécurisé - Documentation

## Vue d'ensemble

Cette implémentation génère des liens de paiement sécurisés qui redirigent vers un checkout (checkout.zilopay.com ou un domaine personnalisé).

## Architecture de sécurité

### 1. **URLs Signées (HMAC SHA256)**
- Chaque lien généré est signé avec une clé secrète Laravel
- La signature est valide pendant 30 jours
- Impossible de falsifier ou modifier l'URL sans la clé secrète

### 2. **Validation du Middleware**
- Middleware `ValidateSignedPaymentLink` valide chaque requête
- Optionnellement, on peut forcer les URLs signées uniquement (mode strict)

### 3. **Domaine de Checkout Isolé**
- Les paiements sont traités sur un domaine séparé
- Isolement CSRF et protection supplémentaire
- Configurable via variables d'environnement

## Configuration

### Variables d'environnement

Ajoutez ces variables à votre `.env` :

```env
# Checkout Domain Configuration
CHECKOUT_DOMAIN=checkout.zilopay.com
CHECKOUT_PROTOCOL=https
CHECKOUT_REQUIRE_SIGNED_URL=false  # Mettez à true en production pour mode strict
```

### Exemple de variables en production

```env
CHECKOUT_DOMAIN=checkout.production.zilopay.com
CHECKOUT_PROTOCOL=https
CHECKOUT_REQUIRE_SIGNED_URL=true
APP_KEY=base64:....  # Clé de chiffrement Laravel (générée automatiquement)
```

## Utilisation

### Génération du lien

```php
$merchant = Merchant::find(1);
$transaction = MerchantTransaction::find(1);

// Le lien est automatiquement généré lors de la création de la transaction
$paymentLink = $paymentService->generateLink($merchant, $transaction);

// Exemple de lien généré:
// https://checkout.zilopay.com/merchant/pay/uuid-abc-123?expires=1735689600&signature=abcd1234...
```

### Format du lien sécurisé

```
https://checkout.zilopay.com/merchant/pay/{uuid}?expires={timestamp}&signature={hash}
```

**Paramètres:**
- `{uuid}` - Identifiant unique du lien de paiement
- `expires` - Timestamp d'expiration (30 jours)
- `signature` - Signature HMAC SHA256

## Flux de sécurité

```
1. Merchant -> Appel API pour créer un paiement
2. Backend -> Génère un lien signé avec URL::temporarySignedRoute()
3. Lien -> https://checkout.zilopay.com/merchant/pay/{uuid}?signature=...
4. Client -> Accède au lien depuis son navigateur
5. Middleware -> Valide la signature avant de traiter
6. Controller -> Retourne les détails du paiement si valide
7. Frontend -> Affiche le formulaire de paiement
```

## Validation de signature

Laravel valide automatiquement:
- ✅ La signature HMAC est correcte
- ✅ L'expiration n'a pas dépassé 30 jours
- ✅ Les paramètres de l'URL ne sont pas modifiés

## Mode strict (Production)

Pour enforcer les URLs signées obligatoires:

```env
CHECKOUT_REQUIRE_SIGNED_URL=true
```

Dans ce mode, les URLs sans signature seront rejetées avec une réponse 403.

## Renouvellement des liens

Les URLs expirent après 30 jours. Pour renouveler:

```php
// Régénérer le lien en créant une nouvelle transaction
$newTransaction = $paymentService->initiate($merchant, $data);
// Un nouveau lien signé sera généré automatiquement
```

## Sécurité - Bonnes pratiques

1. **Clé APP_KEY bien gardée**
   ```bash
   php artisan key:generate  # Ne jamais partager la clé
   ```

2. **HTTPS obligatoire**
   - Toujours utiliser HTTPS en production
   - Configuration: `CHECKOUT_PROTOCOL=https`

3. **Rate limiting**
   - Ajouter du rate limiting sur la route de paiement si nécessaire

4. **Validation supplémentaire**
   - Vérifier que le montant correspond
   - Vérifier que le lien n'a pas expiré
   - Vérifier que le nombre d'utilisations n'est pas dépassé

## Dépannage

### Erreur: "Invalid or missing signature"

**Cause:** URL modifiée ou signature invalide

**Solution:**
- Vérifier que APP_KEY n'a pas changé
- Régénérer un nouveau lien
- Vérifier que l'URL n'a pas été modifiée

### Le lien expire trop vite

**Solution:** Modifier la durée dans `MerchantPaymentService.php`:

```php
// Actuellement 30 jours
now()->addDays(30)

// Changer à 7 jours
now()->addDays(7)
```

## Tests

Pour tester la génération de liens:

```php
// Test unitaire
public function test_generates_secure_payment_link()
{
    $merchant = Merchant::factory()->create();
    $transaction = MerchantTransaction::factory()->create([
        'merchant_id' => $merchant->id,
    ]);
    
    $service = app(MerchantPaymentService::class);
    $link = $service->generateLink($merchant, $transaction);
    
    $this->assertTrue(str_contains($link, 'checkout.zilopay.com'));
    $this->assertTrue(str_contains($link, 'signature='));
}
```

## Références

- [Laravel Signed Routes](https://laravel.com/docs/11.x/urls#signed-urls)
- [HMAC SHA256](https://en.wikipedia.org/wiki/HMAC)
- [OWASP - URL Token Security](https://owasp.org/www-community/attacks/Cross-Site_Request_Forgery_(CSRF))
