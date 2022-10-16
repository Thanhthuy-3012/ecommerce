# ENV
- PHP 8.0, composer1, laravel8

# SETTING

- Step 1:
Install composer.
Link: https://getcomposer.org/doc/00-intro.md#using-the-installer

- Step 2:
Run cmd: composer install

- Step 3:
Run cmd: composer update

- Step 4:
Run cmd: npm install

- Step 5:
Run cmd: php artisan storage:link

- Step 6: reload config 
Run cmd: php artisan config:cache

- Step 7: migrate DB
Run cmd: php artisan migrate:fresh --seed

- Step 8: Generation APP key
Run cmd: php artisan key:generate 

- Step 9: Generation JWTAuth secret key used to sign the tokens
Run cmd: php artisan jwt:secret

- Step 10: Optimize cache
Run cmd: php artisan optimize

- Step 11: Start server
Run cmd: php artisan serve
