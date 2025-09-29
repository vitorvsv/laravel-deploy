# =========================
# 1. Build stage (Composer)
# =========================
FROM composer:2 AS build

WORKDIR /app

# Copia apenas os arquivos necessários para o composer
COPY src/composer.json src/composer.lock ./

# Copia o arquivo artisan que é necessário para os scripts do composer
COPY src/artisan ./

# Instala dependências sem executar scripts que dependem do Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Copia o restante do código
COPY src .

# Executa os scripts do composer agora que todo o código está disponível
RUN composer run-script post-autoload-dump

# Gera cache de configuração do Laravel
# RUN php artisan config:cache && \
#   php artisan route:cache && \
#   php artisan view:cache

# =========================
# 2. Production stage
# =========================
FROM php:8.2-fpm-alpine

# Instala dependências necessárias
RUN apk add --no-cache \
  nginx \
  supervisor \
  bash \
  curl \
  libpng-dev \
  libjpeg-turbo-dev \
  freetype-dev \
  zip \
  unzip \
  icu-dev \
  oniguruma-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl

WORKDIR /var/www/html

# Copia arquivos da build (Laravel já com vendor)
COPY --from=build /app /var/www/html

# Copia configuração do Nginx
COPY ./nginx/nginx.production.conf /etc/nginx/nginx.conf

# Copia configuração do Supervisor (para gerenciar PHP-FPM e Nginx juntos)
COPY ./supervisord/supervisord.conf /etc/supervisord.conf

# Permissões corretas para storage e bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Permissão correta para o sqlite (verifica se o arquivo existe antes)
# RUN if [ -f /var/www/html/database/database.sqlite ]; then \
#   chown www-data:www-data /var/www/html/database/database.sqlite; \
#   fi
RUN chown www-data:www-data /var/www/html/database/database.sqlite

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]