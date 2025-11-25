#!/usr/bin/env bash
set -euo pipefail

###############################################
# SmartyURL Auto Setup Script for Debian 12
# Target OS : Debian 12 (bookworm)
# PHP       : 8.2 (default in Debian 12)
#
# This script: It assumes a fresh system install, then:
#   - Installs nginx, MariaDB, PHP 8.2 and required extensions
#   - Installs Composer and Certbot
#   - Creates SmartyURL database and user
#   - Deploys SmartyURL via Composer to /var/www/smartyurl
#   - Creates an Nginx vhost and issues a Let's Encrypt certificate
#
# NOTE:
#   - Run this script as root.
#   - Environment (CI, .env fine-tuning, and first SuperAdmin user)
#     still require some manual actions after the script finishes.
#
# Copyright (c) 2025 Extendy LTD
# License: MIT
# Author: Mohammed AlShannaq <mohd@extendy.uk>
###############################################

# Define some colors for pretty output
RED='\033[0;31m'   # Red text
YELLOW='\033[1;33m' # Yellow text (optional for less severe notes)
NC='\033[0m'       # No Color (reset)

# --------- Configuration (edit these) ---------
DOMAIN="smartyurl.extendy.xyz"          # Default domain, will be overridden by user input if provided
ADMIN_EMAIL="admin@example.com"         # Email used for Let's Encrypt and system notifications

DB_NAME="smartyurl"
DB_USER="smartyurl_user"
DB_PASS="ChangeThisStrongPassword!"     # Change this to a strong password before using in production

WEB_ROOT="/var/www/smartyurl"
NGINX_CONF_DIR="/etc/nginx/sites-available"
NGINX_ENABLED_DIR="/etc/nginx/sites-enabled"
LOG_NAME="smartyurl"
# ---------------------------------------------

# Check root
if [[ "$(id -u)" -ne 0 ]]; then
  echo "This script must be run as root. Exiting."
  exit 1
fi


