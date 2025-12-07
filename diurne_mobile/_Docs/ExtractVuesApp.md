# 📘 **APPLICATION MOBILE – DOCUMENTATION IA COMPLÈTE**

## *Arbre de navigation • Intents • Actions • Description fonctionnelle*

---

# 🧭 **1. Arbre Global de Navigation (Navigation Tree)**

```
ROOT
 ├── Login
 ├── Menu Principal
 │    ├── Inventaire
 │    ├── Entrée
 │    ├── Sortie
 │    ├── Progress Report (PR)
 │    ├── Recherche
 │    │    ├── Recherche Emplacement (EMPL)
 │    │    ├── Recherche Mouvement (MVT)
 │    │    ├── Recherche Production (PROD)
 │    │    └── Recherche Photo (PHOTO)
 │    ├── Saisie Photo
 │    └── Paramètre
 │         ├── User
 │         ├── Atelier
 │         └── Type
 └── Popup / Confirmations
```

---

# 🎯 **2. Intents (Ce que l’utilisateur veut faire)**

| Catégorie           | Intents                                                              |
| ------------------- | -------------------------------------------------------------------- |
| **Connexion**       | se connecter, mémoriser login                                        |
| **Stock**           | enregistrer inventaire, saisir entrée, saisir sortie                 |
| **Production**      | saisir PR, consulter statut RN                                       |
| **Recherche**       | rechercher RN, mouvements, PR, photos                                |
| **Photos**          | associer une photo au RN, charger une photo                          |
| **Admin – User**    | créer user, chercher user, modifier user, supprimer user             |
| **Admin – Atelier** | gérer atelier, modifier types RN                                     |
| **Admin – Types**   | modifier statuts PR, ajouter/supprimer statut, gérer types de photos |

---

# ⚙️ **3. Actions (Opérations que l’IA peut exécuter)**

| Domaine             | Action                                                                                   |
| ------------------- | ---------------------------------------------------------------------------------------- |
| **Navigation**      | aller_vers(vue), retour(), fermer_popup()                                                |
| **Formulaire**      | saisir(champ, valeur), scanner_RN(), ajouter_ligne(), supprimer_ligne()                  |
| **Stock**           | sauvegarder_inventaire(), enregistrer_entree(), enregistrer_sortie()                     |
| **PR**              | ajouter_evenement_PR(), sauvegarder_PR()                                                 |
| **Recherche**       | rechercher_RN(), rechercher_mouvement(), rechercher_PR(), rechercher_photo()             |
| **Admin – Users**   | rechercher_user(), créer_user(), mettre_a_jour_user(), supprimer_user()                  |
| **Admin – Atelier** | modifier_atelier(), modifier_types_RN()                                                  |
| **Admin – Types**   | ajouter_PR_statut(), supprimer_PR_statut(), ajouter_type_photo(), supprimer_type_photo() |

---

# 🏛️ **4. Description Fonctionnelle Complète des Vues**

---

# **4.1. Login (Connexion)**

### **Composants**

* Champ **Login**
* Champ **Mot de passe**
* Case **Se souvenir**
* Bouton **Connexion**

### **Logique**

* Vérification login/MDP
* Si “Se souvenir” → stocker localement
* Redirection vers **Menu Principal**

---

# **4.2. Menu Principal**

### **Sections (selon profil)**

* Inventaire
* Entrée / Sortie
* Saisie PR
* Recherche RN
* Saisie Photo
* Atelier / Production
* État inventaire
* Paramètre

---

# **4.3. Inventaire**

### **Champs**

* Emplacement dépôt (scan/clavier)
* Date (auto)
* RN
* Liste RN
* Suppression possible d’un RN

### **Actions**

* OK (sauvegarder)
* Retour
* Nouveau (avec confirmation si non sauvegardé)

---

# **4.4. Saisie Entrée / Sortie**

| Saisie Entrée        | Saisie Sortie         |
| -------------------- | --------------------- |
| Emplacement d’entrée | Emplacement de sortie |
| Date auto            | Date auto             |
| RN (scan/clavier)    | RN                    |
| Liste RN             | Liste RN              |

Actions identiques : **OK / Retour / Nouveau**

---

# **4.5. Progress Report (PR)**

### **Champs**

* RN
* Date (auto)
* État (Préparation, Tissage, Finition, Envoi)
* Champ **Tissé** (visible si état = Tissage)
* Commentaire (non visible pour Atelier)
* Liste événements (avec delete)

### **Actions**

* Ajouter événement
* OK

---

# **4.6. Recherche**

### **Onglets**

* EMPL : Emplacement d’un RN ou liste d’un emplacement
* MVT : Historique mouvements
* PROD : PR d’un RN
* PHOTO : Photos associées

### **Champs**

* RN
* Emplacement (selon onglet)
* Bouton **RECHERCHER**

### **Résultat**

Tableau RN / Emplacement / Quantité

