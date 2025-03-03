1. cp .env.example .env
2. set DB params in .env 
3. composer install
4. npm install
5. app:create-database
6. php artisan exchange:update (it will take a long time to complete since the free version of the API has a limit of one request per second), It will be good if you change the key(ANYAPI_KEY in .env) to your own since the API has a limit of 300 requests per year 
7. php artisan serve
8. npm run dev 