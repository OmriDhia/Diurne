# API Diurne: Modular CQRS Architecture

## Overview
A modern backend architecture combining modular design with CQRS pattern for scalable, maintainable applications.

## Key Features
- 🧩 Modular component architecture
- ⚡ CQRS pattern implementation
- 🔒 Role-based access control
- 📊 Comprehensive customer relationship management

## Documentation
```bash
docs/
├─ architecture/               # System design blueprints
│  ├─ images/                  # Architecture diagrams
│  ├─ [MENU.MD](/docs/architecture/MENU.MD)                  # Navigation/RBAC system
│  ├─ [CONTACT.MD](/docs/architecture/CONTACT.MD)            # CRM implementation
│  ├─ [USER.MD](/docs/architecture/USER.MD)                  # Authentication flows
│  ├─ [EVENT.MD](/docs/architecture/EVENT.MD)                # Calendar/reminder system
│  └─ [CONTREMARQUE.MD](/docs/architecture/CONTREMARQUE.MD)  # Core project management
├─ [INSTALL.md](/docs/INSTALL.md)                            # Deployment guide
└─ [PRODUCTION_IMPORT.md](/docs/PRODUCTION_IMPORT.md)        # Data migration procedures

```
## Technical Stack
| Component | Version | Key Features |
|-----------|---------|--------------|
| PHP | 8.4 | JIT compilation, typed properties 2.0 |
| Symfony | 7.2 | Improved Messenger, Runtime components |
| Doctrine | 3.3 | Enhanced ORM performance |
| MySQL | 8.0 | Window functions, atomic DDL |