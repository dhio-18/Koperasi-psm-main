#!/bin/bash

# Koperasi PSM - Production Deployment Script
# This script should be run on the production server

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Configuration
APP_PATH="${APP_PATH:-.}"
BACKUP_DIR="${BACKUP_DIR:-${APP_PATH}/storage/backups}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${BACKUP_DIR}/backup_${TIMESTAMP}.tar.gz"

# Ensure we're in the app directory
cd "${APP_PATH}"

log_info "Starting production deployment..."

# 1. Create backup
log_info "Creating backup..."
mkdir -p "${BACKUP_DIR}"
tar -czf "${BACKUP_FILE}" \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache' \
    --exclude='storage/framework/sessions' \
    --exclude='.git' \
    .
log_success "Backup created: ${BACKUP_FILE}"

# 2. Load environment variables
log_info "Loading environment variables..."
if [ ! -f .env ]; then
    log_error ".env file not found!"
    exit 1
fi

# Export environment variables for use in artisan commands
export $(cat .env | grep -v '^#' | xargs)

# 3. Install composer dependencies
log_info "Installing PHP dependencies..."
composer install --no-dev --prefer-dist --no-interaction --no-progress
log_success "PHP dependencies installed"

# 4. Run database migrations
log_info "Running database migrations..."
php artisan migrate --force
log_success "Database migrations completed"

# 5. Clear and cache configuration
log_info "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
log_success "Application optimized"

# 6. Build frontend assets with Vite
if [ -f "package.json" ]; then
    log_info "Building frontend assets with Vite..."
    npm ci --omit=dev
    npm run build
    if [ $? -eq 0 ]; then
        log_success "Frontend assets built successfully"
    else
        log_error "Vite build failed!"
        exit 1
    fi
else
    log_warning "package.json not found, skipping asset build"
fi

# 7. Clear old session and cache files
log_info "Clearing cache..."
php artisan cache:clear
php artisan session:clear
log_success "Cache cleared"

# 8. Set proper permissions
log_info "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
find storage bootstrap/cache -type f -exec chmod 664 {} \;
log_success "Permissions set"

# 9. Restart queue workers (if using queue)
log_info "Restarting queue workers..."
php artisan queue:restart || log_warning "Queue restart skipped (may not be running)"
log_success "Queue workers restarted"

# 10. Health check
log_info "Running health checks..."
HEALTH_CHECK_URL="${APP_URL}/health"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "${HEALTH_CHECK_URL}" || echo "000")

if [ "${HTTP_CODE}" = "200" ] || [ "${HTTP_CODE}" = "404" ]; then
    log_success "Health check passed"
else
    log_warning "Health check returned HTTP ${HTTP_CODE}"
fi

# 11. Cleanup old backups (keep last 7 days)
log_info "Cleaning up old backups..."
find "${BACKUP_DIR}" -name "backup_*.tar.gz" -type f -mtime +7 -delete
log_success "Old backups cleaned up"

# 12. Log deployment
log_info "Logging deployment..."
{
    echo "=== Deployment Log ==="
    echo "Timestamp: ${TIMESTAMP}"
    echo "User: $(whoami)"
    echo "Hostname: $(hostname)"
    echo "Backup File: ${BACKUP_FILE}"
    echo "Git Commit: $(git log -1 --pretty=%H 2>/dev/null || echo 'N/A')"
    echo "Git Branch: $(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo 'N/A')"
    echo "Status: SUCCESS"
} >> "${APP_PATH}/storage/logs/deployments.log"

log_success "=== Deployment completed successfully! ==="
log_info "Backup location: ${BACKUP_FILE}"
log_info "Application URL: ${APP_URL}"

exit 0
