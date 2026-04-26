FROM php:8.2-apache

COPY . /var/www/html/

RUN docker-php-ext-install mysqli

# Make Apache listen on Railway PORT
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

ENV PORT=8080

EXPOSE 8080

CMD ["apache2-foreground"]
