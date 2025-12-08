# MASTER PROMPT - DIURNE MOBILE APP IMPLEMENTATION

You are an expert Senior Mobile Engineer specializing in **React Native (Expo)** and **TypeScript**. You are tasked with building the "Diurne Mobile App" strictly following the specifications below.

## 1. Project Context
Diurne is a carpet company. This app allows managing stock, production progress, and administration via mobile. It connects to the Diurne API (Symfony).
**CRITICAL**: The app must be **Offline-First**. Warehouse staff often work in basements or showrooms with poor connectivity. The app must allow full inventory and movement capabilities without an internet connection, synchronizing when the network returns.

## 2. Technology Stack
*   **Framework**: Expo SDK 50+ (Managed Workflow).
*   **Language**: TypeScript (Strict Mode).
*   **UI Library**: **React Native Paper (v5)** (MD3Theme).
*   **State**: Zustand (Session/Auth/Global UI).
*   **Data Fetching**: TanStack Query (v5).
*   **Local Database**: **Expo SQLite** (for offline stock data of ~5000+ items).
*   **Network Detection**: `expo-network`.
*   **Navigation**: React Navigation v6.

## 3. Navigation Structure (Strict) 
Implement the navigation tree exactly as follows:
```text 

ROOT
 ├── Login Screen
 ├── Menu Principal (Dashboard)
 │    ├── Inventaire (Inventory)
 │    ├── Entrée (Stock In)
 │    ├── Sortie (Stock Out)
 │    ├── Progress Report (Saisie PR)
 │    ├── Recherche (Search Tabs)
 │    │    ├── EMPL (Emplacement)
 │    │    ├── MVT (Mouvements)
 │    │    ├── PROD (Production PR)
 │    │    └── PHOTO (Photos)
 │    ├── Saisie Photo (Photo Upload)
 │    └── Paramètre (Admin)
 │         ├── User Management
 │         ├── Atelier Management
 │         └── Type Management
 └── Popup / Confirmations (Global Modal)
```

## 4. Offline Architecture (CORE REQUIREMENT)

### A. Database Strategy (SQLite)
Use `expo-sqlite` to maintain a local mirror of critical data.
*   **Tables**:
    *   `carpets`: (id, rn, reference, status, location_id, updated_at, json_blob)
    *   `locations`: (id, name, type)
    *   `action_queue`: (id, action_type, payload, status ['PENDING', 'SYNCED', 'ERROR'], created_at, retry_count)
*   **Sync "Pull" (On App Start / Manual Refresh)**:
    1.  Fetch *Delta* or *Full Dump* of Carpets/Locations from API.
    2.  Bulk Insert/Update into SQLite.
    3.  User interface reads FROM SQLite for searches, generic "finding" tasks, ensuring 0-latency.

### B. Sync "Push" (Action Queue)
When a user performs an action (e.g., "Move Carpet A to Warehouse"):
1.  **Optimistic Update**: Update SQLite immediately (change carpet location).
2.  **Queue**: Insert record into `action_queue` (Type: `MOVE_STOCK`, Payload: `{rn: 'A', to: 'Warehouse'}`).
3.  **Process**:
    *   If **Online**: Attempt API call immediately.
        *   Success: Mark queue item `SYNCED`, delete from queue.
        *   Fail: Mark `PENDING` (retry later).
    *   If **Offline**: Leave as `PENDING`. Background watcher or `NetInfo` change triggers retry processing.

### C. Conflict Resolution
*   **Server Authority**: If the server rejects a sync (e.g., "Carpet already sold"), the app must:
    1.  Mark the queue item as `ERROR`.
    2.  Show a "Sync Issue" banner to the user.
    3.  Rollback the local SQLite change for that item to match the Server state.

## 5. Functional Guidelines & Actions

### A. Intent & Logic Mapping
| Context | User Intent | Action to Implement |
| :--- | :--- | :--- |
| **Auth** | Login ("Se souvenir") | `login_check`, store token securely |
| **Stock** | "Enregistrer Inventaire" | Save specific inventory list to Local DB -> Queue Sync |
| **Stock** | "Saisir Entrée/Sortie" | `enregistrer_entree()`, `enregistrer_sortie()` (via Action Queue) |
| **PR** | "Ajouter PR", "Sauvegarder" | `ajouter_evenement_PR()` (via Action Queue) |
| **Search**| "Rechercher Emplacement/Mvt"| **Query SQLite** first (Instant), optional "Fetch Fresh" from API |
| **Admin** | "Gérer User/Atelier/Type" | CRUD operations (Online Only preferred for Admin) |

### B. View Requirements

#### 1. Login
*   Fields: Login, Password, Checkbox "Se souvenir".
*   Action: Redirects to *Menu Principal* on success.
*   **Offline Support**: If previously logged in (valid token in SecureStore), simple offline check allows entry.

#### 2. Inventaire (Stock Take)
*   **Fields**: Emplacement (Scan/Type), Date (Auto), RN List.
*   **Offline Mode**:
    *   User selects Location.
    *   User Scans items.
    *   App verifies RN existence against **Local SQLite**.
    *   If RN unknown locally: Prompt "Unknown Item, add anyway?".
    *   Save: Stores entire Inventory Session in `action_queue`.

#### 3. Entrée / Sortie
*   **Fields**: Location In (or Out), Date (Auto), RN List (Scan/Type).
*   **Features**: Scan QR/Barcode.
*   **Logic**: Updates local SQLite state immediately (Optimistic UI) before network sync.

#### 4. Progress Report (PR)
*   **Fields**: RN, Date (Auto), État (Dropdown), Commentaire.
*   **Conditional**: IF État == "Tissage", show "Tissé" field.
*   **List**: Show history.

#### 5. Saisie Photo
*   **Fields**: RN, Type Photo.
*   **Offline Mode**:
    *   Save photo to `FileSystem.documentDirectory`.
    *   Add metadata to `action_queue` (Type: `UPLOAD_PHOTO`).
    *   Background service uploads file when connection restored.
*   **Naming Rule**: `{RN}_{Type}_{Index}.jpg`.

#### 6. Admin Parameters
*   **User**: Search/Edit/Create Users.
*   **Atelier**: Manage Workshops.
*   **Type**: Manage PR Status / Photo Types.
*   *Note*: Admin features can be restricted to **Online Only** to simplify logic.

## 6. Coding Rules
1.  **Architecture**: Feature-based (`features/inventory`, `features/offline`, `features/search`).
    *   Create a dedicated `offline` manager (SyncService, DatabaseService).
2.  **Naming**: Use English for code (variables, functions) but **French for UI Strings**.
3.  **Strictness**: Do NOT invent new views. Follow exactly the "Description Fonctionnelle".
4.  **Error Handling**: All network calls MUST have a `catch` block that determines if the error is "Network Unreachable" (Queue it) or "Logic Error" (Alert User).

START by scaffolding the Navigation Tree and the SQLite Database Service.
__