# Inventory Management System

A full-stack inventory management system built with vanilla PHP (MVC architecture) and MySQL.

## Features
- User authentication with session management and password hashing
- Product management with category and supplier relationships
- Stock tracking via a movement-based ledger (audit-trail friendly)
- Low stock alerts on the dashboard
- Purchase Order workflow with multi-line items — receiving an order automatically creates stock movements
- Soft deletes for suppliers and products
- REST API layer with token-based authentication (for a future POS frontend)
- Clean MVC architecture built from scratch (no framework)

## REST API
A token-based REST API layer sits alongside the main MVC application, reusing the same Model classes.

**Authentication**
POST /api/login
Body: { "email": "...", "password": "..." }
Response: { "token": "...", "user": {...} }

**Endpoints**
| Method | Endpoint | Auth Required | Description |
|---|---|---|---|
| POST | /api/login | No | Get an auth token |
| GET | /api/products | No | List all products |
| GET | /api/products/{id} | No | Get a single product |
| POST | /api/products | Yes (Bearer token) | Create a product |

Protected endpoints require an `Authorization: Bearer <token>` header.

## Tech Stack
- PHP (vanilla, custom MVC)
- MySQL (PDO with prepared statements)
- HTML, CSS, JavaScript

## Architecture
- `core/` — base App (router), Controller, and Model classes
- `app/controllers/` — request handling logic
- `app/models/` — database queries per entity
- `app/views/` — HTML templates
- `database/migrations/` — versioned SQL schema files

## Setup
1. Clone the repo
2. Copy `config/database.example.php` to `config/database.php` and add your DB credentials
3. Create a MySQL database and run the SQL files in `database/migrations/` in order
4. Point your local server (XAMPP/similar) to the project root
5. Ensure `mod_rewrite` is enabled for clean URLs

## Planned Enhancements
- Purchase Order module
- Point of Sale (POS) frontend