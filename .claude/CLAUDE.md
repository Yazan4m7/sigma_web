# Laravel Performance Optimization Project

## Database Information
- **Type**: MySQL
- **Connection**: Available via `database` MCP server
- **Direct queries**: You can query the database directly using the MCP server

## Performance Commands
```bash
# View recent requests (if Telescope installed)
php artisan telescope:list

# Show all routes
php artisan route:list

# Database info
php artisan db:show

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan optimize
```

## Performance Investigation Process
1. Check Laravel logs in `storage/logs/laravel.log` for slow query warnings
2. Query database directly to see table structures and indexes
3. Run EXPLAIN on suspicious queries
4. Look for N+1 queries (missing `->with()` on relationships)
5. Check for missing database indexes
6. Verify cache configuration in `.env`

## Common Performance Issues
- **N+1 Queries**: Missing eager loading (`->with(['relation'])`)
- **Missing Indexes**: Check columns used in WHERE, JOIN, ORDER BY
- **No Caching**: Verify CACHE_DRIVER is set (redis/memcached preferred)
- **Large Datasets**: Use `chunk()` or `cursor()` for iteration
- **Unoptimized Queries**: Check raw SQL and use EXPLAIN

## File Structure
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Routes: `routes/web.php` and `routes/api.php`
- Config: `config/`
- Migrations: `database/migrations/`
