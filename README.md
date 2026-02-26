# Hotel Booking System – Backend

## 1. Overview

This is the backend API for the Hotel Booking System.

The backend handles:

- User authentication
- Admin authentication
- Hotel management
- Booking processing
- RESTful API services

---

## 2. Tech Stack

- Laravel 10+
- PHP 8.2+
- MySQL
- Composer 2+
- PHP-FPM
- Nginx (recommended)

---

## 3. Installation

### Install dependencies

```bash
composer install
```

---

### Environment setup

Copy environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

---

## 4. Database Configuration

Edit `.env` file:

```
DB_DATABASE=hotel_booking
DB_USERNAME=root
DB_PASSWORD=your_password
```

Run migrations:

```bash
php artisan migrate
```

(Optional) Seed demo data:

```bash
php artisan db:seed
```

---

## 5. Run Development Server

```bash
php artisan serve
```

Default URL:

```
http://127.0.0.1:8000
```

---

## 6. API Base URL

```
/api
```

Example endpoints:

```
POST /api/login
GET /api/hotels
POST /api/bookings
```

---

## 7. Demo Accounts

### User Account
- Email: 2251052022dun@ou.edu.vn
- Password: Admin@123

### Admin Account
- Email: mathilde.emard@example.com
- Password: 123456

---

## 8. Clear Cache (If Needed)

If changes are not reflected:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 9. Deployment Notes

- Document root must point to `/public`
- Use PHP-FPM with Nginx
- Ensure `storage/` and `bootstrap/cache/` are writable
- Configure Nginx to route `/api` to Laravel
- Project is in progress, please fogive me if it does not work.

---

## 10. Link Demo
User Demo: http://www.hotel.duckou.id.vn/  
Admin Demo: http://www.hotel.duckou.id.vn/admin/login 
