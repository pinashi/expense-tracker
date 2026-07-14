# Expense Tracker API

A RESTful API for tracking personal expenses, built with Laravel and Docker.

## Tech Stack

- PHP 8.3
- Laravel 13
- MySQL 8.0
- Laravel Sanctum (API authentication)
- Docker / Laravel Sail

## Getting Started

### Requirements

- Docker

### Installation

1. Clone the repository:
```bash
git clone https://github.com/pinashi/expense-tracker.git
cd expense-tracker
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Start the containers:
```bash
./vendor/bin/sail up -d
```

4. Generate application key:
```bash
./vendor/bin/sail artisan key:generate
```

5. Run migrations and seeders:
```bash
./vendor/bin/sail artisan migrate --seed
```

The API will be available at `http://localhost`.

## Running Tests

```bash
./vendor/bin/sail artisan test
```

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Login and get token |
| POST | `/api/logout` | Logout (requires auth) |
| GET | `/api/me` | Get current user (requires auth) |

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/categories` | List all categories |
| POST | `/api/categories` | Create a category |
| DELETE | `/api/categories/{id}` | Delete a category |

### Expenses

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/expenses` | List all expenses |
| POST | `/api/expenses` | Create an expense |
| PUT | `/api/expenses/{id}` | Update an expense |
| DELETE | `/api/expenses/{id}` | Delete an expense |
| GET | `/api/expenses/summary` | Monthly summary |

### Filtering Expenses

```
GET /api/expenses?category_id=1
GET /api/expenses?date_from=2026-07-01&date_to=2026-07-31
GET /api/expenses?category_id=1&date_from=2026-07-01&date_to=2026-07-31
```

## Example Requests

### Register
```json
POST /api/register
{
    "name": "John",
    "email": "john@example.com",
    "password": "password123"
}
```

### Create Expense
```json
POST /api/expenses
Authorization: Bearer {token}
{
    "category_id": 1,
    "amount": 25.50,
    "description": "Lunch",
    "date": "2026-07-01"
}
```