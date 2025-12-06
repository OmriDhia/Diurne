# Development Environment Setup

## Prerequisites
- Docker 20.10+ and Docker Compose
- Git

## Quick Start
```bash
git clone https://github.com/slimsayari/diurne_api
cd project

# Copy and configure environment
cp .env.dist .env
# Edit .env if needed (usually defaults are fine)

# Start containers
docker-compose up -d --build

# Verify it's working
curl http://localhost:8741
```

## Common Commands
| Command | Description |
|---------|-------------|
| `docker-compose up -d` | Start services |
| `docker-compose down` | Stop services |
| `docker-compose logs -f [service]` | View logs |
| `docker-compose exec api bash` | Enter API container |

## Database Setup
- Initial schema will be created automatically
- To import test data:
  ```bash
  docker-compose exec db mysql -u root database_name < testdata.sql
  ```