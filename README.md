# WebCraft Studio

A complete Website Requirement & Quotation System for web agencies.

## Features

- 10-step customer requirement form with live price calculation
- Admin dashboard with requirement management
- Quotation builder with PDF export
- Dynamic pricing engine with feature-based calculations
- Mobile-responsive professional UI

## Tech Stack

- PHP 8.2 + Apache (Docker)
- MySQL
- Bootstrap 5.3
- Vanilla JavaScript

## Deployment

### Render (Recommended)

1. Create repo on GitHub
2. Push this code
3. Connect to Render → New Web Service
4. Render auto-detects `render.yaml` and `Dockerfile`
5. Database auto-initializes on first visit

### Local Development

```bash
# Import database
mysql -u root website_requirement_builder < database/database.sql

# Visit
http://localhost/website-requirement-builder
```

## Admin Access

- URL: `/admin/login.php`
- Email: `admin@demo.com`
- Password: `password`

## Environment Variables (Render)

| Variable | Description |
|----------|-------------|
| `DB_HOST` | MySQL host (auto-set by Render) |
| `DB_PORT` | MySQL port (auto-set by Render) |
| `DB_NAME` | Database name |
| `DB_USER` | Database user |
| `DB_PASS` | Database password |
