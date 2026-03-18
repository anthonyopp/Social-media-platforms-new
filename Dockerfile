# 使用官方 PHP + Apache 镜像
FROM php:8.2-apache

# 设置工作目录
WORKDIR /var/www/html

# 安装系统依赖和 PHP 扩展
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libonig-dev zip curl libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 启用 Apache rewrite
RUN a2enmod rewrite

# ⭐ 设置 Apache 指向 Laravel public 目录
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 避免 ServerName 警告
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 安装 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 复制 Laravel 项目文件
COPY . /var/www/html

# 设置 Laravel 文件权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 安装 Laravel 依赖
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# 清理 Laravel 缓存（防止旧配置报错）
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan cache:clear

# 暴露端口（Render 会自动注入 $PORT）
EXPOSE 10000

# CMD 使用 Render 提供的 $PORT 环境变量启动 Apache
CMD sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf \
 && sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf \
 && apache2-foreground
