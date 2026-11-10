FROM php:8.3-apache

# SQLite já vem no PHP oficial; adicionamos o driver MySQL para quem quiser usar.
RUN docker-php-ext-install pdo_mysql \
 && a2enmod rewrite headers \
 && sed -ri 's!/var/www/html!/var/www/app/public!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
 && { echo 'upload_max_filesize=4M'; echo 'post_max_size=6M'; echo 'expose_php=Off'; } > /usr/local/etc/php/conf.d/app.ini \
 && echo 'ServerTokens Prod' >> /etc/apache2/apache2.conf && echo 'ServerSignature Off' >> /etc/apache2/apache2.conf

WORKDIR /var/www/app
COPY app ./app
COPY database ./database
COPY public ./public
RUN mkdir -p data public/uploads && chown -R www-data:www-data data public/uploads

# Hosts como o Render informam a porta em $PORT.
ENV PORT=80
CMD sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf \
 && sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf \
 && apache2-foreground
