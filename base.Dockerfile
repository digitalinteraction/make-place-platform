#
# Base image Dockerfile for Make Place
# Specifically sets up all packages without adding any project code
#


# Start with a php-fpm-nginx-composer image
FROM openlab.ncl.ac.uk:4567/rob/composer-image:1.1.2

# Setup composer & node, then add some www-data owned direcotires
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - > /dev/null \
  && apt-get -qq update \
  && DEBIAN_FRONTEND=noninteractive apt-get -q -y install sqlite3 php5-sqlite nodejs \
  && rm -rf /var/lib/apt/lists/* \
  && mkdir -p /app/silverstripe-cache \
  && mkdir -p /app/assets/surveymedia \
  && mkdir -p /backup/db \
  && touch /app/silverstripe.log \
  && chown -R www-data /app/ \
  && chown -R www-data /backup

# Add package configuration files
COPY ["package.json", "package-lock.json", "composer.json", "composer.lock", "/app/"]

# Install packages & setup
RUN /composer.phar -q install && npm install -s

# Add volumes for assets & backup data
VOLUME ["/app/assets", "/backup", "/app/silverstripe-cache"]
