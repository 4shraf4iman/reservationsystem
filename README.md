# Reservation System

This project is a **Reservation System** built with **Laravel** (backend) and **Vue.js** (frontend).

## Prerequisites
Before setting up the project, ensure you have the following installed:

- **PHP** & **Composer**
- **Laragon** or **XAMPP**
- **Node.js & npm**

---

## Installation & Setup

### 1. Clone the Repository
Open **Command Prompt (CMD)** and run:
```sh
git clone https://github.com/4shraf4iman/reservationsystem.git
```
Navigate into the project folder:
```sh
cd reservationsystem
```

### 2. Setup Environment Variables
Copy the example environment file and rename it:
```sh
cp .env.example .env
```
Open the `.env` file and configure the **database name, username, and password**.

### 3. Configure the Database
- Open **XAMPP** or **Laragon**.
- Create a new database with the **same name** as in `.env`.
- Set the username as **root** and leave the password **empty**.

---

## Backend Setup (Laravel)

Run the following commands inside the `reservationsystem` directory:

1. Install dependencies:
   ```sh
   composer install
   ```
   If authentication is required, run:
   ```sh
   composer config --global --auth github-oauth.github.com [your-token]
   composer install
   ```

2. Run migrations and seed the database:
   ```sh
   php artisan migrate:fresh --seed
   ```

3. Start the Laravel development server:
   ```sh
   php artisan serve
   ```

---

## Frontend Setup (Vue.js)

1. Navigate to the frontend folder:
   ```sh
   cd "kidosys front end"
   ```

2. Copy the environment file:
   ```sh
   cp .env-example .env
   ```

3. Install dependencies:
   ```sh
   npm install
   ```

4. Start the development server:
   ```sh
   npm run dev
   ```

---

## Running the Project
You need **two CMD instances** running:
1. Laravel (`php artisan serve`)
2. Vue.js (`npm run dev`)

### Available Pages:
- **Booking Page**: [http://localhost:5173/reserve](http://localhost:5173/reserve)
- **Admin Login Page**: [http://localhost:5173/](http://localhost:5173/)
- **Admin Reservation Page**: [http://localhost:5173/admin](http://localhost:5173/admin)

### Admin Credentials:
```
Email: superadmin@kiddosys.com
Password: admin
```

---

### 🎉 Your Reservation System is Now Ready!

