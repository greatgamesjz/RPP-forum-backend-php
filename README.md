# RPP Forum

## Installation

```bash
git clone https://github.com/greatgamesjz/RPP-forum-backend-php.git
cd RPP-forum-backend-php
composer install
```

create database and modify .env file

```bash
php bin/console doctrine:schema:update --force
php bin/console app:data-fixture
```


## Usage

Komenda do tworzenia usera

php bin/console app:user:create --nickname (nick) --password (hasło) --email (email) --role (np.USER/ADMIN)

## License
