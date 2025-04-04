# Liftr - Multi-Tenant Weightlifting Gym Booking Platform

Liftr is a Laravel-based multi-tenant online booking platform designed for weightlifting gyms. It allows gym owners and solo trainers to manage their bookings, customers, and sessions with a seamless multi-tenant architecture.

## Features

### Super Admin
- Centralized application management
- Accept or reject tenant (Landlord) registrations
- Assign unique database, domain, and tenancy upon acceptance

### Tenants (Landlords)
- **Solo Trainers/Coaches (Normal Plan):** Can manage 1-3 customers
- **Gym Owners (Premium Plan):** Can manage 3+ users with additional features
- Customize the application to their needs

### Normal Users (Gym Clients)
- Register and log in to a Landlord's platform
- Book and start weightlifting sessions

## Installation Guide

### Prerequisites
Ensure you have the following installed on your system:
- PHP (>= 8.0)
- Composer
- Laravel (>= 10)
- MySQL or PostgreSQL
- Node.js & NPM (for frontend assets)

### Steps to Install

1. **Clone the Repository**
   ```sh
   git clone https://github.com/your-repo/liftr.git
   cd liftr
   ```

2. **Install Dependencies**
   ```sh
   composer install
   npm install && npm run dev
   ```

3. **Set Up Environment**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   - Update `.env` file with database credentials

5. **Run Migrations & Seeders**
   ```sh
   php artisan migrate --seed
   ```

6. **Set Up Tenancy**
   ```sh
   php artisan tenants:install
   ```

7. **Serve the Application**
   ```sh
   php artisan serve
   ```

## Multi-Tenancy Setup
Liftr uses Laravel's multi-tenancy package to create isolated databases for each landlord.
- A new tenant database is created upon super admin approval.
- Each tenant gets a unique domain and access to their gym management panel.

## Contribution
Feel free to contribute by forking the repository and submitting pull requests!

## License
This project is licensed under the MIT License.

