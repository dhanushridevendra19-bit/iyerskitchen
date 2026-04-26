FROM php:8.2-apache

COPY . /var/www/html/

RUN docker-php-ext-install mysqli

# Fix for Railway dynamic port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
