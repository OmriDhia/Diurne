# Cahier des Charges - Application Mobile Diurne Stock

**Date :** 06 Décembre 2025
**Projet :** Diurne Mobile App - Module Gestion des Stocks


---

## 1. Contexte et Objectifs

### 1.1. Contexte
La société Diurne souhaite se doter d'une application mobile pour faciliter la gestion de ses stocks de tapis ("Carpets") et d'échantillons ("Samples"). Actuellement, la gestion est centralisée dans l'ERP (Diurne API/VueJS), mais les opérations physiques en entrepôt ou showroom nécessitent une interface mobile agile.

### 1.2. Objectifs du projet
*   Dématérialiser la gestion des stocks en temps réel.
*   Permettre l'identification rapide des produits via scan (QR Code/Code-barres) du numéro RN ou Référence.
*   Suivre les mouvements de stock (changement d'emplacement).
*   Faciliter les inventaires tournants et périodiques.
*   Donner un accès rapide aux informations produit (fiche technique, historique).

## 2. Périmètre Fonctionnel

L'application se concentre sur deux entités principales identifiées dans l'ERP :
*   **Carpets (Tapis finis)** : Suivis par `rnNumber`.
*   **Samples (Échantillons)** : Suivis par `rn` ou `reference`, associés à une `Location`.

### 2.1. Fonctionnalités Clés

#### A. Authentification
*   Connexion sécurisée utilisant les comptes utilisateurs existants de l'ERP.
*   Gestion des rôles (Lecture seule vs Modification de stock).

#### B. Recherche et Identification
*   **Scan** : Utilisation de la caméra pour scanner un code-barres/QR code (RN).
*   **Recherche Manuelle** : Recherche par Numéro RN, Collection, Modèle, ou Client.
*   **Refinement** : Filtres par statut (En stock, Réservé, Vendu).

#### C. Fiche Détail Produit
Affichage des informations provenant de l'ERP :
*   **Info Générales** : Collection, Modèle, Dimensions, Qualité.
*   **Statut** : État actuel (ex: "Disponible", "En restauration").
*   **Localisation** : Emplacement actuel (Showroom Paris, Entrepôt, etc.).
*   **Photo** : Visuel du tapis/échantillon.
*   **Historique** : Derniers mouvements (via `WorkshopRnHistory`).

#### D. Gestion des Mouvements (Stock Movement)
*   **Changer de Localisation** :
    *   Scan du produit.
    *   Sélection de la nouvelle localisation (liste déroulante ou scan emplacement).
    *   Validation du mouvement -> Mise à jour API (`Location` et création `History`).
*   **Sortie de Stock** : Déclarer un produit comme vendu ou expédié.

#### E. Inventaire (Stock Take)
*   Mode "Inventaire" par localisation.
*   Lister tous les produits supposés être dans une localisation (ex: "Showroom Rive Gauche").
*   Scanner les produits présents pour valider leur présence.
*   Identifier les écarts (manquants ou intrus).

## 3. Architecture Technique

### 3.1. Stack Technologique Proposée
*   **Mobile Framework** : React Native (pour compatibilité iOS/Android et performance).
*   **Backend** : Diurne API (Symfony/Platform.net).
*   **Base de Données** : MySQL (Existante).

### 3.2. Intégration API
L'application communiquera avec `diurne_api`.
*   **Endpoints à exploiter/créer** :
    *   `GET /api/carpets/{rn}` : Récupération détails tapis.
    *   `GET /api/samples/{rn}` : Récupération détails échantillon.
    *   `POST /api/movements` : Enregistrement d'un changement de localisation.
    *   `GET /api/locations` : Liste des emplacements disponibles.

## 4. Interfaces Utilisateur (UX/UI)

*   **Design System** : Cohérent avec l'identité Diurne (Premium, épuré).
*   **Navigation** : Tab Bar (Accueil, Scan, Recherche, Profil).
*   **Ergonomie** : Boutons larges pour usage tactile en entrepôt.

## 5. Livrables Attendus
1.  Code source de l'application mobile (Repo `diurne_mobile`).
2.  Documentation d'installation et déploiement.
3.  Fichier `.apk` et `.ipa` pour tests.

---
*Document généré sur la base de l'analyse technique de l'ERP Diurne existant.*
