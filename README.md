## Project Setup Guide

1. Clone the project

```shell
git clone https://github.com/qtecsolution/coaching-management.git
cd coaching-management
```

2. Install the dependencies

```shell
composer install
```
```shell
npm install
```

3. Copy `.env.example` to `.env`

```shell
cp .env.example .env
```

4. Generate application key

```shell
php artisan key:generate
```

5. Create & setup database

6. Migrate database (with seeder)

```shell
php artisan migrate --seed
```

7. Start the project

```shell
php artisan serve
```
```shell
npm run dev (build)
```
