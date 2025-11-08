.

📘 URL Shortener RESTful API — Laravel Project

🧩 Overview

This project is a simple URL Shortener RESTful API built with Laravel 12, using MySQL as the database.

It allows users to:

Shorten long URLs.

Redirect from short URLs to the original ones.

Track click counts.

List all shortened URLs.

Delete a specific URL (optional feature).

The project follows REST standards, handles validation, and uses proper HTTP status codes.

🚀 Installation & Setup

1️⃣ Clone the project
```bash
git clone https://github.com/yourusername/url-shortener.git
```

2️⃣ Install dependencies
composer install

4️⃣ Configure the database

Update .env with your MySQL credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=samplelaravel
DB_USERNAME=root
DB_PASSWORD=

5️⃣ Generate app key
php artisan key:generate

6️⃣ Run migrations
php artisan migrate

7️⃣ Start the development server
php artisan serve

Server will run at:
👉 http://127.0.0.1:8000

🔍 Example Workflow
🟢 Shorten a URL

curl -X POST http://127.0.0.1:8000/api/v1/shorten \
-H "Content-Type: application/json" \
-d '{"url": "https://www.example.com/some/very/long/path"}'

Response:
{
"short_url": "http://127.0.0.1:8000/TJUF4H"
}

🔵 Redirect to original URL
Visit:
http://127.0.0.1:8000/TJUF4H
You’ll be redirected (301) to the original URL.

🟡 View all shortened URLs

curl http://127.0.0.1:8000/api/v1/urls

🔴 Delete a shortened URL

curl -X DELETE http://127.0.0.1:8000/api/v1/urls/1

Run tests
(it uses mysql db for testing and not sqlite)

php artisan test

✅ Expected Output:


PASS  Tests\Feature\UrlShortenerTest
✓ shorten validation error
✓ can shorten url
✓ redirect increments clicks
✓ list urls returns records
✓ delete url

📄 Testing File: tests/Feature/UrlShortenerTest.php

Covers:

URL validation errors

Successful URL shortening

Redirection (301)

Click tracking

URL listing

Deletion


🧠 Notes

No authorization is required (no API keys or tokens).

Proper HTTP codes are returned:

201 → Created

301 → Moved Permanently

404 → Not Found

422 → Validation Error

200 → Success

Handles all errors gracefully with JSON responses.
