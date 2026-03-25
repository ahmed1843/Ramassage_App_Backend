markdown
# 🚛 New Déchets - Système de Gestion des Déchets (Backend API)

**New Déchets** est une solution numérique permettant d'optimiser la collecte des déchets ménagers à Dakar (Médina, Colobane, etc.). Cette API Laravel gère les utilisateurs, les zones de passage, les signalements citoyens et les statistiques de nuisances.

## ✨ Fonctionnalités Clés
- **Authentification Sécurisée** : Connexion et inscription avec Sanctum (Token).
- **Gestion des Zones** : Liste des horaires de passage et descriptions des quartiers.
- **Signalements Citoyens** : Envoi de signalements (photo, type de déchet, niveau de nuisance).
- **Statistiques en Temps Réel** : Analyse du niveau de bruit et de propreté par zone.
- **Système de Notifications** : Alertes de passage du camion UCG (visuelles et sonores).

## 🛠️ Technologies Utilisées
- **Framework** : Laravel 10 / 11
- **Base de données** : MySQL
- **Authentification** : Laravel Sanctum
- **Format d'échange** : API REST (JSON)

## 🚀 Installation Locale (XAMPP)

1. **Cloner le projet** :
   ```bash
   git clone https://github.com
   cd ramassage-app
Utilisez le code avec précaution.

Installer les dépendances :
bash
composer install
Utilisez le code avec précaution.

Configurer l'environnement :
Dupliquer .env.example et le renommer en .env.
Créer une base de données nommée ramassage_db.
Configurer les accès DB dans le fichier .env.
Lancer les migrations & les données de test :
bash
php artisan migrate --seed
Utilisez le code avec précaution.

Démarrer le serveur :
bash
php artisan serve
Utilisez le code avec précaution.

L'API sera disponible sur : http://127.0.0.1:8000
👤 Auteur
Ahmed (@ahmed1843) - Développeur Fullstack

---