# ---------------------------------------------
# Safety confirmation before proceeding
# This script installs SmartyURL from the latest stable release.
# It is intended for development/staging purposes only, not for production.
# It assumes a fresh operating system and will make significant changes.
# It is recommended to run this on a container or staging server.
# Ask the user to confirm before continuing.
# ---------------------------------------------
# Use -e flag so echo interprets escape sequences
echo -e "${YELLOW}This script must be use on Fresh OS only , be carful! ${NC}"
echo -e "${RED}WARNING:${NC} This script will install SmartyURL (latest stable release) for developer use only."
echo -e "${RED}It assumes a fresh system and will apply many changes in Operating system.${NC} with ${YELLOW} NO UNDO! ${NC}"
echo -e "Please run it in a container or on a staging server, not in production."
read -rp "Are you sure you want to proceed? Type 'yes' to continue: " PROCEED
if [[ \"${PROCEED,,}\" != \"yes\" ]]; then
    echo "Aborting installation."
    exit 1
fi



echo "==============================================="
echo "SmartyURL Setup on Debian 12 (Bookworm)"
echo "==============================================="

# Ask for domain interactively (optional)
read -rp "Enter domain for SmartyURL [${DOMAIN}]: " INPUT_DOMAIN
if [[ -n "${INPUT_DOMAIN}" ]]; then
  DOMAIN="${INPUT_DOMAIN}"
fi

read -rp "Enter email for Let's Encrypt notifications [${ADMIN_EMAIL}]: " INPUT_EMAIL
if [[ -n "${INPUT_EMAIL}" ]]; then
  ADMIN_EMAIL="${INPUT_EMAIL}"
fi

echo
echo "Using domain: ${DOMAIN}"
echo "Using admin email: ${ADMIN_EMAIL}"
echo "Database: ${DB_NAME}, User: ${DB_USER}"
echo

# Export to avoid Composer root warning
export COMPOSER_ALLOW_SUPERUSER=1

echo "[*] Updating system packages..."
apt update && apt upgrade -y

echo "[*] Installing base packages (gnupg, ca-certificates, lsb-release, debconf-utils)..."
apt install -y gnupg ca-certificates lsb-release debconf-utils nano wget curl

echo "[*] Installing nginx, MariaDB, git, unzip, Composer, Certbot..."
apt install -y nginx mariadb-server mariadb-client git unzip \
               composer certbot python3-certbot-nginx

echo "[*] Installing PHP 8.2 and required extensions..."
apt install -y php php-fpm php-cli php-mbstring php-xml \
               php-intl php-curl php-mysql php-zip php-gmp \
               php-bcmath php-readline php-opcache

echo "[*] Enabling and starting PHP-FPM, MariaDB, and Nginx services..."
# PHP-FPM service on Debian 12 is php8.2-fpm
systemctl enable php8.2-fpm || true
systemctl restart php8.2-fpm || true

systemctl enable mariadb
systemctl restart mariadb

systemctl enable nginx
systemctl restart nginx

echo "[*] Detecting PHP-FPM socket..."
PHP_FPM_SOCK=$(find /run/php -maxdepth 1 -name "php8.2-fpm.sock" | head -n1 || true)
if [[ -z "${PHP_FPM_SOCK}" ]]; then
  PHP_FPM_SOCK=$(find /run/php -maxdepth 1 -name "php*-fpm.sock" | head -n1 || true)
fi
if [[ -z "${PHP_FPM_SOCK}" ]]; then
  echo "[!] Could not detect PHP-FPM socket. Falling back to /run/php/php8.2-fpm.sock"
  PHP_FPM_SOCK="/run/php/php8.2-fpm.sock"
fi
echo "[*] Using PHP-FPM socket: ${PHP_FPM_SOCK}"

echo "[*] Creating SmartyURL database and user in MariaDB..."
mysql -u root <<MYSQL_EOF
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
MYSQL_EOF

echo "[*] Creating document root directory at ${WEB_ROOT}..."
mkdir -p "$(dirname "${WEB_ROOT}")"

if [[ -d "${WEB_ROOT}" ]]; then
  echo "[!] Directory ${WEB_ROOT} already exists."
  read -rp "Do you want to overwrite existing SmartyURL installation? [y/N]: " OVERWRITE
  OVERWRITE=${OVERWRITE:-n}
  if [[ "${OVERWRITE,,}" != "y" ]]; then
    echo "[!] Skipping Composer installation of SmartyURL. Make sure existing code is valid."
  else
    echo "[*] Removing existing directory ${WEB_ROOT}..."
    rm -rf "${WEB_ROOT}"
  fi
fi

if [[ ! -d "${WEB_ROOT}" ]]; then
  echo "[*] Installing SmartyURL via Composer..."
  cd /var/www
  composer create-project extendy/smartyurl "$(basename "${WEB_ROOT}")" --no-interaction
else
  echo "[*] SmartyURL directory already exists. Skipping create-project."
fi

cd "${WEB_ROOT}"

echo "[*] Setting writable directory permissions..."
mkdir -p writable
chown -R www-data:www-data writable
chmod -R 775 writable

echo "[*] Preparing .env file..."
if [[ ! -f "${WEB_ROOT}/.env" ]]; then
  cp "${WEB_ROOT}/env" "${WEB_ROOT}/.env"
fi

# Update environment variables in .env file
# Assumes WEB_ROOT, DOMAIN, DB_NAME, DB_USER, and DB_PASS are already defined

echo "[*] Updating .env file for  ${DOMAIN}..."

# CI_ENVIRONMENT: set to production
sed -i "s/^CI_ENVIRONMENT *=.*/CI_ENVIRONMENT = production/" "${WEB_ROOT}/.env"

# Base URL
sed -i "s~^app\.baseURL *=.*~app.baseURL = \"https://${DOMAIN}/\"~" "${WEB_ROOT}/.env"

# Cookie domain
sed -i "s~^cookie\.domain *=.*~cookie.domain = \"${DOMAIN}\"~" "${WEB_ROOT}/.env"

# 404 page setting
sed -i "s~^smartyurl\.mainpagefor404notfound *=.*~smartyurl.mainpagefor404notfound = \"/\"~" "${WEB_ROOT}/.env"

# Database hostname
sed -i "s/^[[:space:]]*database\.default\.hostname *=.*/database.default.hostname = localhost/" "${WEB_ROOT}/.env"

# Database name
sed -i "s/^[[:space:]]*database\.default\.database *=.*/database.default.database = ${DB_NAME}/" "${WEB_ROOT}/.env"

# Database username
sed -i "s/^[[:space:]]*database\.default\.username *=.*/database.default.username = ${DB_USER}/" "${WEB_ROOT}/.env"

# Database password
sed -i "s/^[[:space:]]*database\.default\.password *=.*/database.default.password = ${DB_PASS}/" "${WEB_ROOT}/.env"


echo "[*] Creating Nginx server block for ${DOMAIN}..."
NGINX_CONF_PATH="${NGINX_CONF_DIR}/${DOMAIN}.conf"

cat > "${NGINX_CONF_PATH}" <<NGINX_EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};

    root ${WEB_ROOT}/public;
    index index.php index.html;

    access_log /var/log/nginx/${LOG_NAME}.access.log;
    error_log  /var/log/nginx/${LOG_NAME}.error.log;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_FPM_SOCK};
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|webp)\$ {
        expires max;
        log_not_found off;
    }
}
NGINX_EOF

echo "[*] Enabling Nginx site..."
ln -sf "${NGINX_CONF_PATH}" "${NGINX_ENABLED_DIR}/${DOMAIN}.conf"

echo "[*] Testing Nginx configuration..."
nginx -t

echo "[*] Reloading Nginx..."
systemctl reload nginx

echo "[*] Issuing Let's Encrypt certificate via Certbot..."
certbot --nginx \
  -d "${DOMAIN}" \
  --non-interactive \
  --agree-tos \
  -m "${ADMIN_EMAIL}" \
  --redirect || echo "[!] Certbot failed. Check logs and DNS configuration."

echo "[*] Final Nginx configuration test..."
nginx -t
systemctl reload nginx

echo "[*] Running SmartyURL Database migration for ${DOMAIN}..."
cd "${WEB_ROOT}" || exit 1
php spark migrate --all

echo
echo "==============================================="
echo "SmartyURL base installation completed."
echo "Next manual steps:"
echo " 1) Review ${WEB_ROOT}/.env and adjust as needed:"
echo "      - CI_ENVIRONMENT (development/production)"
echo "      - app.baseURL"
echo "      - cookie.domain"
echo "      - smartyurl.mainpagefor404notfound"
echo "      - database.default.* values if changed"
echo
echo " 2) Create first user and assign superadmin group:"
echo "      cd ${WEB_ROOT}"
echo "      php spark shield:user create"
echo "      php spark shield:user activate"
echo "      php spark shield:user addgroup"
echo "         (when asked about group name, enter: superadmin)"
echo
echo " 4) If you want debug mode:"
echo "      Set CI_ENVIRONMENT = development in .env"
echo
echo "Script finished."
echo "==============================================="
