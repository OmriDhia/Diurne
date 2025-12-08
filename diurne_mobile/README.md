# Diurne Mobile App

Application mobile pour la gestion de stock Diurne (Offline-First).

## Architecture
- **Expo** (TypeScript)
- **SQLite** (Base de données locale)
- **React Native Paper** (UI)

## Structure
```
src/
  ├── core/         # Database, Theme, Utils
  ├── features/     # Écrans et logique métier (Auth, Inventory...)
  └── navigation/   # Configuration React Navigation
```

## Démarrage
1. `npm install`
2. `npx expo start`

## Fonctionnalités
- [x] Structure & Navigation
- [x] Base de données Offline (SQLite)
- [ ] Authentification
- [ ] Inventaire & Mouvements
