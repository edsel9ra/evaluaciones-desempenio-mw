# AGENTS.md - Performance Evaluation System

## Quick Start

```bash
# Start full dev environment (all Docker services)
docker compose up -d

# Or manually:
cd src && composer run dev
```

## Dev Commands

| Command | Description |
|---------|-------------|
| `composer run setup` | Install dependencies, generate key, migrate, build assets |
| `composer run test` | Run PHPUnit tests |
| `npm run dev` | Vite dev server (port 5173) |
| `npm run build` | Build frontend assets |

## Docker Services

| Service | Port | Purpose |
|--------|------|---------|
| nginx | 8080 | Web application |
| db | 3307 | MySQL 8.4 |
| phpmyadmin | 8081 | Database admin |
| vite | 5173 | Frontend HMR |

## Architecture

- **Pattern**: Service Layer (Controllers → Services → Models)
- **Frontend**: Vue 3 + Inertia.js (SPA)
- **Styling**: Tailwind CSS 4
- **Auth**: Session-based, username/password

## Database

- MySQL (Docker service `db`)
- Port: 3307 (not 3306)
- Host: `db` (Docker service name, not localhost)
- Migrations: `src/database/migrations/`

## Routes

All routes require auth except:
- `/login` (GET/POST)
- `/` → redirects to `/login`

## Important Notes

1. Uses Docker Compose - always `docker compose up -d` to start
2. DB_HOST in .env is `db` (Docker service), not `localhost`
3. App runs on port 8080 (nginx), not Laravel default 8000
4. Frontend requires pnpm (not npm/yarn) - see docker/node/Dockerfile
5. Session driver: database, lifetime: 120 minutes