if [! -f "./.env"] {
    read -s -n 1 -p ".env Konfigurationsdatei nicht erstellt"
    exit
}

composer install
npm install
npm run build
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan optimize
php artisan serve