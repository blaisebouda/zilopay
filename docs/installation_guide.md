# Guide d'installation d'un projet Laravel existant

Ce guide vous explique étape par étape comment installer et configurer un projet Laravel existant sur votre ordinateur.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** (version 8.2 ou supérieure)
- **Composer** (gestionnaire de dépendances PHP)
- **PostgreSQL** (base de données)
- **Git** (pour cloner le projet)
- **Node.js et NPM** (pour les assets front-end)

---

## Étape 1 : Cloner le projet

Ouvrez votre terminal et naviguez vers le dossier où vous voulez installer le projet :

```bash
cd ~/Documents/mes-projets
```

Clonez le dépôt Git du projet :

```bash
git clone https://github.com/utilisateur/nom-du-projet.git
```

Entrez dans le dossier du projet :

```bash
cd nom-du-projet
```

---

## Étape 2 : Installer les dépendances PHP

Installez toutes les dépendances PHP du projet avec Composer :

```bash
composer install
```

> **Note :** Cette commande peut prendre quelques minutes. Elle lit le fichier `composer.json` et télécharge tous les packages nécessaires.

---

## Étape 3 : Créer le fichier de configuration

Laravel utilise un fichier `.env` pour stocker les configurations sensibles (base de données, clés API, etc.).

Copiez le fichier d'exemple :

```bash
cp .env.example .env
```

Sur Windows, utilisez :

```bash
copy .env.example .env
```

---

## Étape 4 : Générer la clé d'application

Laravel nécessite une clé unique pour sécuriser les sessions et les données chiffrées :

```bash
php artisan key:generate
```

> Cette commande génère automatiquement une clé et la place dans votre fichier `.env` sous `APP_KEY`.

---

## Étape 5 : Configurer la base de données

Ouvrez le fichier `.env` avec votre éditeur de texte et modifiez les paramètres de connexion :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_votre_base
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

**Créez la base de données** dans MySQL/PostgreSQL :

```sql
CREATE DATABASE nom_de_votre_base;
```

---

## Étape 6 : Exécuter les migrations

Les migrations créent les tables dans votre base de données :

```bash
php artisan migrate
```

> Si vous voyez "Migration table created successfully" et une liste de migrations, c'est réussi !

### Optionnel : Peupler la base avec des données de test

Si le projet contient des seeders (données d'exemple) :

```bash
php artisan db:seed
```

Ou tout en une fois (réinitialise la base + migrations + seeders) :

```bash
php artisan migrate:fresh --seed
```

---

## Étape 7 : Installer les dépendances front-end

Si le projet utilise des assets JavaScript/CSS (Vue.js, React, Tailwind, etc.) :

```bash
npm install
```

Puis compilez les assets :

```bash
npm run dev
```

Pour la production :

```bash
npm run build
```

## Étape 9 : Démarrer le serveur de développement

Lancez le serveur local Laravel :

```bash
php artisan serve
```

Par défaut, l'application sera accessible à l'adresse :

```
http://localhost:8000
```

Ouvrez votre navigateur et visitez cette URL pour voir votre application !

---

## Conclusion

Vous avez maintenant installé avec succès un projet Laravel existant ! Vous pouvez commencer à développer ou tester l'application.
