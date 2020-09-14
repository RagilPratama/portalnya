FROM php:7.3-apache
RUN apt-get update
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql   
RUN mkdir /app	
COPY ./ /app/
COPY .conf/vhost.conf /etc/apache2/sites-available/000-default.conf
# RUN chown -R www-data:www-data /app \
#     && a2enmod rewrite
RUN a2enmod rewrite
RUN chown -R www-data:www-data /app/storage
RUN chown -R www-data:www-data /app/bootstrap/cache
RUN chmod -R 775 /app/storage
RUN chmod -R 775 /app/bootstrap/cache
EXPOSE 80
