# Gunakan image dasar PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi mysqli (untuk koneksi ke MySQL)
RUN docker-php-ext-install mysqli

# Salin semua file project ke dalam folder kerja Apache di container
COPY . /var/www/html/

# Berikan permission (opsional, jika dibutuhkan)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
