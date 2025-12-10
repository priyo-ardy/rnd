# Gunakan image Ubuntu 22.04 LTS sebagai dasar
FROM docker.io/library/ubuntu:24.04

# Set environment variable agar tidak ada prompt interaktif saat instalasi
ENV DEBIAN_FRONTEND=noninteractive

# Update, install software properties, dan PPA untuk PHP 8.3
RUN apt-get update && \
    apt-get install -y software-properties-common ca-certificates curl gnupg && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update

# Install Nginx, Supervisor, PHP 8.3 dan ekstensi yang umum dibutuhkan CodeIgniter 4
RUN apt-get install -y \
    nginx \
    supervisor \
    php8.3-fpm \
    php8.3-cli \
    php8.3-common \
    php8.3-mysql \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-intl \
    php8.3-curl \
    php8.3-gd \
    php8.3-zip \
    php8.3-ldap \
    php8.3-opcache \
    php8.3-bcmath \
    php8.3-imagick \
    php8.3-redis \
    php8.3-memcached \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer (Dependency Manager untuk PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set direktori kerja
WORKDIR /var/www/html

# Salin semua file proyek Anda ke dalam direktori kerja di container
COPY . .

# Install dependency PHP menggunakan Composer
RUN composer install --no-dev --optimize-autoloader

# Jalankan php spark migrate
RUN php spark migrate

# jalankan database seeder bernaka Restore.php
RUN php spark db:seed RestoreData


# Salin file konfigurasi Nginx dan Supervisor
COPY config/nginx.conf /etc/nginx/sites-available/default
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Berikan hak akses yang benar untuk CodeIgniter, terutama pada folder 'writable'
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/writable

# Buka port 80 di dalam container (Nginx akan berjalan di port ini)
EXPOSE 80

# Jalankan Supervisor saat container dimulai
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
