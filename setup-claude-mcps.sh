#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}=== Claude Code MCP Setup for Laravel ===${NC}\n"

# Check if .env file exists
if [ ! -f .env ]; then
    echo -e "${RED}Error: .env file not found!${NC}"
    echo "Make sure you're running this from your Laravel project root."
    exit 1
fi

# Extract database credentials from .env
echo -e "${YELLOW}Reading database credentials from .env...${NC}"

DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d '=' -f2)
DB_HOST=$(grep "^DB_HOST=" .env | cut -d '=' -f2)
DB_PORT=$(grep "^DB_PORT=" .env | cut -d '=' -f2)
DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d '=' -f2)
DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2)

# Default values if not set
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}

echo -e "${GREEN}Found:${NC}"
echo "  Connection: $DB_CONNECTION"
echo "  Host: $DB_HOST"
echo "  Port: $DB_PORT"
echo "  Database: $DB_DATABASE"
echo "  Username: $DB_USERNAME"
echo ""

# Build MySQL connection string
MYSQL_URL="mysql://${DB_USERNAME}:${DB_PASSWORD}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

echo -e "${YELLOW}Setting up MCP servers...${NC}\n"

# 1. MySQL Database MCP
echo -e "${GREEN}1. Adding MySQL database MCP...${NC}"
claude mcp add laravel-db -- npx -y @benborla/mcp-server-mysql "$MYSQL_URL"

# 2. Filesystem access for the project
echo -e "${GREEN}2. Adding filesystem access for project...${NC}"
claude mcp add laravel-files -- npx -y @modelcontextprotocol/server-filesystem "$(pwd)"

# 3. Laravel logs access
echo -e "${GREEN}3. Adding Laravel logs access...${NC}"
claude mcp add laravel-logs -- npx -y @modelcontextprotocol/server-filesystem "$(pwd)/storage/logs"

# 4. Create project-level .mcp.json
echo -e "${YELLOW}Creating .mcp.json for team sharing...${NC}"

cat > .mcp.json << EOF
{
  "mcpServers": {
    "database": {
      "command": "npx",
      "args": ["-y", "@benborla/mcp-server-mysql"],
      "env": {
        "MYSQL_URL": "$MYSQL_URL"
      }
    },
    "filesystem": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-filesystem", "."]
    },
    "logs": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-filesystem", "./storage/logs"]
    }
  }
}
EOF

echo -e "${GREEN}.mcp.json created!${NC}\n"

# 5. Create CLAUDE.md if it doesn't exist
if [ ! -d .claude ]; then
    mkdir -p .claude
fi

if [ ! -f .claude/CLAUDE.md ]; then
    echo -e "${YELLOW}Creating CLAUDE.md with project context...${NC}"
    
    cat > .claude/CLAUDE.md << 'EOF'
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
EOF

    echo -e "${GREEN}CLAUDE.md created in .claude/ directory!${NC}\n"
fi

# Verify setup
echo -e "${YELLOW}Verifying MCP setup...${NC}"
claude mcp list

echo -e "\n${GREEN}=== Setup Complete! ===${NC}"
echo -e "\n${YELLOW}Next steps:${NC}"
echo "1. Test the connection: ${GREEN}claude \"show me the users table schema\"${NC}"
echo "2. Start debugging: ${GREEN}claude \"analyze performance of /users endpoint\"${NC}"
echo "3. The .mcp.json file has been created - commit it to share with your team"
echo ""
echo -e "${YELLOW}Note:${NC} Keep your .env file private and out of version control!"
