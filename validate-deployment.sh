#!/bin/bash

# Koperasi PSM - Environment & Configuration Validator
# Run this before deployment to catch issues early

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
NC='\033[0m'

# Counters
PASSED=0
FAILED=0
WARNINGS=0

# Logging
log_header() {
    echo -e "${MAGENTA}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${MAGENTA}$1${NC}"
    echo -e "${MAGENTA}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
}

log_check() {
    echo -e "${BLUE}→${NC} $1"
}

log_pass() {
    echo -e "${GREEN}✓${NC} $1"
    ((PASSED++))
}

log_warn() {
    echo -e "${YELLOW}⚠${NC} $1"
    ((WARNINGS++))
}

log_fail() {
    echo -e "${RED}✗${NC} $1"
    ((FAILED++))
}

# Start
clear
log_header "Koperasi PSM - Pre-Deployment Validation"

# 1. Check PHP Installation
log_header "1. PHP & Dependencies"
log_check "Checking PHP installation..."
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1)
    log_pass "PHP installed: $PHP_VERSION"
else
    log_fail "PHP is not installed"
fi

# Check PHP version
if php -r 'exit(version_compare(PHP_VERSION, "8.2", ">=") ? 0 : 1);' 2>/dev/null; then
    log_pass "PHP version >= 8.2"
else
    log_warn "PHP version < 8.2 (recommended 8.2+)"
fi

# Check PHP extensions
EXTENSIONS=("mysql" "curl" "gd" "intl" "mbstring" "bcmath" "iconv")
for ext in "${EXTENSIONS[@]}"; do
    if php -m | grep -q "$ext"; then
        log_pass "PHP extension $ext is installed"
    else
        log_fail "PHP extension $ext is NOT installed"
    fi
done

# 2. Check Composer
log_header "2. Composer"
log_check "Checking Composer..."
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version)
    log_pass "Composer installed: $COMPOSER_VERSION"
else
    log_fail "Composer is not installed"
fi

# 3. Check Node.js & NPM
log_header "3. Node.js & NPM"
log_check "Checking Node.js..."
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    log_pass "Node.js installed: $NODE_VERSION"
else
    log_fail "Node.js is not installed"
fi

log_check "Checking NPM..."
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm -v)
    log_pass "NPM installed: $NPM_VERSION"
else
    log_fail "NPM is not installed"
fi

# 4. Check Project Files
log_header "4. Project Structure"
FILES=(
    "composer.json"
    "package.json"
    ".env.production"
    ".github/workflows/deploy.yml"
    "deploy.sh"
    "rollback.sh"
)

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        log_pass "File exists: $file"
    else
        log_fail "File missing: $file"
    fi
done

# 5. Check Environment Files
log_header "5. Environment Configuration"

log_check "Checking .env.production..."
if [ -f ".env.production" ]; then
    log_pass ".env.production exists"
    
    # Check required variables
    REQUIRED_VARS=("APP_NAME" "APP_ENV" "APP_URL" "DB_HOST" "DB_DATABASE" "DB_USERNAME")
    for var in "${REQUIRED_VARS[@]}"; do
        if grep -q "^$var=" .env.production; then
            log_pass ".env.production has $var"
        else
            log_warn ".env.production missing $var"
        fi
    done
    
    # Warn if secrets in file
    if grep -q "password\|PASSWORD\|secret\|SECRET\|key\|KEY" .env.production; then
        if ! grep -q "CHANGE_ME\|YOUR_\|PLACEHOLDER"; then
            log_warn ".env.production might contain real secrets (DANGER!)"
        fi
    fi
else
    log_fail ".env.production not found"
fi

log_check "Checking .env (local)..."
if [ -f ".env" ]; then
    log_pass ".env exists (local)"
else
    log_warn ".env not found (will be created during setup)"
fi

# 6. Check Git Status
log_header "6. Git Configuration"
log_check "Checking Git repository..."
if git rev-parse --git-dir > /dev/null 2>&1; then
    log_pass "Git repository initialized"
    
    # Check for sensitive files
    if git ls-files | grep -E "deploy_key|\.env$|\.env\.production\.local" > /dev/null; then
        log_fail "Sensitive files found in Git (DANGER!)"
    else
        log_pass "No sensitive files in Git"
    fi