---

# **4.7. Saisie Photo**

### **Champs**

* RN
* Type photo (Production, Finition, Drone, Vignette, Détail)
* Boutons charger / prendre photo

### **Nommage automatique**

`{RN}_{Type}_{Index}.jpg`

---

# **4.8. Paramètre**

Regroupe **User**, **Atelier**, **Type**

---

# 🟦 **4.8.1. Paramètre – User**

### **Objectif**

Créer, rechercher, modifier, supprimer utilisateurs.

### **Contenu de la vue**

#### **Bloc recherche**

* ID (email)
* Droit (Admin, Atelier, Interne Prod, Photo)
* Boutons **RECHERCHER** et **Nouveau**

#### **Bloc utilisateur (éditable)**

* ID
* MDP
* Droit
* Champ Atelier *affiché uniquement si droit = Atelier*
* Boutons :

  * 🗑️ Supprimer
  * 🚫 Désactiver (selon design)

### **Fonctions IA**

* rechercher_user()
* créer_user()
* mettre_a_jour_user()
* supprimer_user()

---

# 🟩 **4.8.2. Paramètre – Atelier**

### **Objectif**

Gérer les ateliers et leurs catégories de RN.

### **Pour chaque atelier**

* Champ **Atelier** (nom)
* RN Tapis (lettre ex: B / C)
* RN Éch (lettre ex: F / D)

### **Logique**

* Chaque ligne correspond à un atelier.
* L’utilisateur peut modifier le nom et les catégories RN.

### **Fonctions IA**

* modifier_atelier()
* modifier_types_RN()

---

# 🟧 **4.8.3. Paramètre – Type**

### **Objectif**

Gérer :

* les statuts Progress Report
* les types photo

---

## **A. Section Progress Report**

### **Liste**

* Préparation de commande
* Tissage
* Finition
* Envoi
  *(modifiable / supprimable)*

### **Actions**

* Ajouter statut (+)
* Supprimer statut (🗑️)

### **Fonctions IA**

* ajouter_PR_statut()
* supprimer_PR_statut()

---

## **B. Section Photo**

### **Liste**

* Production
* Finition
* Drone
* Vignette
* Détail

### **Actions**

* Ajouter type photo (+)
* Supprimer type (🗑️)

### **Fonctions IA**

* ajouter_type_photo()
* supprimer_type_photo()

---

# 🔌 **5. Tables Résumées des Intents & Actions**

## **Intents → Navigation**

| Intent             | Navigation IA                   |
| ------------------ | ------------------------------- |
| aller à inventaire | aller_vers("Inventaire")        |
| ouvrir paramètres  | aller_vers("Paramètre")         |
| modifier user      | aller_vers("Paramètre/User")    |
| gérer atelier      | aller_vers("Paramètre/Atelier") |

---

## **Intents → Stock**

| Intent                 | Action                   |
| ---------------------- | ------------------------ |
| enregistrer inventaire | sauvegarder_inventaire() |
| saisir entrée          | enregistrer_entree()     |
| saisir sortie          | enregistrer_sortie()     |

---

## **Intents → PR**

| Intent         | Action                 |
| -------------- | ---------------------- |
| ajouter PR     | ajouter_evenement_PR() |
| sauvegarder PR | sauvegarder_PR()       |

---

## **Intents → Recherche**

| Intent                 | Action                 |
| ---------------------- | ---------------------- |
| rechercher emplacement | rechercher_RN()        |
| rechercher mouvement   | rechercher_mouvement() |
| rechercher PR          | rechercher_PR()        |
| rechercher photos      | rechercher_photo()     |

---

## **Intents → Paramètre**

| Intent               | Action                 |
| -------------------- | ---------------------- |
| chercher user        | rechercher_user()      |
| nouveau user         | créer_user()           |
| modifier user        | mettre_a_jour_user()   |
| supprimer user       | supprimer_user()       |
| modifier atelier     | modifier_atelier()     |
| modifier RN types    | modifier_types_RN()    |
| ajouter statut PR    | ajouter_PR_statut()    |
| supprimer statut PR  | supprimer_PR_statut()  |
| ajouter type photo   | ajouter_type_photo()   |
| supprimer type photo | supprimer_type_photo() |

---

# 🧠 **6. Instructions IA pour l’exploitation du document**

* Toujours déterminer **l’intent** de l’utilisateur.
* Toujours sélectionner **l’action IA appropriée**.
* Toujours utiliser **l’arbre de navigation** pour déterminer la vue cible.
* Toujours préciser **si un champ nécessite un scan RN**, une saisie, ou une sélection.
* Ne jamais inventer de nouvelles vues.
* Ne jamais modifier les noms (PR, atelier, RN, etc.).
* Suivre exactement les règles de logique décrites dans ce document.

---

# ✔️ **Fichier `.md` prêt à l’usage IA / prompt**

