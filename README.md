# Inspection Booking System

A Laravel-based API for managing property inspection bookings, team availability, and tenant management.

## Features

- User Authentication and Authorization
- Tenant Management
- Team Management with Availability Scheduling
- Booking System with Conflict Prevention
- API Resources for Consistent Responses
- Form Request Validation
- Comprehensive Error Handling

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (for frontend development)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd inspection-booking
```

2. Install PHP dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inspection_booking
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run database migrations:
```bash
php artisan migrate
```

7. (Optional) Seed the database with sample data:
```bash
php artisan db:seed
```

8. Start the development server:
```bash
php artisan serve
```

## API Documentation

### Authentication Endpoints

- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user

### Tenant Endpoints

- `GET /api/tenants` - List all tenants
- `POST /api/tenants` - Create a new tenant
- `GET /api/tenants/{id}` - Get tenant details
- `PUT /api/tenants/{id}` - Update tenant
- `DELETE /api/tenants/{id}` - Delete tenant
- `GET /api/tenants/active` - Get active tenants

### Team Endpoints

- `GET /api/teams` - List all teams
- `POST /api/teams` - Create a new team
- `GET /api/teams/{id}` - Get team details
- `PUT /api/teams/{id}` - Update team
- `DELETE /api/teams/{id}` - Delete team
- `GET /api/teams/active` - Get active teams
- `GET /api/teams/{id}/availability` - Get team availability
- `POST /api/teams/{id}/availability` - Set team availability

### Booking Endpoints

- `GET /api/bookings` - List all bookings
- `POST /api/bookings` - Create a new booking
- `GET /api/bookings/{id}` - Get booking details
- `PUT /api/bookings/{id}` - Update booking
- `DELETE /api/bookings/{id}` - Delete booking
- `GET /api/bookings/tenant/{id}` - Get tenant's bookings
- `GET /api/bookings/team/{id}` - Get team's bookings
- `GET /api/bookings/upcoming/{tenant_id}` - Get upcoming bookings

## Project Structure

```
inspection-booking/
├── app/
│   ├── Modules/
│   │   ├── Auth/
│   │   ├── Bookings/
│   │   ├── Teams/
│   │   └── Tenants/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   └── Exceptions/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── tests/
```

## Error Handling

The API uses consistent error responses:

```json
{
    "message": "Error message",
    "errors": {
        "field": ["Error details"]
    }
}
```

Common HTTP Status Codes:
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## Development

1. Create a new branch for your feature:
```bash
git checkout -b feature/your-feature-name
```

2. Make your changes and commit:
```bash
git add .
git commit -m "Description of changes"
```

3. Push to your branch:
```bash
git push origin feature/your-feature-name
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
