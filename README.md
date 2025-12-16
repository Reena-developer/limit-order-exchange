# Limit-Order Exchange Mini Engine

A full-stack **limit-order exchange mini engine** built with **Laravel API** (backend) and **Vue 3** (frontend).
The system focuses on **financial data integrity**, **real-time order matching**, and **scalable architecture**.

---

## 🚀 Features

* **Limit Buy/Sell Orders** – Place and manage limit orders
* **Order Matching Engine** – Automatic trade execution based on **price-time priority**
* **Real-Time Order Book Updates** – Live updates using **WebSockets**
* **Trade History Tracking** – View executed trades
* **Filtering & Sorting** – Advanced order filtering and sorting
* **Graceful 401 Handling** – Unauthorized API request handling
* **Client & Server Validation** – Full validation on frontend and backend
* **Modular Architecture** – Clean separation of backend and frontend logic

---

## 🛠 Tech Stack

### Backend

* **Laravel** 10.x
* **PHP** 8.2

### Frontend

* **Vue 3**
* **Vite**
* **TanStack Table**

### Other

* **Database:** MySQL
* **Realtime:** Laravel Echo, Pusher
* **API Style:** REST
* **Version Control:** Git

---

## ⚙️ Installation & Setup

### Backend Setup

#### 1️⃣ Clone the repository

```bash
git clone https://github.com/Reena-developer/limit-order-exchange.git
cd limit-order-exchange/backend
```

#### 2️⃣ Install dependencies

```bash
composer install
```

#### 3️⃣ Configure environment variables

Create and configure the `.env` file:

```env
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_pusher_cluster
```

#### 4️⃣ Generate application key

```bash
php artisan key:generate
```

#### 5️⃣ Run migrations and seeders

```bash
php artisan migrate --seed
```

#### 6️⃣ Start the backend server

```bash
php artisan serve
```

📌 Backend API will be available at:

```
http://127.0.0.1:8000
```

---

### Frontend Setup

#### 1️⃣ Navigate to frontend directory

```bash
cd ../frontend
```

#### 2️⃣ Install dependencies

```bash
npm install
```

#### 3️⃣ Configure frontend environment variables

Create a `.env` file:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api/v1
VITE_PUSHER_APP_KEY=your_pusher_key
VITE_PUSHER_APP_CLUSTER=your_pusher_cluster
```

#### 4️⃣ Run the frontend development server

```bash
npm run dev
```

📌 Frontend will be available at:

```
http://localhost:5173
```

---

## 👩‍💻 Author

**Reena Hathaliya**
GitHub: [https://github.com/Reena-developer](https://github.com/Reena-developer)
