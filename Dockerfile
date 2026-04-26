FROM php:8.2-apache

COPY . /var/www/html/

RUN docker-php-ext-install mysqli

# Make Apache use Railway PORT
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
