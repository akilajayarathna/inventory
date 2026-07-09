# Inventory Management System

A full-stack inventory management system built with vanilla PHP (MVC architecture) and MySQL.

## Features
- User authentication with session management and password hashing
- Product management with category and supplier relationships
- Stock tracking via a movement-based ledger (audit-trail friendly)
- Low stock alerts on the dashboard
- Soft deletes for suppliers and products
- Clean MVC architecture built from scratch (no framework)

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
- REST API layer for POS integration