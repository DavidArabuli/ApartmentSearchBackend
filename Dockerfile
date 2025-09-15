
    
    FROM composer:2 AS vendor
    WORKDIR /app
    COPY composer.json composer.lock ./
    RUN composer install --no-dev --prefer-dist --no-progress --no-interaction
    

        FROM php:8.2-apache
        

RUN docker-php-ext-install pdo pdo_mysql \
    && apt-get update \
    && apt-get install -y procps curl vim less \
    && rm -rf /var/lib/apt/lists/*


RUN a2enmod rewrite


RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && echo "upload_max_filesize=20M" >> "$PHP_INI_DIR/php.ini" \
 && echo "post_max_size=20M" >> "$PHP_INI_DIR/php.ini" \
 && echo "memory_limit=512M" >> "$PHP_INI_DIR/php.ini" \
 && echo "max_execution_time=60" >> "$PHP_INI_DIR/php.ini"


WORKDIR /var/www/html

COPY . /var/www/html

COPY --from=vendor /app/vendor /var/www/html/vendor


RUN mkdir -p /var/www/html/cache \
 && chown -R www-data:www-data /var/www/html/cache

# Apache site config

RUN echo "<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride None\n\
        Require all granted\n\
\n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} !-f\n\
        RewriteCond %{REQUEST_FILENAME} !-d\n\
        RewriteRule ^ index.php [L]\n\
    </Directory>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf


EXPOSE 80


COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