else
    log_warn "Not a Git repository"
fi

# 7. Check Database
log_header "7. Database Configuration"
log_check "Checking MySQL/MariaDB..."
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version)
    log_pass "MySQL installed: $MYSQL_VERSION"
else
    log_warn "MySQL not installed (required for local testing)"
fi

# 8. Check File Permissions
log_header "8. File Permissions"
log_check "Checking deploy.sh permissions..."
if [ -x "deploy.sh" ]; then
    log_pass "deploy.sh is executable"
else
    log_warn "deploy.sh is not executable (chmod +x needed)"
fi

log_check "Checking rollback.sh permissions..."
if [ -x "rollback.sh" ]; then
    log_pass "rollback.sh is executable"
else
    log_warn "rollback.sh is not executable (chmod +x needed)"
fi

# 9. Check Dependencies
log_header "9. Project Dependencies"
log_check "Checking Composer dependencies..."
if [ -d "vendor" ]; then
    log_pass "vendor directory exists"
else
    log_warn "vendor directory not found (run composer install)"
fi

log_check "Checking Node dependencies..."
if [ -d "node_modules" ]; then
    log_pass "node_modules directory exists"
else
    log_warn "node_modules not found (run npm install)"
fi

# 10. Check GitHub Actions
log_header "10. GitHub Actions Workflows"
log_check "Checking workflows..."
if [ -f ".github/workflows/deploy.yml" ]; then
    log_pass "deploy.yml workflow exists"
    
    # Basic YAML validation
    if command -v python3 &> /dev/null; then
        if python3 -c "import yaml; yaml.safe_load(open('.github/workflows/deploy.yml'))" 2>/dev/null; then
            log_pass "deploy.yml is valid YAML"
        else
            log_fail "deploy.yml has YAML syntax errors"
        fi
    fi
else
    log_fail ".github/workflows/deploy.yml not found"
fi

if [ -f ".github/workflows/test.yml" ]; then
    log_pass "test.yml workflow exists"
else
    log_warn "test.yml workflow not found (optional)"
fi

# 11. Security Check
log_header "11. Security Checks"
log_check "Checking for hardcoded secrets..."

SENSITIVE_PATTERNS=("password=" "secret=" "api_key=" "token=" "PRIVATE_KEY")
FOUND_SECRETS=0

for pattern in "${SENSITIVE_PATTERNS[@]}"; do
    if grep -r "$pattern" --include="*.php" --include="*.js" --include="*.env" . 2>/dev/null | grep -v ".env.production" | grep -v ".env.example" > /dev/null; then
        log_fail "Potential hardcoded secret found: $pattern"
        ((FOUND_SECRETS++))
    fi
done

if [ $FOUND_SECRETS -eq 0 ]; then
    log_pass "No hardcoded secrets detected"
fi

log_check "Checking .gitignore..."
if grep -q "^\.env$" .gitignore 2>/dev/null; then
    log_pass ".gitignore has .env rule"
else
    log_fail ".gitignore missing .env rule"
fi

# 12. Summary
log_header "VALIDATION SUMMARY"

echo ""
echo -e "${GREEN}Passed: $PASSED${NC}"
if [ $WARNINGS -gt 0 ]; then
    echo -e "${YELLOW}Warnings: $WARNINGS${NC}"
fi
if [ $FAILED -gt 0 ]; then
    echo -e "${RED}Failed: $FAILED${NC}"
fi

echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✓ All critical checks passed!${NC}"
    echo ""
    echo -e "${BLUE}Next steps:${NC}"
    echo "1. Add GitHub Secrets (Settings → Secrets → Actions)"
    echo "2. Make scripts executable: chmod +x *.sh"
    echo "3. Push to main branch to trigger deployment"
    echo "4. Monitor GitHub Actions tab"
    exit 0
else
    echo -e "${RED}✗ Some checks failed. Please fix them before deploying.${NC}"
    exit 1
fi
