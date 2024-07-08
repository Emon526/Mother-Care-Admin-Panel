#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Function to kill processes using a specific port
clear_port() {
  local port=$1
  echo "Checking for processes using port $port..."
  if lsof -Pi :$port -sTCP:LISTEN -t >/dev/null; then
    echo "Port $port is in use. Killing process..."
    lsof -Pi :$port -sTCP:LISTEN -t | xargs kill -9
    echo "Port $port cleared."
  else
    echo "Port $port is not in use."
  fi
}

# Clear port 8000
clear_port 8000

# Step 1: Run composer update to ensure all dependencies are compatible with the current PHP version
echo "Running composer update..."
composer update

# Step 2: Run composer install
echo "Running composer install..."
composer install

# Step 3: Run npm install
echo "Running npm install..."
npm install

# Step 4: Run npm update
echo "Running npm update..."
npm update

# Step 5: Run npm run dev in the background
echo "Running npm run dev..."
npm run dev &
npm_run_dev_pid=$!

# Wait for a few seconds to ensure npm run dev has started
echo "Waiting for npm run dev to initialize..."
sleep 10

# Step 6: Set Laravel environment to local
echo "Setting Laravel environment to local..."
export APP_ENV=local
export APP_DEBUG=true

# Step 7: Cache Laravel configuration
echo "Caching Laravel configuration..."
php artisan config:cache

# Step 8: Run php artisan serve on localhost and port 8000
echo "Running php artisan serve on http://localhost:8000..."
php artisan serve --host=localhost --port=8000 &

# Keep the script running to keep the server up
echo "Development server is running........"
wait
