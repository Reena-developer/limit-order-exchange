# -------------------------------
# Clone the repository
# -------------------------------
git clone https://github.com/Reena-developer/limit-order-exchange.git
cd limit-order-exchange

# -------------------------------
# Backend Setup (Laravel)
# -------------------------------
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Update backend/.env with your config:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=your_db_user
# DB_PASSWORD=your_db_password
# PUSHER_APP_ID=your_pusher_app_id
# PUSHER_APP_KEY=your_pusher_key
# PUSHER_APP_SECRET=your_pusher_secret
# PUSHER_APP_CLUSTER=your_pusher_cluster

# Generate application key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Serve backend
php artisan serve
# Backend URL: http://127.0.0.1:8000

# -------------------------------
# Frontend Setup (Vue 3)
# -------------------------------
cd ../frontend

# Install JS dependencies
npm install

# Update frontend/.env with your config:
# VITE_API_BASE_URL=http://127.0.0.1:8000/api
# VITE_PUSHER_APP_KEY=your_pusher_key
# VITE_PUSHER_APP_CLUSTER=your_pusher_cluster

# Serve frontend
npm run dev
# Frontend URL: http://localhost:5173
