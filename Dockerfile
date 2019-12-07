FROM php:7.4
MAINTAINER  Attila Szeremi <attila+webdev@szeremi.com>
WORKDIR /var/www
RUN cd /var/www

RUN apt-get update && apt-get install -y \
  # For installing node
  curl \
  wget \
  gnupg \

  # For "The `/path/to/project/node_modules/mozjpeg/vendor/cjpeg` binary doesn't seem to work correctly"
  # https://github.com/imagemin/imagemin-mozjpeg/issues/26
  nasm \
  libpng-dev \

  # To proxy to Swoole.
  nginx \

  # For composer
  libzip-dev \
  zlib1g-dev

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && \
  apt-get update && \
  apt-get install -y nodejs && \
  node --version && \
  npm --version

# This includes the docker-php-pecl-install executable
COPY bin/docker-php-pecl-install /usr/local/bin/

# PHP extensions
RUN docker-php-ext-install \
  pdo_mysql \
  # Newer Laravel Swoole needs this for some reason.
  pcntl \

  zip

RUN docker-php-pecl-install swoole

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY composer.json .
COPY composer.lock .

# These directories need to exist if we composer install without hooks/scripts.
RUN mkdir -p database/seeds
RUN mkdir -p database/factories

# Do not run hooks, because they require the project files to already be there.
# We want to be able to avoid complete (slow) composer installs in the Dockerfile with caching if possible.
RUN composer install --no-scripts

COPY package.json .
COPY package-lock.json .

RUN npm install

COPY resources resources
COPY webpack.mix.js .
COPY .babelrc .
RUN npm run production

COPY . .

RUN mkdir -p bootstrap/cache && chmod a+rwx bootstrap/cache

# This time, optimize and run hooks as well.
RUN composer install --optimize-autoloader

RUN [ \
 "/bin/bash", \
 "-c", \
  "mkdir -p storage/framework/{cache,sessions,views} && chmod -R a+rwx storage/framework/{cache,sessions,views}" \
]

# To try and make `php artisan self-diagnosis` happy.
RUN php artisan storage:link

# The parent directory of the sqlite database must be writable.
# https://github.com/wallabag/wallabag/issues/1845#issuecomment-205726683
RUN chmod a+rw database/

CMD ["bin/start.sh"]

