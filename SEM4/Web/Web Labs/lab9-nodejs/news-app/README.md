# News Service — Angular Frontend + PHP Backend

This project replaces the original PHP/HTML frontend with an Angular single-page application
while keeping the same PHP backend (MySQL, AJAX/JSON API).

## Project Structure

```
news-app/
├── php-backend/          # PHP API files (served at localhost:8080)
│   ├── cors.php          # CORS headers helper
│   ├── db.php            # Database connection
│   ├── fetch_news.php    # GET news (with optional filters)
│   ├── login.php         # POST login → JSON response
│   ├── logout.php        # GET logout → JSON response
│   └── admin.php         # CRUD API for news articles
├── src/app/
│   ├── components/
│   │   ├── navbar/       # Navigation bar (shared)
│   │   ├── news-list/    # Home page — news list with filters
│   │   ├── article-detail/  # Single article view
│   │   ├── about/        # About page with stats
│   │   ├── login/        # Admin login form
│   │   └── admin/        # Admin panel — add/edit/delete news
│   ├── services/
│   │   ├── news.ts       # NewsService — HTTP calls to PHP API
│   │   └── auth.ts       # AuthService — login/logout/session
│   ├── guards/
│   │   └── auth.guard.ts # Route guard for /admin
│   ├── models/
│   │   └── news.model.ts # TypeScript interfaces
│   ├── app.routes.ts     # Angular routing
│   └── app.config.ts     # App providers (HttpClient, Router)
├── docker-compose.yml    # Docker setup for PHP + MySQL
└── init.sql              # Database schema + sample data
```

## How to Run

### 1. Start the PHP + MySQL backend

```bash
docker-compose up -d
```

This starts:
- PHP/Apache at `http://localhost:8080`
- MySQL at `localhost:3306` (database: `news_db`, user: `root`, password: `root`)

### 2. Start the Angular frontend

```bash
npm install
ng serve
```

Open `http://localhost:4200` in your browser.

### 3. Login credentials

- Username: `admin`
- Password: `admin`

## Angular Features Used

- **Components**: 6 standalone components (Navbar, NewsList, ArticleDetail, About, Login, Admin)
- **Services**: NewsService (HTTP/AJAX), AuthService (session management)
- **Routing**: Angular Router with 5 routes + wildcard redirect
- **Route Guard**: `authGuard` protects the `/admin` route
- **Two-way binding**: `[(ngModel)]` for all forms
- **Control flow**: `@if`, `@for`, `@empty` blocks
- **Pipes**: `titlecase`, `slice`
- **HttpClient**: GET/POST requests to PHP backend with `withCredentials`
- **localStorage**: Remembers previous filter + login state
