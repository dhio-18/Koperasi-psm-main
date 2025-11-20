#!/bin/bash

# Koperasi PSM - Production Health Check Script
# Run this periodically to monitor application health

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
APP_URL="${APP_URL:-https://koperasi-psm.com}"
LOG_FILE="${LOG_FILE:-./storage/logs/health-check.log}"

# Logging
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a "${LOG_FILE}"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "${LOG_FILE}"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "${LOG_FILE}"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "${LOG_FILE}"
}

# Initialize log
{
    echo "============================================"
    echo "Health Check: $(date)"
    echo "============================================"
} >> "${LOG_FILE}"

log_info "Starting health checks..."

# Counter
PASSED=0
FAILED=0

# 1. Check HTTP connectivity
log_info "1. Checking HTTP connectivity..."
if curl -f -s -o /dev/null -w "%{http_code}" "${APP_URL}" | grep -q "200\|301\|302"; then
    log_success "HTTP connectivity: OK"
    ((PASSED++))
else
    log_error "HTTP connectivity: FAILED"
    ((FAILED++))
fi

# 2. Check HTTPS
log_info "2. Checking HTTPS..."
if curl -f -s -o /dev/null --connect-timeout 5 "${APP_URL}" 2>/dev/null; then
    log_success "HTTPS: OK"
    ((PASSED++))
else
    log_error "HTTPS: FAILED"
    ((FAILED++))
fi

# 3. Check response time
log_info "3. Checking response time..."
RESPONSE_TIME=$(curl -f -s -o /dev/null -w "%{time_total}" "${APP_URL}" 2>/dev/null | cut -d. -f1)
if [ -n "${RESPONSE_TIME}" ] && [ "${RESPONSE_TIME}" -lt 5 ]; then
    log_success "Response time: ${RESPONSE_TIME}s (OK)"
    ((PASSED++))
else
    log_warning "Response time: ${RESPONSE_TIME}s (might be slow)"
    ((PASSED++))
fi

# 4. Check database connection (requires artisan)
if command -v php &> /dev/null; then
    log_info "4. Checking database connection..."
    if php artisan tinker <<< "DB::connection()->getPdo();" 2>&1 | grep -q "Pdo"; then
        log_success "Database connection: OK"
        ((PASSED++))
    else
        log_error "Database connection: FAILED"
        ((FAILED++))
    fi
fi

# 5. Check disk space
log_info "5. Checking disk space..."
DISK_USAGE=$(df . | awk 'NR==2 {print $5}' | sed 's/%//')
if [ "${DISK_USAGE}" -lt 80 ]; then
    log_success "Disk usage: ${DISK_USAGE}% (OK)"
    ((PASSED++))
else
    log_warning "Disk usage: ${DISK_USAGE}% (HIGH)"
    ((FAILED++))
fi

# 6. Check log file size
log_info "6. Checking log file size..."
if [ -f "storage/logs/laravel.log" ]; then
    LOG_SIZE=$(du -h storage/logs/laravel.log | awk '{print $1}')
    if [ -n "${LOG_SIZE}" ]; then
        log_success "Log file size: ${LOG_SIZE}"
        ((PASSED++))
    fi
fi

# 7. Check application file permissions
log_info "7. Checking file permissions..."
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    log_success "File permissions: OK"
    ((PASSED++))
else
    log_warning "File permissions: NEEDS ATTENTION"
fi

# Summary
echo "" | tee -a "${LOG_FILE}"
log_info "============================================"
log_info "Health Check Summary:"
log_success "Passed: ${PASSED}"
if [ "${FAILED}" -gt 0 ]; then
    log_error "Failed: ${FAILED}"
fi
log_info "============================================"
echo "" | tee -a "${LOG_FILE}"

# Exit with appropriate code
if [ "${FAILED}" -eq 0 ]; then
    log_success "All checks passed!"
    exit 0
else
    log_error "Some checks failed!"
    exit 1
fi
