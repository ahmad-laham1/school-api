# 🏫 School Management API (Laravel)

This is the **backend API** for the School Management System.  
It handles authentication (JWT), role-based access (Admin, Teacher, Student), and APIs for managing users, classrooms, and students.

---

## 🚀 Features

-   🔑 JWT Authentication
-   👨‍💻 Role-based access:
    -   **Admin** → manage users, classrooms, students
    -   **Teacher** → manage their classrooms & students
    -   **Student** → view & update their profile
-   📚 CRUD APIs for Users, Students, Classrooms

---

## 🛠 Tech Stack

-   Laravel 10+
-   MySQL
-   JWT Auth (`tymon/jwt-auth`)

---

## ⚙️ Local Installation

### 1. Clone repo

```bash
git clone https://github.com/<your-username>/school-backend.git
cd school-backend
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure enviornment

copy the example file and update DB credentials:

### 4. Generate keys

```bash
php artisan key:generate
php artisan jwt:secret
```

### 5. Run migrations

```bash
php artisan migrate --seed
```

### 6. Serve the app

using xampp, herd, or wamp

\| Variable | Purpose |

> \|---|---|
> \| APP_KEY | Laravel application key |
> \| JWT_SECRET | Secret for JWT token generation |
> \| DB_HOST, etc. | MySQL connection info |
