# 使用官方 PHP Apache 镜像
FROM php:8.2-apache

# 设置工作目录
WORKDIR /var/www/html

# 安装系统依赖和 PHP 扩展
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

# 设置权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 安装 Laravel 依赖（build 阶段）
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# 缓存配置和路由，提高性能
RUN php artisan config:cache
RUN php artisan route:cache

ENV PORT=10000
EXPOSE 10000

CMD sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf \
 && sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf \
 && apache2-foreground


# 生成 APP_KEY
# RUN php artisan key:generate

# # 设置 Apache 指向 Laravel public
# RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# # 开放端口
# EXPOSE 80
