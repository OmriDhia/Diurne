# Document Technique - Diurne Mobile App

**Date :** 07 Décembre 2025
**Projet :** Diurne Mobile App
**Version :** 1.0.0
**Auteur :** Antigravity (IA Assistant)

---

## 1. Stack Technologique (Technology Stack)

### 1.1. Core Framework
*   **Framework :** [React Native](https://reactnative.dev/)
*   **Platform Manager :** [Expo SDK 50+](https://expo.dev/) (Recommandé pour la facilité de maintenance, les mises à jour OTA et la gestion des permissions).
*   **Langage :** TypeScript (Strict Mode) pour la sécurité du typage et la maintenabilité.

### 1.2. Interface Utilisateur (UI Framework)
Pour assurer une interface "Premium", cohérente et rapide à développer :
*   **UI Library :** **React Native Paper (v5)**
    *   *Pourquoi ?* Support complet de Material Design 3, composants riches (Cards, Dialogs, FAB), et système de thème puissant pour adapter l'identité visuelle de Diurne (Polices, Couleurs).
*   **Icons :** Material Community Icons (via `@expo/vector-icons`).
*   **Animations :** `react-native-reanimated` pour les micro-interactions fluides.

### 1.3. Gestion d'État et Navigation
*   **State Management :** **Zustand**
    *   *Pourquoi ?* Plus léger et moins verbeux que Redux. Idéal pour gérer la session utilisateur, le "Panier" d'inventaire, et les données cacheades.
*   **Navigation :** **React Navigation (v6)** (Native Stack).
*   **Data Fetching :** **TanStack Query (React Query)**
    *   *Pourquoi ?* Gestion native du cache, des états de chargement/erreur, et du rafraîchissement des données (stale-while-revalidate), crucial pour une app mobile.

### 1.4. Outils et Utilitaires
*   **HTTP Client :** Axios (avec intercepteurs pour l'injection du JWT).
*   **Stockage Local :** `expo-secure-store` (pour les Tokens auth) et `AsyncStorage` (pour les préférences non sensibles).
*   **Scan :** `expo-camera` ou `expo-barcode-scanner`.

---

## 2. Architecture et Structure du Code

L'application suivra une **Architecture Modulaire (Feature-based)**. Cela permet d'isoler le code par domaine métier (Auth, Stock, Inventaire) plutôt que par type technique.

### 2.1. Arborescence des Dossiers
```text
diurne_mobile/
├── src/
│   ├── app/                # Point d'entrée, Configuration globale (Theme, QueryClient)
│   ├── assets/             # Images, Fonts locales
│   ├── components/         # Composants UI partagés (Boutons génériques, Layouts)
│   │   ├── atoms/
│   │   ├── molecules/
│   │   └── organisms/
│   ├── core/               # Utilitaires système
│   │   ├── api/            # Configuration Axios
│   │   ├── auth/           # Logique d'authentification (Storage, Context)
│   │   ├── theme/          # Définition des couleurs/typo (DiurneTheme)
│   │   └── utils/          # Helpers (Formatage date, monnaie)
│   ├── features/           # Modules Métier (Cœur de l'app)
│   │   ├── auth/           # Login, ForgotPassword
│   │   │   ├── screens/
│   │   │   └── store/
│   │   ├── inventory/      # Scan, Stock Take
│   │   ├── products/       # Liste tapis, Détail, Recherche
│   │   └── workshops/      # Mouvements, Historique
│   ├── navigation/         # Définition des Stacks et Tabs
│   └── types/              # Définitions TypeScript globales (Env, API Response)
├── App.tsx                 # Root Component
├── app.json                # Config Expo
└── tsconfig.json           # Config TypeScript
```

### 2.2. Flux de Données (Data Flow)
1.  **UI Component** (ex: `ProductDetailScreen`) demande des données.
2.  **Custom Hook** (ex: `useProductDetails(id)`) utilise **React Query**.
3.  **API Service** (ex: `products.api.ts`) fait l'appel Axios vers `diurne_api`.
4.  **UI** réagit aux états `isLoading`, `isError`, `data`.
5.  **Global Store** (Zustand) n'est utilisé que pour les données traversales (User Session, Theme Mode, Panier d'actions en attente).

---

## 3. Détails Techniques des Modules

### 3.1. Module Authentification
*   Login avec Email/Password.
*   Stockage du JWT dans `SecureStore`.
*   Intercepteur Axios : Si `401 Unauthorized`, tentative de refresh token ou déconnexion forcée.

### 3.2. Module Scanner
*   Utilisation de la caméra pour détecter QR Codes (format JSON interne) ou Code-barres (RN Number).
*   Mode "Rafale" pour l'inventaire : Scanne et ajoute à une liste temporaire sans changer d'écran.

### 3.3. Gestion Offline (Optionnel/Futur)
*   Grâce à **React Query**, l'application affichera les dernières données connues si le réseau est coupé.
*   Les actions d'inventaire peuvent être stockées dans une file d'attente (Queue) persistée localement et synchronisée au retour du réseau.

## 4. Sécurité
*   **SSL Pinning** (optionnel pour la prod) pour éviter les attaques Man-in-the-Middle.
*   Aucune donnée sensible (prix d'achat, marges) n'est stockée en clair sur le téléphone.
*   Code obfusqué lors du build production (JSC/Hermes bytecode).

## 5. Déploiement (CI/CD)
*   Via **EAS Build** (Expo Application Services).
*   **Dev** : Build APK/IPA ad-hoc distribué aux testeurs internes.
*   **Prod** : Soumission automatique aux Stores (Apple App Store / Google Play).

---
*Fin du Document Technique*
