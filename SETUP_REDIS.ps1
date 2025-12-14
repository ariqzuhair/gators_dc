# Gators DC - Redis Setup Script
# This script will restart your system with Redis enabled

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Gators DC - Redis & Token Expiration " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Stop existing containers
Write-Host "[1/5] Stopping existing containers..." -ForegroundColor Yellow
docker-compose down
Write-Host "[OK] Containers stopped" -ForegroundColor Green
Write-Host ""

# Step 2: Build and start containers with Redis
Write-Host "[2/5] Starting containers with Redis..." -ForegroundColor Yellow
docker-compose up -d
Write-Host "[OK] Containers started" -ForegroundColor Green
Write-Host ""

# Step 3: Wait for services to be ready
Write-Host "[3/5] Waiting for services to initialize (15 seconds)..." -ForegroundColor Yellow
Start-Sleep -Seconds 15
Write-Host "[OK] Services initialized" -ForegroundColor Green
Write-Host ""

# Step 4: Clear Laravel caches
Write-Host "[4/5] Clearing Laravel caches..." -ForegroundColor Yellow
docker exec gators_backend php artisan cache:clear | Out-Null
docker exec gators_backend php artisan config:clear | Out-Null
docker exec gators_backend php artisan route:clear | Out-Null
Write-Host "[OK] Caches cleared" -ForegroundColor Green
Write-Host ""

# Step 5: Test Redis connection
Write-Host "[5/5] Testing Redis connection..." -ForegroundColor Yellow
$redisPing = docker exec gators_redis redis-cli ping 2>&1
if ($redisPing -match "PONG") {
    Write-Host "[OK] Redis is working!" -ForegroundColor Green
} else {
    Write-Host "[ERROR] Redis connection failed" -ForegroundColor Red
}
Write-Host ""

# Display system status
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "           SYSTEM STATUS                " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "Running Containers:" -ForegroundColor White
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | Select-String "gators"
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "          QUICK COMMANDS                " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Test Redis:         " -NoNewline
Write-Host "docker exec -it gators_backend php artisan tinker" -ForegroundColor Yellow
Write-Host "                    " -NoNewline
Write-Host "Cache::put('test', 'hello', 60)" -ForegroundColor Yellow
Write-Host ""
Write-Host "Clean Tokens:       " -NoNewline
Write-Host "docker exec -it gators_backend php artisan tokens:cleanup" -ForegroundColor Yellow
Write-Host ""
Write-Host "View Redis Keys:    " -NoNewline
Write-Host "docker exec -it gators_redis redis-cli keys '*'" -ForegroundColor Yellow
Write-Host ""
Write-Host "Monitor Redis:      " -NoNewline
Write-Host "docker exec -it gators_redis redis-cli MONITOR" -ForegroundColor Yellow
Write-Host ""
Write-Host "View Logs:          " -NoNewline
Write-Host "docker-compose logs -f backend" -ForegroundColor Yellow
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "        ACCESS URLS                     " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Frontend:   " -NoNewline
Write-Host "http://localhost:5174" -ForegroundColor Cyan
Write-Host "Backend:    " -NoNewline
Write-Host "http://localhost:8001" -ForegroundColor Cyan
Write-Host "Nginx:      " -NoNewline
Write-Host "http://localhost:8888" -ForegroundColor Cyan
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "    [OK] Setup Complete! System Ready  " -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "For more info, see REDIS_SETUP_GUIDE.md" -ForegroundColor White
Write-Host ""
