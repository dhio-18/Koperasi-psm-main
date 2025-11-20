#!/bin/bash

# Koperasi PSM - Production Rollback Script
# Use this script to rollback to a previous deployment

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Logging
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
BACKUP_DIR="${APP_PATH}/storage/backups"

log_info "Production Rollback Tool"
log_info "========================"

# Check if backup directory exists
if [ ! -d "${BACKUP_DIR}" ]; then
    log_error "Backup directory not found: ${BACKUP_DIR}"
    exit 1
fi

# List available backups
log_info "Available backups:"
echo ""
ls -lhS "${BACKUP_DIR}"/backup_*.tar.gz | awk '{print $9, "(" $5 ")"}' | nl

echo ""
read -p "Enter backup number to restore (or 'q' to cancel): " backup_num

if [ "${backup_num}" = "q" ] || [ -z "${backup_num}" ]; then
    log_info "Rollback cancelled"
    exit 0
fi

# Get selected backup file
backup_file=$(ls -t "${BACKUP_DIR}"/backup_*.tar.gz | sed -n "${backup_num}p")

if [ -z "${backup_file}" ]; then
    log_error "Invalid backup number"
    exit 1
fi

log_warning "You are about to restore from: $(basename ${backup_file})"
read -p "Are you sure? (yes/no): " confirm

if [ "${confirm}" != "yes" ]; then
    log_info "Rollback cancelled"
    exit 0
fi

# Change to app directory
cd "${APP_PATH}"

log_info "Stopping queue workers..."
php artisan queue:restart || true

log_info "Restoring from backup..."
tar -xzf "${backup_file}" --warning=no-timestamp

log_info "Running migrations..."
php artisan migrate:rollback --step=1 || true

log_info "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

log_info "Restarting queue workers..."
php artisan queue:restart || true

log_success "Rollback completed!"
log_info "Please verify the application is working correctly"
log_info "Check logs: tail -f storage/logs/laravel.log"

exit 0
