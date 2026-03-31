#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}   Starting Development with Hot Reload    ${NC}"
echo -e "${GREEN}===========================================${NC}"

# Check if npm dependencies are installed
if [ ! -d "node_modules" ]; then
    echo -e "${YELLOW}Installing npm dependencies...${NC}"
    npm install
fi

# Start Laravel server in background
echo -e "${YELLOW}Starting Laravel server...${NC}"
php artisan serve --host=127.0.0.1 --port=8000 &
LARAVEL_PID=$!

# Start Vite dev server
echo -e "${YELLOW}Starting Vite dev server with hot reload...${NC}"
echo -e "${GREEN}===========================================${NC}"
echo -e "${GREEN}Access the app at: http://127.0.0.1:8000${NC}"
echo -e "${GREEN}CSS/JS will auto-reload on changes${NC}"
echo -e "${GREEN}Press Ctrl+C to stop both servers${NC}"
echo -e "${GREEN}===========================================${NC}"

# Trap to kill both processes when script exits
trap "kill $LARAVEL_PID 2>/dev/null" EXIT

# Run Vite in foreground
npm run dev