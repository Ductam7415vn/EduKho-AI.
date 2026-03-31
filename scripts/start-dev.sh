#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}   Starting Laravel Development Server     ${NC}"
echo -e "${GREEN}===========================================${NC}"

# Check if npm dependencies are installed
if [ ! -d "node_modules" ]; then
    echo -e "${YELLOW}Installing npm dependencies...${NC}"
    npm install
fi

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}Creating .env file...${NC}"
    cp .env.example .env
    php artisan key:generate
fi

# Create SQLite database if not exists
if [ ! -f database/database.sqlite ]; then
    echo -e "${YELLOW}Creating SQLite database...${NC}"
    touch database/database.sqlite
fi

# Check if database is empty or needs migration
echo -e "${YELLOW}Checking database status...${NC}"
if ! php artisan migrate:status &>/dev/null; then
    echo -e "${YELLOW}Database needs initialization. Running migrations and seeders...${NC}"
    php artisan migrate:fresh --seed --force
else
    echo -e "${GREEN}Database already initialized.${NC}"
fi

# Build CSS assets
echo -e "${YELLOW}Building CSS assets...${NC}"
npm run build

# Clear cache
echo -e "${YELLOW}Clearing cache...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start server
echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}Starting server at http://127.0.0.1:8000${NC}"
echo -e "${GREEN}===========================================${NC}"
echo -e "${YELLOW}Login credentials:${NC}"
echo -e "Admin: admin@truong.edu.vn / password"
echo -e "Teacher: lan.tran@truong.edu.vn / password"
echo -e "${GREEN}===========================================${NC}"

php artisan serve --host=127.0.0.1 --port=8000