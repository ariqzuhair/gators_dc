# Quick Setup Script for Gators DC Backend

Write-Host "=== Gators DC Backend Quick Setup ===" -ForegroundColor Cyan
Write-Host ""

# Instead of manually creating all Laravel files, let's use a fresh Laravel install
# Then copy over our custom files

Write-Host "This project requires a full Laravel installation." -ForegroundColor Yellow
Write-Host ""
Write-Host "To set up the backend properly, follow these steps:" -ForegroundColor Green
Write-Host ""
Write-Host "1. Delete the 'backend' folder contents (except our custom files)" -ForegroundColor White
Write-Host "2. Install Laravel fresh with MongoDB support" -ForegroundColor White
Write-Host "3. Copy back our Models, Controllers, and Routes" -ForegroundColor White
Write-Host ""
Write-Host "OR use the simplified approach:" -ForegroundColor Cyan
Write-Host ""
Write-Host "Run these commands:" -ForegroundColor White
Write-Host "docker exec gators_backend php artisan key:generate --force" -ForegroundColor Yellow
Write-Host "docker-compose restart backend" -ForegroundColor Yellow
Write-Host ""
Write-Host "Then access:" -ForegroundColor Green
Write-Host "- Frontend: http://localhost:5174" -ForegroundColor Cyan
Write-Host "- Backend: http://localhost:8001/api" -ForegroundColor Cyan
Write-Host "- MongoDB: localhost:27018" -ForegroundColor Cyan
Write-Host "- Nginx: http://localhost:8888" -ForegroundColor Cyan
