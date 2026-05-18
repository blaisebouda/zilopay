# Implémentation - Lien de Paiement Sécurisé

## Fichiers modifiés et créés

### 1. **Service de Paiement** 
📄 [app/Services/Merchant/MerchantPaymentService.php](../app/Services/Merchant/MerchantPaymentService.php)
- ✅ Ajout import `use Illuminate\Support\Facades\URL`
- ✅ Implémentation `generateLink()` - Génère une URL signée avec HMAC
- ✅ Implémentation `getCheckoutLink()` - Méthode publique pour récupérer le lien

### 2. **Middleware de Sécurité**
📄 [app/Http/Middleware/ValidateSignedPaymentLink.php](../app/Http/Middleware/ValidateSignedPaymentLink.php) *(NOUVEAU)*
- ✅ Valide la signature HMAC de l'URL
- ✅ Support du mode strict (optionnel)
- ✅ Backward compatibility

### 3. **Configuration Routes**
📄 [routes/merchant.php](../routes/merchant.php)
- ✅ Ajout préfixe de nom `->name('merchant.')`
- ✅ Application du middleware sur les routes de paiement
- ✅ Routes nommées: `merchant.pay` et `merchant.process`

### 4. **Bootstrap Application**
📄 [bootstrap/app.php](../bootstrap/app.php)
- ✅ Import du middleware `ValidateSignedPaymentLink`
- ✅ Enregistrement de l'alias: `validate.signed.payment.link`

### 5. **Configuration Services**
📄 [config/services.php](../config/services.php)
- ✅ Configuration du domaine checkout
- ✅ Configuration du protocole (http/https)
- ✅ Configuration du mode strict (optionnel)

### 6. **Documentation**
📄 [docs/secure-payment-links.md](../docs/secure-payment-links.md) *(NOUVEAU)*
- ✅ Guide complet de sécurité
- ✅ Architecture et flux de sécurité
- ✅ Configuration et utilisation
- ✅ Dépannage

### 7. **Tests Unitaires**
📄 [tests/Unit/Services/Merchant/SecurePaymentLinkTest.php](../tests/Unit/Services/Merchant/SecurePaymentLinkTest.php) *(NOUVEAU)*
- ✅ Test génération de lien
- ✅ Test validation signature
- ✅ Test configuration personnalisée
- ✅ Test expiration 30 jours
- ✅ Test liens différents

### 8. **Configuration d'Exemple**
📄 [.env.checkout.example](.env.checkout.example) *(NOUVEAU)*
- ✅ Variables d'environnement par défaut
- ✅ Documentation des paramètres

## Fonctionnement

### Génération du lien

```php
$paymentService = app(MerchantPaymentService::class);
$checkoutLink = $paymentService->getCheckoutLink($transaction);

// Résultat:
// https://checkout.zilopay.com/merchant/pay/{uuid}?expires={ts}&signature={hash}
```

### Flux de sécurité

```
1. Cliente demande un paiement
2. Backend génère une URL signée (HMAC SHA256)
3. URL redirige vers checkout.zilopay.com
4. Middleware valide la signature
5. Frontend affiche le formulaire de paiement
```

### Sécurité implémentée

✅ **HMAC SHA256** - Impossible de falsifier l'URL
✅ **Expiration** - URL valide 30 jours
✅ **Domaine isolé** - Séparation CSRF
✅ **Middleware** - Validation à chaque requête
✅ **Mode strict** - Peut forcer signatures obligatoires

## Configuration (.env)

```env
# Ajouter à votre .env
CHECKOUT_DOMAIN=checkout.zilopay.com
CHECKOUT_PROTOCOL=https
CHECKOUT_REQUIRE_SIGNED_URL=false  # true en production
```

## Installation / Déploiement

1. ✅ Les fichiers sont créés et prêts
2. ⚙️ Ajouter les variables d'environnement au `.env`
3. 🧪 Exécuter les tests: `php artisan test tests/Unit/Services/Merchant/SecurePaymentLinkTest.php`
4. 🚀 Déployer et tester en production

## Points clés de sécurité

- 🔐 Clé `APP_KEY` doit être bien gardée (elle signe les URLs)
- 🔒 HTTPS obligatoire en production
- ⏱️ Signatures valides 30 jours (configurable)
- 🛡️ Mode strict recommandé pour production

## Prochaines étapes

1. Ajouter les variables d'environnement
2. Intégrer le checkout (frontend/UI)
3. Tester en local: `php artisan test`
4. Déployer en staging
5. Valider en production avec monitoring

## Support

Voir [docs/secure-payment-links.md](../docs/secure-payment-links.md) pour:
- FAQ et dépannage
- Exemples d'utilisation avancée
- Référence de sécurité
