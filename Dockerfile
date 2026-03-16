# 使用 PHP 8.2 Apache 官方镜像
FROM php:8.2-apache

# 设置工作目录
WORKDIR /var/www/html

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 启用 Apache rewrite
RUN a2enmod rewrite

# 安装 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 复制 Laravel 项目文件
COPY . /var/www/html

# 设置文件权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 安装 Laravel 依赖
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# 缓存配置和路由
RUN php artisan config:cache
RUN php artisan route:cache

# 生成 Laravel APP KEY
RUN php artisan key:generate

# 设置 Apache 指向 Laravel public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 开放端口
EXPOSE 80
