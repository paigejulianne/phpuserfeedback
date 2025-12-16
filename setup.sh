#!/bin/bash

# Configuration
DB_NAME="phpuserfeedback"
# Allow overriding DB creds via env vars or default to typical local setup
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}" 

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== PHPUserFeedback Setup ===${NC}"

# 1. Install Dependencies
echo -e "\n${GREEN}[1/4] Installing Composer Dependencies...${NC}"
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader
else
    echo -e "${RED}Error: Composer is not installed. Please install composer first.${NC}"
    exit 1
fi

# 2. Database Setup
echo -e "\n${GREEN}[2/4] Setting up Database ($DB_NAME)...${NC}"
read -p "Enter Database User [current: $DB_USER]: " input_user
DB_USER="${input_user:-$DB_USER}"

echo "Enter Database Password:"
read -s DB_PASS

# Create DB if not exists
mysql -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "Database created or already exists."
else
    echo -e "${RED}Failed to create database. Check credentials.${NC}"
    exit 1
fi

# Import Schema
mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < sql/schema.sql 2>/dev/null
if [ $? -eq 0 ]; then
    echo "Schema imported successfully."
else
    echo -e "${RED}Failed to import schema.${NC}"
    exit 1
fi

# 3. Seed Admin User
echo -e "\n${GREEN}[3/4] Seeding Admin User...${NC}"

# Generate PHP script to seed admin
SEED_SCRIPT="
<?php
require_once __DIR__ . '/vendor/autoload.php';

// Mock Config for the Seeder
\$db_config = [
    'db_host' => 'localhost',
    'db_name' => '$DB_NAME',
    'db_user' => '$DB_USER',
    'db_pass' => '$DB_PASS'
];

// Helper to manually connect since we can't easily rely on app config file which might have diff creds
try {
    \$conn = new PDO(\"mysql:host={\$db_config['db_host']};dbname={\$db_config['db_name']}\", \$db_config['db_user'], \$db_config['db_pass']);
    \$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if admin exists
    \$stmt = \$conn->prepare(\"SELECT id FROM users WHERE username = 'admin'\");
    \$stmt->execute();
    
    if (\$stmt->rowCount() == 0) {
        \$pass = password_hash('admin', PASSWORD_DEFAULT);
        \$sql = \"INSERT INTO users (username, email, password_hash, role) VALUES ('admin', 'admin@example.com', '\$pass', 'admin')\";
        \$conn->exec(\$sql);
        echo 'Admin user created (admin/admin)';
    } else {
        echo 'Admin user already exists';
    }
} catch(PDOException \$e) {
    echo 'Error seeding admin: ' . \$e->getMessage();
    exit(1);
}
"
# Run the seed script
php -r "$SEED_SCRIPT"

# 4. Final Instructions
echo -e "\n${GREEN}[4/4] Configuration & Instructions${NC}"

# Update config.php with these credentials? 
# We can attempt to sed replace, but it's risky. Let's just instruct the user.
echo -e "${BLUE}IMPORTANT:${NC} Please update 'src/Config/config.php' with the database credentials you just used."

echo -e "\n${BLUE}=== Setup Complete ===${NC}"
echo -e "You can now access your application."
echo -e "⚠️  ${RED}Web Server Configuration Requirement:${NC}"
echo -e "Please configure your web server (Apache/Nginx) to serve the application from the ${BLUE}/public${NC} directory."
echo -e "Pointing it to the project root is insecure and may expose source code."
echo -e "\nDefault Admin Credentials:"
echo -e "Username: ${BLUE}admin${NC}"
echo -e "Password: ${BLUE}admin${NC}"
echo -e "\nEnjoy!"
