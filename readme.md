# PW Personal
Project is written on Laravel 5.7 and VueJS 2.0

## Getting Started

### Server Requirements
```
PHP >= 7.1.3
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
Ctype PHP Extension
JSON PHP Extension
BCMath PHP Extension
```
### Deployment
A step by step series of examples that tell you have to get a development env running.

To install the project packages run:
```$xslt
composer install --no-dev
```
```$xslt
npm install
```
After installing the packages copy `.env.example` to `.env` file in the root of the project and setup your configuration in this file.
```$xslt
cp .env.example .env // for copy file
```
Generate the ***APP KEY***
```$xslt
php artisan key:generate
```
Generate public views
```$xslt
npm run prod
```

###### For testing in local machine you can run project with command: `php artisan serve`
