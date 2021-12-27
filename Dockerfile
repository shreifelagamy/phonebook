FROM php:8.0-apache

COPY src/ /var/www/html/

# Update system core
RUN apt update -y && apt upgrade -y
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
