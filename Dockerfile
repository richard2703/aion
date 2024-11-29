# Create args for PHP extensions and PECL packages we need to install.
# This makes it easier if we want to install packages,
# as we have to install them in multiple places.
# This helps keep ou Dockerfiles DRY -> https://bit.ly/dry-code
# You can see a list of required extensions for Laravel here: https://laravel.com/docs/8.x/deployment#server-requirements
ARG PHP_EXTS="bcmath ctype fileinfo mbstring pdo pdo_mysql dom pcntl"
ARG PHP_PECL_EXTS="redis"

# We need to build the Composer base to reuse packages we've installed
FROM composer:latest as composer_base

# We need to declare that we want to use the args in this build step
ARG PHP_EXTS
ARG PHP_PECL_EXTS

# First, create the application directory, and some auxilary directories for scripts and such
RUN mkdir -p /opt/apps/aion-k8s /opt/apps/aion-k8s/bin

# Next, set our working directory
WORKDIR /opt/apps/aion-k8s

# We need to create a composer group and user, and create a home directory for it, so we keep the rest of our image safe,
# And not accidentally run malicious scripts
RUN addgroup -S composer
RUN adduser -S composer -G composer 
RUN chown -R composer /opt/apps/aion-k8s
RUN apk add --virtual build-dependencies --no-cache ${PHPIZE_DEPS} openssl ca-certificates libxml2-dev oniguruma-dev 
RUN docker-php-ext-install -j$(nproc) ${PHP_EXTS} 
RUN pecl install ${PHP_PECL_EXTS} 
RUN docker-php-ext-enable ${PHP_PECL_EXTS} 
RUN apk del build-dependencies

# Next we want to switch over to the composer user before running installs.
# This is very important, so any extra scripts that composer wants to run,
# don't have access to the root filesystem.
# This especially important when installing packages from unverified sources.
USER composer

# Copy in our dependency files.
# We want to leave the rest of the code base out for now,
# so Docker can build a cache of this layer,
# and only rebuild when the dependencies of our application changes.
COPY --chown=composer composer.json ./

# Install all the dependencies without running any installation scripts.
# We skip scripts as the code base hasn't been copied in yet and script will likely fail,
# as `php artisan` available yet.
# This also helps us to cache previous runs and layers.
# As long as comoser.json and composer.lock doesn't change the install will be cached.
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-req=ext-gd

# Copy in our actual source code so we can run the installation scripts we need
# At this point all the PHP packages have been installed, 
# and all that is left to do, is to run any installation scripts which depends on the code base
COPY --chown=composer . .

# Now that the code base and packages are all available,
# we can run the install again, and let it run any install scripts.
RUN composer install --no-dev --prefer-dist --ignore-platform-req=ext-gd




# For the frontend, we want to get all the Laravel files,
# and run a production compile
FROM node:14 as frontend

# We need to copy in the Laravel files to make everything is available to our frontend compilation
COPY --from=composer_base /opt/apps/aion-k8s /opt/apps/aion-k8s

WORKDIR /opt/apps/aion-k8s

# We want to install all the NPM packages,
# and compile the MIX bundle for production
RUN npm install && npm run build
    #npm run serve-all




# We need a stage which contains FPM to actually run and process requests to our PHP application.
FROM php:8.2-fpm-alpine as fpm_server

# We need to declare that we want to use the args in this build step
ARG PHP_EXTS
ARG PHP_PECL_EXTS

WORKDIR /opt/apps/aion-k8s

RUN apk add --virtual build-dependencies --no-cache ${PHPIZE_DEPS} openssl ca-certificates libxml2-dev oniguruma-dev && \
    docker-php-ext-install -j$(nproc) ${PHP_EXTS} && \
    pecl install ${PHP_PECL_EXTS} && \
    docker-php-ext-enable ${PHP_PECL_EXTS} && \
    apk del build-dependencies
    
# As FPM uses the www-data user when running our application,
# we need to make sure that we also use that user when starting up,
# so our user "owns" the application when running
USER  www-data

# We have to copy in our code base from our initial build which we installed in the previous stage
COPY --from=composer_base --chown=www-data /opt/apps/aion-k8s /opt/apps/aion-k8s
COPY --from=frontend --chown=www-data /opt/apps/aion-k8s/public /opt/apps/aion-k8s/public

# We want to cache the event, routes, and views so we don't try to write them when we are in Kubernetes.
# Docker builds should be as immutable as possible, and this removes a lot of the writing of the live application.
RUN php artisan event:cache && \
    php artisan route:cache && \
    php artisan view:cache





# We need an nginx container which can pass requests to our FPM container,
# as well as serve any static content.
FROM nginx:1.20-alpine as web_server

WORKDIR /opt/apps/aion-k8s

# We need to add our NGINX template to the container for startup,
# and configuration.
COPY docker/nginx.conf.template /etc/nginx/templates/default.conf.template

# Copy in ONLY the public directory of our project.
# This is where all the static assets will live, which nginx will serve for us.
COPY --from=frontend /opt/apps/aion-k8s/public /opt/apps/aion-k8s/public
