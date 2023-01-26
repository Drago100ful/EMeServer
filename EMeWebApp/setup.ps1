if(![System.IO.File]::Exists("./.env")) {
    Write-Output(".env Konfigurationsdatei nicht erstellt");
    pause;
    exit;
}

composer install
npm install
npm run build
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan optimize
php artisan serve