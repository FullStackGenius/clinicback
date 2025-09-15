**Clinic Account Admin & API Management**

The **Clinic Account Admin Panel** is a robust backend interface built with **Laravel**, designed for platform administrators to manage all aspects of the freelance marketplace efficiently. It also powers the **API endpoints** used by the React.js frontend.

Key functionalities include:

* **User Management**: Oversee both clients and freelancers, including role assignments, account verification, and monitoring platform activity.

* **Payment & Escrow Control**: Manage financial transactions securely via Stripe integration. Monitor escrow payments, approve releases, and handle refunds when necessary.

* **API Management**: Serve as the central hub for all backend API endpoints. Admin can manage, monitor, and troubleshoot API requests to ensure smooth communication between the frontend and backend.

The Admin Panel ensures **secure, efficient, and reliable operation** of the Clinic Account platform while maintaining seamless integration with the frontend application.



## 🔵 Laravel Project Setup (Backend/API)

1. **Clone repo**

   ```bash
   git clone <backend-repo-url>
   cd backend-project
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Copy `.env.example` to `.env`**

   ```bash
   cp .env.example .env
   ```

4. **Generate application key**

   ```bash
   php artisan key:generate
   ```

5. **Set up database**

   * Create a database in MySQL/PostgreSQL (e.g. `clinic_api`).
   * Update `.env` with DB credentials:

     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=projectmanagment
     DB_USERNAME=root
     DB_PASSWORD=
     ```

6. **Run migrations & seeders**

   ```bash
   php artisan migrate --seed
   ```

7. **Run backend server**

   ```bash
   php artisan serve
   ```

   → Runs at `http://127.0.0.1:8000`

