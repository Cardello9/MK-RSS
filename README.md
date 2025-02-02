# MK-RSS
Displays RSS Feed from external sources.

Runs on Symfony 6.3.8.

## Requirements
1. PHP 8.1 or higher
2. Apache

(Runs well on XAMPP, does not require database)

## Docker setup
1. Ensure you have Docker installed
2. Create file named "Dockerfile" (without extension)
3. Paste this content into Dockerfile
```
FROM php:8.1-apache

RUN apt-get update && apt-get install -y git
RUN git clone https://github.com/Cardello9/MK-RSS.git /var/www/html/

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --optimize-autoloader

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
```

4. run command "docker build --no-cache -t mk-rss ."
5. run command "docker run --name mk-rss -p 80:80 mk-rss"
6. Open link "http://localhost/" in browser

![Alt text](/public/screenshots/homepage_desktop.png?raw=true "Homepage - desktop")
![Alt text](/public/screenshots/homepage_mobile.png?raw=true "Homepage - mobile")
