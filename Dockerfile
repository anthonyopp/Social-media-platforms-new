FROM php:8.2-apache

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip

# 安装 PHP 扩展
RUN docker-php-ext-install pdo pdo_mysql zip

# 启用 Apache rewrite
RUN a2enmod rewrite

# 设置 Apache 指向 Laravel public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 设置工作目录
WORKDIR /var/www/html

# 复制项目文件
COPY . /var/www/html

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 安装 Laravel 依赖
RUN composer install --no-dev --optimize-autoloader

# 设置权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 生成 Laravel APP KEY
RUN php artisan key:generate

# 开放端口
EXPOSE 80
