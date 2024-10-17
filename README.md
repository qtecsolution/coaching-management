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

5. Create & Setup Database

```shell
mysql -u {username} -p
```

```sql
CREATE DATABASE coaching_management;
```

```sql
GRANT ALL ON coaching_management.* TO '{your_username}'@'localhost' IDENTIFIED BY '{your_password}';
```

```sql
FLUSH PRIVILEGES;
EXIT;
```

Now update the `.env` file:

```shell
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coaching_management
DB_USERNAME={your_username}
DB_PASSWORD={your_password}
``` 

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
