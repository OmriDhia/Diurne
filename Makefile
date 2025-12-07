# Makefile for Diurne Project Setup

.PHONY: setup up down restart logs install-deps db-migrate fix-perms

# One-shot command to setup everything
# One-shot command to setup everything
setup: down up fix-perms install-deps jwt-setup wait-db db-migrate
	@echo "✅ Project Setup Complete! Access at: http://localhost:8081 (Frontend)"

# Stop containers
down:
	@echo "🛑 Stopping containers..."
	docker-compose down

# Build and Start containers
up:
	@echo "🚀 Building and Starting containers..."
	docker-compose up -d --build

# Fix permissions
fix-perms:
	@echo "🔧 Fixing permissions..."
	docker-compose exec -u root backend chown -R www-data:www-data /var/www/symfony/var

# Install Dependencies
install-deps:
	@echo "📦 Installing Backend Dependencies (Composer)..."
	docker-compose exec backend composer install --optimize-autoloader
	@echo "📦 Installing Frontend Dependencies (NPM)..."
	docker-compose exec frontend npm install

# Setup JWT Keys
jwt-setup:
	@echo "🔑 Generating JWT Keys..."
	docker-compose exec backend sh -c 'mkdir -p config/jwt && if [ ! -f config/jwt/private.pem ]; then openssl genrsa -out config/jwt/private.pem -aes256 -passout pass:change_me_in_prod 4096 && openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem -passin pass:change_me_in_prod; fi'
	docker-compose exec -u root backend chown -R www-data:www-data config/jwt

# Wait for Database
wait-db:
	@echo "⏳ Waiting for Database..."
	@sleep 10

# Database Migration
db-migrate:
	@echo "🗄️ Running Database Migrations..."
	docker-compose exec backend php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# View Logs
logs:
	docker-compose logs -f
