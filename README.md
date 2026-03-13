# 🛡️ Projet SecurEvent - Plateforme de Gestion d'Événements Cybersécurité

Ce dépôt contient la solution complète développée pour le projet **SecurEvent** (Module Symfony - ESDI). L'application permet la gestion, la catégorisation et la réservation sécurisée d'événements de type Conférences, CTF et Workshops.

## 📅 Historique de Résolution du Projet (Semaine du 09/03 au 15/03)

Voici le détail des étapes de développement réalisées pour répondre au cahier des charges :

### Lundi & Mardi & Mercredi : Socle Technique et Sécurité (Priorité 1)
- **Initialisation** : Mise en place de Symfony 7 avec l'architecture MVC.
- **Modélisation** : Création des entités `User`, `Event` et `Reservation` avec migrations Doctrine.
- **Sécurité** : 
    - Configuration du `SecurityBundle` avec hachage Argon2id.
    - Implémentation du système d'authentification (Login Form, Logout).
    - Gestion des 3 rôles : `Visiteur` (anonyme), `Participant` (ROLE_USER), `Administrateur` (ROLE_ADMIN).
    - Protection systématique contre les failles CSRF sur les formulaires d'inscription et de réservation.

### Jeudi : Fonctionnalités Métier & Dashboard
- **Catalogue & Détails** : Développement des vues Twig pour la consultation des événements.
- **Système de Réservation** : Création d'un `ReservationService` pour gérer les inscriptions, vérifiant en temps réel la capacité maximale et empêchant les doublons.
- **Espace Admin** : Génération des CRUD sécurisés pour permettre aux administrateurs de piloter les événements.

### Vendredi : Scope Optionnel
- **Gestion des Catégories** : Ajout de l'entité `Category` et liaison ManyToOne avec `Event`. Mise en place de filtres dynamiques sur le catalogue (Conférences, CTF, Workshops).
- **Algorithme d'Exclusion (LIFO)** : Développement d'une logique complexe dans le contrôleur d'édition : si un administrateur réduit la capacité d'un événement, le système supprime automatiquement les dernières réservations effectuées pour respecter la nouvelle limite, avec notification par Flash Messages.
- **Géolocalisation & Maps** :
    - Extension de l'entité `User` pour inclure une adresse.
    - Intégration d'un module d'itinéraire Google Maps sur la page de détails des événements, pré-rempli avec l'adresse du participant.
- **Design Professionnel** : Intégration de **Tailwind CSS** pour une interface "Dark Mode" cohérente et moderne.

---

## 🛠️ Instructions de Mise en Route

### 1. Installation
```bash
composer install
npm install && npm run build

Configuration du .env, puis :

Bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

docker compose up -d 