FROM php:8.2-cli

# install dependency
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# set working directory
WORKDIR /app

# copy project
COPY . .

# install dependency laravel
RUN composer install --no-dev --optimize-autoloader

# build frontend
RUN npm install && npm run build

# expose port
EXPOSE 8000

# run laravel
CMD php artisan serve --host=0.0.0.0 --port=8000