# Makefile for Diurne Project Setup

.PHONY: setup up down restart logs install-deps db-migrate fix-perms

# One-shot command to setup everything
setup: down up fix-perms install-deps db-migrate
	@echo "✅ Project Setup Complete! Access at: http://localhost:8081 (Frontend)"

# Stop containers
down:
	@echo "🛑 Stopping containers..."
	docker-compose down

# Build and Start containers
up:
	@echo "🚀 Building and Starting containers..."
	docker-compose up -d --build

# Fix permissions (crucial for WSL/Docker volumes)
fix-perms:
	@echo "🔧 Fixing permissions..."
	# Ensure storage/cache is writable (if applicable, basic example)
	# docker-compose exec -u root backend chown -R www-data:www-data /var/www/symfony/var

# Install Dependencies
install-deps:
	@echo "📦 Installing Backend Dependencies (Composer)..."
	docker-compose exec backend composer install --optimize-autoloader
	@echo "📦 Installing Frontend Dependencies (NPM)..."
	docker-compose exec frontend npm install

# Database Migration
db-migrate:
	@echo "🗄️ Running Database Migrations..."
	# Wait for DB to be ready (naive sleep, or a wait-for-it script)
	@sleep 10
	docker-compose exec backend php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# View Logs
logs:
	docker-compose logs -f
