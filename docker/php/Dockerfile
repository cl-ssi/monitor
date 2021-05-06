FROM php:7-fpm

RUN apt-get clean && \
    apt-get update &&  \
	apt-get install -y --fix-missing \
		libsqlite3-dev \
		libzip-dev \
		zlib1g-dev \
		libpng-dev \
		libonig-dev \
		nginx && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    pecl -q install redis && \
    docker-php-ext-enable redis && \
    docker-php-ext-install -j$(nproc) pcntl && \
	docker-php-ext-install -j$(nproc) pdo && \
	docker-php-ext-install -j$(nproc) pdo_mysql && \
	docker-php-ext-install -j$(nproc) pdo_sqlite && \
	docker-php-ext-install -j$(nproc) zip && \
	docker-php-ext-install -j$(nproc) mbstring && \
	docker-php-ext-install -j$(nproc) gd

RUN pecl -q install xdebug
RUN docker-php-ext-enable xdebug
