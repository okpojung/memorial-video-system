```apacheconf
COMPOSER_MEMORY_LIMIT=-1 composer create-project laravel/laravel=9.* wedt_api --prefer-dist
```

```apacheconf
composer dump-autoload
```

```apacheconf
php artisan make:migration create_users_table --create=users --path=/database/migrations/User
```

```apacheconf

php artisan migrate --path=/database/migrations/Wedt
```

```apacheconf
php artisan make:model Api\Wedt\UserEduInfo
```

```apacheconf
php artisan make:seeder Wedt\SidosTableSeeder
```

```apacheconf
php artisan make:controller Api/UserController --api
```

```apacheconf
php artisan route:cache
```

```apacheconf
php artisan route:list
```

```apacheconf
php artisan config:clear
```

```apacheconf
php artisan make:factory RatingFactory --model RatingFactory
```

```apacheconf
php artisan make:resource Wedt\UserResource
```

```apacheconf
php artisan vendor:publish --tag=sanctum-migrations
```
```apacheconf
php artisan migrate --database=mysql_wedt
```

```apacheconf
php artisan migrate --path=/database/migrations/Wedt
```

```apacheconf
"require": {
    "ext-http": "*",
}
```

```apacheconf
php artisan make:event PodcastProcessed
```

```apacheconf
php artisan make:listener SendPodcastNotification --event=PodcastProcessed
```

```apacheconf
php artisan event:cache
```
