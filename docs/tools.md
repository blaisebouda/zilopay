# Tools

## Guide Swagger API Documentation

### Introduction

Swagger (OpenAPI) a été installé et configuré avec succès dans votre projet Laravel Union Halal.

### Accès à la documentation

Une fois le serveur démarré, accédez à la documentation Swagger via :

```
http://localhost:8000/api/documentation

```

### accéder a la documentation

Pour accéder à la documentation Swagger, ouvrez votre navigateur et allez à l'adresse suivante :

```
http://localhost:8000/api/documentation
```

### Commandes utiles

```bash
# Générer les annotations Swagger
php artisan l5-swagger:generate

# Vider le cache
php artisan l5-swagger:clean

# Vider le cache et régénérer
php artisan l5-swagger:clean && php artisan l5-swagger:generate
```

## Telescope

### Introduction

Telescope a été installé et configuré avec succès dans votre projet Laravel Union Halal.

### Accès à la documentation

Une fois le serveur démarré, accédez à la documentation Telescope via :

```
http://localhost:8000/telescope
```

```json
 "data": {
    "api_key": {
      "id": 3,
      "merchant_id": 1,
      "name": "Production Key",
      "key": "mk_live_55bbdd42b047e23a95642c70b5d4c050",
      "public_key": "mk_pub_live_9d06fe245c60b79675fa8d091663e7ad",
      "is_live": true,
      "last_used_at": null,
      "expires_at": null,
      "is_active": true,
      "created_at": "2026-05-11T09:48:42.000000Z",
      "updated_at": "2026-05-11T09:48:42.000000Z"
    },
    "plain_secret": "bef854418d103d597b1d29981ca90349466fad3c4736023be7aea01499c8d0b6a6c12cd0"
  }
```
